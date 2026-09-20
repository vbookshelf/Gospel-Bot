<?php
session_start();

// ERROR LOGGING
// Php errors are logged to a file named: php-errors.log
// This file will be automatically created the first time
// an error occurs.


// ADD YOUR OPENROUTER API KEY
// Get a key from https://openrouter.ai/keys and add it to the
// ebot_config.ini.txt file. Change the file name from
// ebot_config.ini.txt to ebot_config.ini
// The ebot_config.ini file gets loaded in the php funtion that
// makes the API request.


// *** IMPORTANT SECURITY NOTE ***
// The ebot_config.ini file is currently located inside the website root folder.
// Please Secure your API Key by moving the ebot_config.ini file
// to a folder that's located outside your website root folder.
// Specify the path to the ebot_config.ini file here.

$path_to_config_ini = 'ebot_config.ini';


// OpenRouter uses a single, OpenAI-compatible chat completions endpoint.
// The specific model (and provider) are chosen per-request in the
// request body, not via the URL - see the agent config below.
$url = "https://openrouter.ai/api/v1/chat/completions";

// OpenRouter uses these headers to attribute/rank apps on openrouter.ai.
// They're optional but recommended. Update to match your own site.
$site_url = "https://my-website.com";
$site_title = "Gospel Bot";



//-----------------------------
// Agent model / provider config
//-----------------------------
// A single agent runs the whole conversation now - no separate
// proofreader or translator.

$chat_agent_config = [
	"model_id" => "qwen/qwen3.5-flash-02-23",
	"provider" => "alibaba",
];



//-----------
// Settings
//-----------

$temperature = 0.5;

$max_tokens = 1000;

// Maximum number of user/model turn PAIRS to keep in the chat history.
// Once this is exceeded, the oldest pair is dropped (FIFO), one pair
// at a time, so a user turn and its matching model turn are always
// removed together - the history never ends up with a dangling turn.
$max_history_pairs = 20;

// Set how fast the text is spoken
$speech_rate = 1;

// English only - Gospelbot doesn't offer multi-language support.
$speech_lang_code = "en-GB";
$speech_voice_name = "Serena";


// If the message history session variable does NOT exist
if (!isset($_SESSION['message_history'])) {

	// Create a message_history list
	$_SESSION['message_history'] = array();
	$message_history = $_SESSION['message_history'];

} else {

	// Assign the session variable
	$message_history = $_SESSION['message_history'];

}


// This function cleans and secures the user input
function test_input(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);

		return $data;
	}

	/**
	 * Backstop for the "LLM always ends with two questions" habit.
	 * If the reply's last two sentences both end in "?", drop the final
	 * question so the reply ends on its second-to-last sentence instead.
	 * This only trims a trailing pair; it does not touch questions earlier
	 * in the reply.
	 */
	function limit_trailing_questions($text) {
		// Split into sentences, keeping the punctuation attached.
		preg_match_all('/[^.!?]+[.!?]+(?:\s+|$)/u', $text, $matches);
		$sentences = $matches[0];

		if (count($sentences) < 2) {
			return $text;
		}

		$last  = trim(end($sentences));
		$second_to_last = trim($sentences[count($sentences) - 2]);

		$last_is_question = substr($last, -1) === '?';
		$second_is_question = substr($second_to_last, -1) === '?';

		if ($last_is_question && $second_is_question) {
			// Drop the final sentence, keep everything before it.
			array_pop($sentences);
			$text = trim(implode('', $sentences));
		}

		return $text;
	}


// This code is triggered when the user submits a message.
// The form data arrives here via Ajax.
if (isset($_REQUEST["my_message"]) && empty($_REQUEST["robotblock"])) {


	// Check the status of the radio button
	if (isset($_REQUEST["speak1"])) {
		$speak_request = 'selected';
	} else {
		$speak_request = 'not_selected';
	}


	// Get the user's message and clean/secure it.
	$user_message = isset($_REQUEST["my_message"]) ? $_REQUEST["my_message"] : '';
	$user_message = test_input($user_message);


	//---------------------
	// Run the chat agent
	//---------------------
	// Creates Gospelbot's responses to the user's chat messages.


$chat_agent_system_message = <<<EOT
You are Gospel Bot.
You are a friendly christian chatbot.
Your task is to share the Good News to users.
You follow the five step format that's explained in the given documentation.
The documentation is delimited by four hash tags, ####.
You always greet the user and introduce yourself.
You always ask for permission to share the gospel.
You share one step at a time.
You always explain each step using an illustration.
After each step you confirm that the user has understood.
You always confirm that the user has understood the fifth step.
You summarize the gospel.
You ask if they would like to receive the gift of eternal life.
If their answer is yes you lead them in the salvation prayer.
You welcome them to God's family.
You assure them that salvation happens instantly by quoting John 6:47.
You assure them that salvation is permanent by quoting John 1:12.


Here are some good ways to gently confirm that the user has understood:

Good_Example_1: Does that make sense? Please let me know if you feel you've understood this first step, and we'll gently move on to the next part together.
Good_Example_2: Does this make sense so far? Please let me know if you've understood so we can calmly continue.
Good_Example_3: Does that explanation clear up how God's love and justice work together? Please let me know when you feel comfortable, and we'll move on to the next step.
Good_Example_4: Is that explanation clear? Please let me know when you feel comfortable, and we'll ease into the next step.
Good_Example_5: Have you understood this final step? Once you confirm, we'll finish up.
Good_Example_6: Do you want to receive the free gift of eternal life? I know this question may sound strange coming from a bot, but what's important is not who asks the question - it's how you respond.
Good_Example_7: Please let me know when you've finished praying the prayer.

Never ask blunt questions like these:

Bad_Example_1: Do you understand what I mean?
Bad_Example_2: Have you understood this first point?

When you show the user the prayer, always stop at that point. 
This will allow the user an opportunity to refelect and to pray that prayer.

When you've finished the conversation always wish the user well and end the conversation.
Do not ask: 
Is there anything else I can do for you right now?
Is there anything else I can pray for you right now?

Your output is being converted to speech using a TTS system. 
Therefore, please keep your responses conversational.

Your sole task is to share the gospel. 
Never invite to user to come back to chat on other topics.

####

The Good News of
JESUS CHRIST
in 5 Simple Steps


1
HEAVEN IS A FREE GIFT.
IT CAN'T BE EARNED OR DESERVED.

Imagine that it's the morning of your birthday. Your mum surprises you with an expensive gift - the latest iPhone. "Wow! Thanks Mum!" you say. Then you reach into your pocket for some change to pay your mum. If you pay, would it be a gift? It wouldn't. Also, trying to pay your mum would be an insult.

Or let's say a dad wants to encourage his teenage daughter to work hard in school so he tells her, "If you get all A's this year I'll buy you a car for Christmas."

At the end of the year she gets all A's and, true to his word, he buys her a car. Is it a gift? No. It's actually a reward for performance.

A gift must be freely given and be freely received. If you have to pay, or do something in return - it's not a gift.

The Bible tells us that heaven (eternal life) is a completely free gift:

God saved you by his grace when you believed. And you can't take credit for this; it is a gift from God. Salvation is not a reward for the good things we have done, so none of us can boast about it.
Ephesians 2:8-9

For the wages of sin is death but the free gift of God is eternal life through Christ Jesus our Lord.
Romans 6:23




2
WE ARE ALL SINNERS.
WE CANNOT SAVE OURSELVES.
	
No one deserves a place in heaven. And no one can earn a place.

Suppose you were making an omelette that needed six eggs. One by one you crack open each egg and drop the yolk into a bowl. You get to the last one, crack it open and drop it in. Suddenly you turn your head away and pinch your nose. The last egg is rotten.

You have no choice but to throw it all away. Although there are five good eggs in the bowl, one bad egg spoils everything.

Just as you wouldn't serve your family an omelette contaminated by one bad egg, we can't bring to a holy God a life contaminated by even one sin and expect him to accept it.

God's standard is extreme. For him, anger is the same as murder; a lustful thought is the same as adultery. Sin is not just what we do - but anything we think, say, do or even don't do that fails to meet God's perfect standard.

For everyone has sinned;
we all fall short of God's glorious standard.
Romans 3:23

"But I'm a good person," you may be thinking. "I take care of my family. I volunteer in my community. I don't steal or hurt anyone. Surely I should be let into heaven?"

If you want to get into heaven by trying to live a good life, this is how good, Jesus said, you'll need to be:

But you are to be perfect,
even as your Father in heaven is perfect.
Matthew 5:48

The standard for getting into heaven is total perfection in thought and deed. In short, you'll have to be as good as God is. It's impossible for a human being to reach this standard.



3
GOD IS BOTH LOVING AND JUST.
	

Imagine that a desperate man decides to rob a bank. He walks up to the teller, points a gun at her and gruffly demands money.

The frightened teller hands it over.

He shoves the money into a garbage bag and rushes towards the exit. But on the way he trips on the carpet and falls heavily, dropping the gun. The bank security guards overpower him.

When in court, the judge asks the robber, "How do you plead?"

"Guilty," he responds softly. He has no other option. The evidence against him is overwhelming.

"Your honour," the robber continues, "this is my first offence. I didn't hurt anyone. The bank got all the money back. Can you please forget what I've done and let me go?"

Would the judge be just if he let the robber go? No he wouldn't. The judge has to uphold the law and the law requires that a man found guilty of robbery should be punished.

God is even more just than any human judge. He cannot and will not excuse our sin.

...God is love.
1 John 4:8

But I [God] do not excuse the guilty.
Exodus 34:7

This is the dilemma: God is love and he does not want to punish us. But God is also just and he must punish our sin. God solved this dilemma by sending Jesus.

4
JESUS IS GOD AND MAN.
HE WAS PUNISHED FOR OUR SINS.

Jesus is God in a human body. He's not just a good man,
a prophet or a teacher.

In the beginning the Word [Jesus] already existed; the Word was with God and the Word was God...The Word became a human being and, full of grace and truth, lived among us. We saw his glory, the glory which he received as the Father's only son.
John 1:1,14

God loves us but he hates our sin. He yearns to enjoy an intimate relationship with us. But our sin is the wall that separates him from us.

To solve this problem, God took all our sin - sins past, sins present and also all future sins - and placed them on Jesus. Then, he punished Jesus for our sins.

All of us were like sheep that were lost, each of us going his own way. But the Lord made the punishment fall on him [Jesus], the punishment all of us deserved.
Isaiah 53:6 TEV

Jesus was handed over to barbaric men who beat him and humiliated him. He was punched, slapped and spat on. His flesh was lacerated as he was scourged - the whip was tipped with pieces of metal.

As the men laughed at him, a crown of thorns was forced onto his head. Iron spikes were then hammered through his hands and feet - he was nailed to a cross.

Finally, when he had paid for the last sin Jesus said, "Tetelestai." An ancient business word that means: The price has been paid.

Jesus died. But three days later God raised him from the dead.

This means that your sins have already been punished. They just weren't punished in your body.

Jesus died on the cross and rose from the dead to pay the penalty for our sins and to purchase a place in heaven for us. A place he now offers to us as a free gift.

Just as soap exists but can only cleanse our bodies if we use it - the gift exists but it can only benefit us if we accept it.

This gift is received through faith.

5
FAITH IS THE KEY
THAT UNLOCKS HEAVEN'S DOOR.

To log in to my bank account I need to enter a password. I could try many passwords. But only the correct password will work. Saving faith is the only password that unlocks access to heaven.

What is saving faith?

A scientist may know many facts about water, but if she is crawling through the desert dying of thirst, head knowledge will not save her. She needs to drink water. Head knowledge that God exists is not saving faith.

Before going on a journey we might pray to God for protection or before writing an exam we might ask him for help. Turning to God only when we are in need or in a crisis is temporary faith.

Saving faith is not head knowledge that God exists, neither is it temporary faith. True saving faith is trusting in Jesus Christ alone for eternal life.

Then he led them out and asked,
"Sirs, what must I do to be saved?"
They answered,
"Believe in the Lord Jesus and you will be saved—
you and your family."
Acts 16:30-31

Imagine you're out sailing. You are caught in a violent storm. Huge waves come crashing over your small boat. One of them sinks the boat and you end up in the icy water clinging desperately to a piece of wood.

A ship spots you and rushes over. The captain comes to the handrailing and shouts, "Hey! I'm throwing you a life preserver. Grab It! We'll pull you to safety."

In the same way, God sees us drowning in our sin. We are powerless to save ourselves. So he calls out to us, "I've already thrown you a life preserver. His name is Jesus. Let go of the piece of wood. Grab on to him and I will pull you to safety."

We must choose - either keep holding onto a piece of wood (trying to save ourselves) or let go and trust Jesus to save us.

Jesus is the only way to eternal life. He's God's only life preserver. In order to receive the gift of eternal life we must place our faith in
Jesus Christ alone.
	
	
MAKE AN OFFER OF SALVATION
	
Let me summarize what I've shared so far.
Heaven is a free gift that cannot be earned or deserved. We are all sinners and unable to save ourselves. Jesus, who is both God and man, was punished for our sins and by placing our faith in Him, we can receive the gift of eternal life.

Do you want to receive the gift of eternal life?

There's no complicated ritual to follow. The gift is received simply by asking for it. If your answer is yes, I can lead us in a prayer so you can tell God what you've just told me. Please say the following prayer:

Dear Jesus. I am a sinner. I want to receive your free gift of eternal life. I believe that you are the son of God. I believe that you died for my sins. I believe that you rose from the dead. I choose to place my trust in you alone. Thank you Jesus for the free gift of eternal life. Amen.


	
WELCOME TO GOD'S FAMILY
	
Congratulations! Let me be the first to welcome you to the family of God!
	
	
SALVATION HAPPENS INSTANTLY
	
This is what Jesus said about what you've just done:

I tell you the truth, anyone who believes has eternal life.
John 6:47

This means that we receive eternal life the instant we believe. Because you believed, you have received it.
	

SALVATION IS PERMANENT
	
Jesus also said:

I give them eternal life, and they shall never perish; neither shall anyone snatch them out of My hand.
John 10:28
	
This means that nothing, not even your own actions, can cause you to lose eternal life.

The bible also says:

But to all who believed him [Jesus] and accepted him, he gave the right to become children of God. They are reborn—not with a physical birth resulting from human passion or plan, but a birth that comes from God.
John 1:12-13

You are now part of God's family. Nothing you do can change that - once a baby is born, he or she cannot be unborn.

Your past, present and future sins have been forgiven. In God's eyes you are, and always will be, radiantly pure. Just like Jesus.

####
EOT;


	$parts_list = array();
	if (trim($user_message) !== '') {
		$parts_list[] = array("text" => $user_message);
	}
	$message_history[] = array("role" => "user", "parts" => $parts_list);

	$GLOBALS['last_api_usage'] = null;
	$chat_agent_response_list = run_agent_with_memory(
		$chat_agent_system_message,
		$message_history,
		$chat_agent_config['model_id'],
		$chat_agent_config['provider']
	);
	$chat_usage = $GLOBALS['last_api_usage'];
	// This response is always plain text
	$chat_agent_response = $chat_agent_response_list[1];

	// Backstop: trim a trailing double-question if the prompt-level
	// instruction didn't catch it.
	$chat_agent_response = limit_trailing_questions($chat_agent_response);


	// This text will be spoken out loud
	$text_to_speak = test_input($chat_agent_response);

	// Update the chat history
	$message_dict = array("text" => $chat_agent_response);
	$parts_list = array();
	$parts_list[] = $message_dict;
	$message_history[] = array("role" => "model", "parts" => $parts_list);

	// Trim the history to $max_history_pairs, oldest pair first.
	// A "pair" is a user turn immediately followed by its model turn,
	// so this always removes both halves together.
	while (count($message_history) > $max_history_pairs * 2) {
		array_shift($message_history); // oldest user turn
		array_shift($message_history); // its matching model turn
	}

	$_SESSION['message_history'] = $message_history;


	//------------------------
	// Create the output text
	//------------------------
	// This is sent to the main web page via Ajax.

	$check_array = array(
		'user_message' => $user_message,
		'chat_agent_response' => $chat_agent_response);


	// Dev-only: usage/cost breakdown for this request, plus a total.
	// Only meaningful when the model/provider returns a "usage"
	// object with cost info (OpenRouter includes this automatically).
	// The frontend keeps this hidden by default - see the
	// #dev-usage-panel div and DEV_MODE flag in index.php.
	$agent_usages = [
		'chat' => $chat_usage,
	];
	$total_cost = 0;
	$total_prompt_tokens = 0;
	$total_completion_tokens = 0;
	foreach ($agent_usages as $usage) {
		if (is_array($usage)) {
			$total_cost += $usage['cost'] ?? 0;
			$total_prompt_tokens += $usage['prompt_tokens'] ?? 0;
			$total_completion_tokens += $usage['completion_tokens'] ?? 0;
		}
	}
	$debug_usage = [
		'agents' => $agent_usages,
		'total_cost' => $total_cost,
		'total_prompt_tokens' => $total_prompt_tokens,
		'total_completion_tokens' => $total_completion_tokens,
		'history_pairs' => intdiv(count($message_history), 2),
	];


	$response = array('success' => true,
		'speech_lang_code' => $speech_lang_code,
		'speech_voice_name' => $speech_voice_name,
		'speech_rate' => $speech_rate,
		'check_array' => $check_array,
		'text_to_speak' => $text_to_speak,
		'speak_status' => $speak_request,
		'chat_text' => $chat_agent_response,
		'debug_usage' => $debug_usage);

  	echo json_encode($response);


}


// ============================================================
// Helper functions
// (formerly in php/php_utils_revised.php)
// ============================================================


/**
 * Load configuration from a file
 *
 * @param string $file The configuration file path
 * @return array The parsed configuration
 * @throws Exception if the file does not exist
 */
function load_config($file) {
    if (!file_exists($file)) {
        throw new Exception("Configuration file not found: $file");
    }
    return parse_ini_file($file, true);
}


/**
 * Convert the app's Gemini-style message history (list of
 * ["role" => "user"|"model", "parts" => [["text" => "..."]]])
 * into the OpenAI-compatible "messages" array that OpenRouter expects
 * (list of ["role" => "system"|"user"|"assistant", "content" => "..."]).
 * This keeps the rest of main.php - which builds and stores
 * $message_history in the session - unchanged.
 *
 * @param string $system_message The system message
 * @param array $message_history The Gemini-style message history
 * @return array The OpenAI-style messages array
 */
function build_openrouter_messages($system_message, $message_history) {
    $messages = [];

    $messages[] = ["role" => "system", "content" => $system_message];

    foreach ($message_history as $turn) {
        $role = (isset($turn['role']) && $turn['role'] === 'model') ? 'assistant' : ($turn['role'] ?? 'user');

        $text = '';
        if (isset($turn['parts']) && is_array($turn['parts'])) {
            foreach ($turn['parts'] as $part) {
                if (isset($part['text'])) {
                    $text .= $part['text'];
                }
            }
        }

        $messages[] = ["role" => $role, "content" => $text];
    }

    return $messages;
}


/**
 * Make an API call to OpenRouter, with retry support
 *
 * @param string $system_message The system message
 * @param array $message_history The message history (Gemini-style; converted internally)
 * @param string $model_id The OpenRouter model id, e.g. "qwen/qwen3.5-flash-02-23"
 * @param string $provider The OpenRouter provider slug, e.g. "alibaba"
 * @param int $max_retries Number of times to retry the API call on failure
 * @return array|string The API response or an error message
 */
function make_api_call($system_message, $message_history, $model_id, $provider, $max_retries = 3) {
    global $path_to_config_ini;
    global $url;
    global $temperature;
    global $max_tokens;
    global $site_url;
    global $site_title;

    $timestamp = date('Y-m-d H:i:s');
    $file_path = "php-errors.log";

    try {
        $config = load_config($path_to_config_ini);
    } catch (Exception $e) {
        error_log($timestamp . ' ' . $e->getMessage(), 3, $file_path);
        return 'Failed to load configuration.';
    }

    $apiKey = $config['api']['API_KEY'] ?? '';
    if (empty($apiKey) || empty($url)) {
        error_log($timestamp . ' API key or URL not configured properly.', 3, $file_path);
        return 'API key or URL not configured properly.';
    }

    $messages = build_openrouter_messages($system_message, $message_history);

    $data = [
        "model" => $model_id,
        // Pins the request to a specific provider on OpenRouter. Setting
        // allow_fallbacks to false means the call will fail rather than
        // silently route to a different (and possibly pricier) provider.
        "provider" => [
            "order" => [$provider],
            "allow_fallbacks" => false
        ],
        "messages" => $messages,
        "temperature" => $temperature,
        "max_tokens" => $max_tokens,

        "reasoning" => [
            "effort" => "none"
        ],

    ];
    $headers = [
        "Authorization: Bearer {$apiKey}",
        "Content-Type: application/json",
        "HTTP-Referer: {$site_url}",
        "X-Title: {$site_title}"
    ];

    $attempt = 0;
    while ($attempt < $max_retries) {
        $attempt++;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($curl) ? curl_error($curl) : null;

        curl_close($curl);

        if ($curlError) {
            error_log($timestamp . " Attempt $attempt - cURL error: $curlError\n", 3, $file_path);
        } elseif ($httpStatusCode >= 400) {
            error_log($timestamp . " Attempt $attempt - HTTP error: $httpStatusCode - Response: $result\n", 3, $file_path);
        } else {
            $decodedResult = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Stash usage/cost info for this call so the caller can
                // read it back after the fact. Dev/debug use only - see
                // $GLOBALS['last_api_usage'].
                if (isset($decodedResult['usage'])) {
                    $GLOBALS['last_api_usage'] = $decodedResult['usage'];
                    $GLOBALS['last_api_usage']['model_id'] = $model_id;
                }
                return $decodedResult;
            } else {
                error_log($timestamp . " Attempt $attempt - JSON decode error: " . json_last_error_msg() . "\n", 3, $file_path);
            }
        }

        // Optional: sleep between retries to avoid hitting rate limits
        sleep(1);
    }

    return 'api_error';
}


/**
 * Extract text from an OpenRouter (OpenAI-compatible) API response
 *
 * @param array $response The API response
 * @return string The extracted text or error message
 */
function extract_text_from_response($response) {
    if (isset($response["choices"][0]['message']['content'])) {
        return $response["choices"][0]['message']['content'];
    } elseif (isset($response['error'])) {
        $error_code = $response['error']['code'] ?? '';
        $error_message = $response['error']['message'] ?? 'Unknown error';
        return "Error: " . $error_code . "<br>" . $error_message;
    } else {
        return "Sorry. Something went wrong. Please try again.";
    }
}


/**
 * Run agent with memory
 *
 * @param string $system_message The system message
 * @param array $message_history The message history
 * @param string $model_id The OpenRouter model id for this agent
 * @param string $provider The OpenRouter provider slug for this agent
 * @return array The output type and text
 */
function run_agent_with_memory($system_message, $message_history, $model_id, $provider) {
    $response = make_api_call($system_message, $message_history, $model_id, $provider);


	// If the API call failed then try again (two more trys)
	//----------

    if ($response == "api_error") {
        $response = make_api_call($system_message, $message_history, $model_id, $provider);
    }


	if ($response == "api_error") {
        $response = make_api_call($system_message, $message_history, $model_id, $provider);
    }

	//----------



    if ($response != "api_error") {


		// If the API call failed then try again (two more trys)
		//----

        $response_text = extract_text_from_response($response);

        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }


		$response_text = extract_text_from_response($response);

        if ($response_text == "Sorry. Something went wrong. Please try again.") {
            $response = make_api_call($system_message, $message_history, $model_id, $provider);
        }
		//----

    }




    if ($response != "api_error") {
        $response_text = extract_text_from_response($response);
        $output_type = check_output_type($response_text);

        if ($output_type == "is_json_string") {
            $output_text = json_decode($response_text, true);
        } elseif ($output_type == "is_json_object") {
            $response_text = json_encode($response_text);
            $output_text = json_decode($response_text, true);
        } else {
            $output_text = $response_text;
        }

        return [$output_type, $output_text];
    } else {
        return ["is_plain_text", "api_error"];
    }
}


/**
 * Check the output type
 *
 * @param mixed $output The output
 * @return string The type of output
 */
function check_output_type($output) {
    if (is_object($output)) {
        return "is_json_object";
    } elseif (is_string($output)) {
        $decoded = json_decode($output, true);
        if ($decoded !== null) {
            return "is_json_string";
        } else {
            return "is_plain_text";
        }
    }
}

?>
