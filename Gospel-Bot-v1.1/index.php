<?php
session_start();

// ============================================================
// This file used to include php/name_config.php separately.
// Those values are now set directly here as part of the
// codebase consolidation into just index.php and main.php.
// ============================================================
$bot_name = 'Gospel Bot'; 	// Give the bot a name
$user_name = 'Guest';	// Set the user's name
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Tell search engines not to index the site 
    <meta name="robots" content="noindex, nofollow">
	-->
  
    <meta charset="utf-8">
    <title>Gospel Bot - Heaven is a free gift</title>
    <meta name="description" content="An AI powered chatbot that respectfully shares the Good News in 5 steps and leads people to Jesus. Non-denominational. Non-profit. No ads.">
	
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Image -->
    <link rel="shortcut icon" type="image/png" href="assets/cross.png">
	
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Markdown rendering for the bot's replies (see the ajax
         response handler below): marked.js converts markdown -> HTML,
         DOMPurify sanitizes it before it's ever set via innerHTML.
         Loaded blocking, in <head>, so both are guaranteed to exist by
         the time any script further down the page runs. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/12.0.2/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.5/purify.min.js"></script>

    <style>
        /* ============================================================
           Default Theme Palette: Light Mode
           ============================================================ */
        :root {
            --bg: #f5f7fa;
            --bg2: #ffffff;
            --bg3: #eef1f6;
            --border: rgba(0, 0, 0, 0.08);
            --border2: rgba(0, 0, 0, 0.15);
            --text: #1a1d24;
            --text2: #5c6275;
            --text3: #8a91a5;
            --accent: #2563eb;
            --accent-dim: rgba(37, 99, 235, 0.08);
            --accent-glow: rgba(37, 99, 235, 0.18);
            --pill-bg: rgba(255, 255, 255, 0.92);
            --overlay-bg: rgba(245, 247, 250, 0.97);
        }

        /* ============================================================
           Dark Theme Palette Override: Near-black Navy (adopted from
           the Noise for Sleep app: two lighter "surface" layers for
           cards/panels rather than one flat background color
           everywhere, soft off-white text with two dimmer secondary
           shades, and a blue accent).
           ============================================================ */
        body.dark-mode {
        	--bg: #0d0f14; --bg2: #141720; --bg3: #1c2030;
        	--border: rgba(255,255,255,0.08); --border2: rgba(255,255,255,0.14);
        	--text: #e8eaf0; --text2: #8b90a4; --text3: #555b70;
        	--accent: #5b8cff; --accent-dim: rgba(91,140,255,0.12); --accent-glow: rgba(91,140,255,0.25);
        	--pill-bg: rgba(20, 23, 32, 0.92);
        	--overlay-bg: rgba(13, 15, 20, 0.97);
        }

        /* ============================================================
           Custom replacements for the w3.css utility classes that
           were previously used (w3.css has been dropped entirely).
           ============================================================ */
        .w3-small { font-size: 12px; }
        .w3-padding-left { padding-left: 8px; }
        .w3-padding-right { padding-right: 8px; }
        .w3-padding-bottom { padding-bottom: 8px; }
        .w3-padding { padding: 8px; }
        .w3-text-white { color: var(--text); }
        .w3-text-blue { color: var(--accent); }
        .w3-text-teal { color: var(--accent); }
        .w3-center { text-align: center; }
        .w3-round { border-radius: 4px; }
        .w3-animate-opacity { animation: w3-fade-in 0.5s; }
        @keyframes w3-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ============================================================
           App styles (formerly css/e-bot.css)
           ============================================================ */
        body {
        	background-color: var(--bg);
        	font-family: Helvetica, Arial, sans-serif;
        	font-size: 18px;
        	color: var(--text);
        	padding-top: 60px; /* leave room for the fixed language pill */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        main {
        	margin-bottom: 200px;
        	color: var(--text);
        	padding: 10px;
        }

        h4 {
        	font-size: 20px;
        }

        #main-image h2 {
        	color: var(--text);
        	letter-spacing: -0.02em;
        	font-size: clamp(2.4rem, 9vw, 3.2rem);
        	margin: 0 0 2px 0;
        }

        .hero-subtitle {
        	color: var(--text2);
        	font-weight: normal;
        	font-size: 18px;
        	margin: 0;
        }

        #about-link {
        	display: inline-block;
        	color: var(--text3);
        	font-size: 13px;
        	margin-top: 6px;
        	text-decoration: none;
        }

        #about-link:hover,
        #about-link:focus-visible {
        	color: var(--accent);
        	outline: none;
        }

        a {
        	text-decoration: none;
        }

        .responsive {
        	 width: 100%; /*Makes media scalable as the viewport size changes*/
        	 height: auto;
        	 max-width: 200px; 
        } 

        .hero-image {
        	width: 100%;
        	height: auto;
        	max-width: 280px;
        	margin: 0 auto 4px auto;
        	display: block;
        }

        .container {
        	width: 100%;
        	max-width: 600px;
        	margin: 0 auto;
        	padding: 0 20px;
        }

        .sticky-bar {
        	position: fixed;
        	bottom: 0;
        	left: 0;
        	width: 100%;
            box-sizing: border-box; /* Fix content-box overflow clipping on mobile */
        	background-color: var(--bg);
        	color: var(--text);
        	padding: 10px 0; /* Align top/bottom, horizontal spacing is handled by input-group */
        	text-align: center;
        	border-top: 1px solid transparent;
            transition: background-color 0.3s ease, border-top-color 0.2s ease;
        }

        /* Thin top border on the sticky area while the Settings panel
           is open, so the bar visually separates itself from the chat
           above it. Disappears again once the panel is closed. */
        .sticky-bar.settings-open {
        	border-top-color: var(--border2);
        }

        .input-group {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 20px; /* Matches default container margins on desktop */
            box-sizing: border-box;
        }

        .sticky-bar input[type="text"] {
        	box-sizing: border-box;
        	height: 46px;
        	padding: 0 15px;
        	border-radius: 5px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	flex: 1;
        	min-width: 0;
        	font-size: 18px;
        }

        .sticky-bar input[type="submit"] {
        	box-sizing: border-box;
        	height: 46px;
        	background-color: var(--accent);
        	color: #fff;
        	border: 1px solid transparent;
        	padding: 0 20px;
        	border-radius: 5px;
        	cursor: pointer;
        	font-size: 16px;
        	margin-left: 10px;
        	flex-shrink: 0;
        }

        .message-container {
        	font-size: 16px;
        	margin-bottom: 10px;
        	padding: 5px 20px;
        	background-color: var(--bg2);
        	color: #36454F;
        	border: 1px solid var(--border);
        	border-radius: 5px;
        	line-height: 1.8;
        	letter-spacing: 0.02em;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        body.dark-mode .message-container {
        	color: var(--text);
        }

        /* --- Suggested prompt chips, shown above the input until the
               first message is sent --- */

        .suggested-prompts {
        	display: flex;
        	flex-direction: column;
        	gap: 4px;
        	width: 100%;
        	max-width: 600px;
        	margin: 0 auto 10px auto;
        	padding: 0 20px;
        	box-sizing: border-box;
        }

        .suggested-prompt-btn {
        	box-sizing: border-box;
        	background: none;
        	border: none;
        	padding: 2px 0;
        	color: var(--text2);
        	font-size: 15px;
        	text-align: left;
        	white-space: nowrap;
        	overflow: hidden;
        	text-overflow: ellipsis;
        	cursor: pointer;
        }

        .suggested-prompt-btn:hover {
        	color: var(--accent);
        }

        .set-color1 {
        	color: var(--accent);
        }

        .set-color2 {
        	color: var(--text2);
        }

        #chat-buttons {
          display: flex;
          justify-content: center;
          align-items: center;
          margin-top: 10px;
        }

        #chat-buttons button {
          margin-right: 20px;
          padding: 0px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 15px;
          background-color: var(--bg3);
          color: var(--text);
          border: none;
        }

        #chat-buttons input[type="file"] {
          display: none;
        }

        #chat-buttons label {
          display: inline-block;
          padding: 0px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 15px;
          background-color: var(--bg3);
          color: var(--text);
          border: none;
        }
        	
        #chat-buttons input[type="file"] + label {
        	margin-right: 10px;
        }

        #chat-buttons input[type="file"] + label:before {
          	content: "Load a saved chat";
        }

        .sticky-image {
        	position: fixed;
        	top: 0;
        	left: 0;
        }
        	
        .beta-text {
        	font-size: 15px;
        }


        .lighter-black {
        	color: var(--text2); 
        }

        .space-letters {
        	letter-spacing: .03em;
        }
        	
        .wrapper {
          display: flex;
          justify-content: center;
          width: 100%;
          max-width: 600px;
          margin: 3px auto 0 auto;
          padding: 0 20px;
          box-sizing: border-box;
        }

        .form-elements {
          display: flex;
          flex-direction: row;
          justify-content: center;
          align-items: center;
          gap: 10px; /* Space between the radio buttons and dropdown */
          width: 100%;
        }

        .radio-group {
          display: flex;
          flex-direction: row;
          justify-content: flex-start;
          align-items: center;
          gap: 10px; /* Space between the radio buttons */
        }

        .radio-option {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 8px;
          /*border: 1px solid #ccc;
          border-radius: 4px;*/
          cursor: pointer;
        }

        .radio-option input[type="radio"] {
          margin-right: 0;
          accent-color: var(--accent);
        }

        .dropdown-option {
          display: flex;
          align-items: center;
          flex: 1 1 auto;
          min-width: 0; /* lets the select actually shrink instead of forcing the row wide - this was the root cause of the dropdown ballooning to full width alone on mobile */
        }

        .styled-dropdown {
          width: 100%;
          padding: 8px 30px 8px 10px;
          border: 1px solid var(--border2);
          border-radius: 4px;
          cursor: pointer;
          background-color: var(--bg3);
          color: var(--text);
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='%238b90a4' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 10px center;
          background-size: 10px 7px;
        }

        .styled-dropdown:hover,
        .styled-dropdown:focus,
        .styled-dropdown:focus-visible {
          border-color: var(--accent);
          outline: none;
        }

        /* Settings panel rows (Voice, Speed): label on the left, a
           *single* controls group on the right that holds the
           dropdown/slider plus its companion button/value-label. Using
           one wrapper for "everything after the label" means that if
           the row runs out of space, the whole controls group wraps
           together onto its own line - rather than the label, the
           dropdown, and the button each independently deciding where
           to wrap and landing on three separate lines. */
        .settings-row {
          display: flex;
          flex-wrap: wrap;
          align-items: center;
          gap: 10px;
        }

        .settings-row-label {
          display: flex;
          align-items: center;
          flex: 0 0 auto;
          min-width: 60px;
          cursor: pointer;
        }

        .settings-row-controls {
          display: flex;
          align-items: center;
          gap: 8px;
          flex: 1 1 auto;
          min-width: 0;
        }

        .title-color {
        	color: var(--accent);
        }

        .tag-color {
        	background-color: var(--bg3);
        }

        .hide {
            display: none;
        }

        /* ============================================================
           Voice picker row (Settings panel): dropdown of available
           TTS voices for English, plus a small preview button so the
           user can hear a voice before committing to it.
           ============================================================ */
        #preview-voice-btn {
        	display: flex;
        	align-items: center;
        	justify-content: center;
        	width: 34px;
        	height: 34px;
        	flex-shrink: 0;
        	border-radius: 4px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	cursor: pointer;
        }

        #preview-voice-btn:hover {
        	border-color: var(--accent);
        	color: var(--accent);
        }

        #preview-voice-btn:disabled {
        	opacity: 0.4;
        	cursor: not-allowed;
        }

        /* ============================================================
           Speed picker row (Settings panel): a range slider so users
           can slow speech down for practice or speed it back up.
           ============================================================ */
        #speed-select {
        	flex: 1;
        	min-width: 100px;
        	accent-color: var(--accent);
        	cursor: pointer;
        }

        #speed-value-label {
        	min-width: 34px;
        	text-align: right;
        	font-size: 13px;
        	color: var(--text);
        	font-variant-numeric: tabular-nums;
        }

        .display-block {
        	display: block;
        }

        /* Initially hide the panel and set up the transition */
        #panel {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.5s ease-out;
          width: 100%;
        }

        /* Class to show the panel */
        .panel-open {
          max-height: 1000px; /* Set a high value for max-height */
        }

        /* Card styling lives on this inner wrapper rather than on #panel
           itself: #panel is the thing whose max-height animates for the
           open/close transition, and padding placed directly on an
           element with max-height:0 still renders (padding isn't part
           of what max-height constrains), leaving a visible sliver even
           while "closed". Keeping padding/background one level in avoids
           that. */
        #panel-inner {
          display: flex;
          flex-direction: column;
          gap: 10px;
          margin-top: 10px;
          padding: 14px 16px;
          background-color: var(--bg2);
          border: 1px solid var(--border);
          border-radius: 10px;
        }

        #accordion {
          margin-right: 20px;
          padding: 8px 14px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 18px;
          background-color: transparent;
          color: var(--text);
          border: none;
          transition: background-color 0.15s ease, color 0.15s ease;
        }

        #accordion:hover,
        #accordion:focus,
        #accordion:focus-visible {
          background-color: var(--bg3);
          outline: none;
          box-shadow: none;
        }

        /* Settings button stays visibly "active" while its panel is
           open, so the gear affordance actually signals open/closed
           state instead of looking the same either way (no blue
           tint - just a neutral background, matching the button's
           hover state). */
        #accordion.settings-active {
          background-color: var(--bg3);
        }

        #start-voicechat-btn {
          margin-right: 10px;
          padding: 8px 14px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 18px;
          background-color: transparent;
          color: var(--text);
          border: none;
          transition: background-color 0.15s ease;
        }

        #start-voicechat-btn:hover,
        #start-voicechat-btn:focus,
        #start-voicechat-btn:focus-visible {
          background-color: var(--bg3);
          outline: none;
          box-shadow: none;
        }

        #audioIndicator {
          bottom: 20px;
          right: 20px;
        }

        .bar {
          width: 4px;
          height: 15px;
          background-color: var(--accent);
          display: inline-block;
          margin-right: 2px;
          animation: soundWave 1s infinite alternate;
        }

        @keyframes soundWave {
          0% { height: 5px; }
          50% { height: 20px; }
          100% { height: 5px; }
        }

        #audioIndicator1 {
          bottom: 20px;
          right: 20px;
        }

        .bar1 {
          width: 4px;
          height: 15px;
          background-color: var(--text2);
          display: inline-block;
          margin-right: 2px;
          
        }

        .clickable {
          cursor: pointer;
        }

        /* ============================================================
           Rendered markdown inside a bot reply (see the ajax response
           handler below). This wraps arbitrary block content (headings,
           lists, code, etc.) from marked.js, so it's a <div> rather
           than a <p> - these rules just rein in default browser
           spacing/sizing so it reads like a chat bubble rather than a
           rendered document.
           ============================================================ */
	   .bot-markdown {
    	font-size: inherit;
    	line-height: 1.7;
    	margin-top: 1.1em;
    	margin-bottom: 1.1em;
        }

        .bot-markdown > *:first-child {
        	margin-top: 0;
        }

        .bot-markdown > *:last-child {
        	margin-bottom: 0;
        }

        .bot-markdown p,
        .bot-markdown ul,
        .bot-markdown ol,
        .bot-markdown pre,
        .bot-markdown blockquote {
        	margin: 0 0 18px 0;
        }

        .bot-markdown ul,
        .bot-markdown ol {
        	padding-left: 22px;
        }

        .bot-markdown li {
        	margin-bottom: 2px;
        }

        .bot-markdown h1,
        .bot-markdown h2,
        .bot-markdown h3,
        .bot-markdown h4 {
        	margin: 10px 0 6px 0;
        	line-height: 1.3;
        }

        .bot-markdown h1 { font-size: 1.25em; }
        .bot-markdown h2 { font-size: 1.15em; }
        .bot-markdown h3,
        .bot-markdown h4 { font-size: 1.05em; }

        .bot-markdown code {
        	font-family: ui-monospace, Menlo, Consolas, monospace;
        	font-size: 0.9em;
        	background-color: var(--bg3);
        	border-radius: 4px;
        	padding: 1px 5px;
        }

        .bot-markdown pre {
        	background-color: var(--bg3);
        	border-radius: 8px;
        	padding: 10px 12px;
        	overflow-x: auto;
        }

        .bot-markdown pre code {
        	background-color: transparent;
        	padding: 0;
        }

        .bot-markdown blockquote {
        	border-left: 3px solid var(--border2);
        	padding-left: 10px;
        	color: var(--text2);
        }

        .bot-markdown a {
        	color: var(--accent);
        }

        .bot-markdown table {
        	border-collapse: collapse;
        	margin-bottom: 8px;
        	max-width: 100%;
        	display: block;
        	overflow-x: auto;
        }

        .bot-markdown th,
        .bot-markdown td {
        	border: 1px solid var(--border2);
        	padding: 4px 8px;
        	text-align: left;
        }

        /* ============================================================
           Per-message speaker icon: pulses while its audio is playing,
           doubling as both the "audio is playing" cue and the
           "click here to mute" affordance (replaces the old separate
           mute button in the sticky bar).

           Opacity-only on purpose: this icon has display:block (see
           .display-block below) so it drops onto its own line under
           the chat text, which makes its box as wide as the paragraph
           while the glyph itself sits left-aligned inside that box.
           transform: scale() scales around the box's center, not the
           glyph, so it visibly drifts sideways as it grows/shrinks.
           Opacity doesn't touch layout/geometry at all, so it can't
           cause that drift regardless of box width.
           ============================================================ */
        .speaker-icon.speaking {
        	animation: speaker-pulse 1s ease-in-out infinite;
        }

        @keyframes speaker-pulse {
        	0%, 100% { opacity: 1; }
        	50% { opacity: 0.35; }
        }


        /* When the page loads
        show audioIndicator1 and
        hide #audioIndicator
        */

        #audioIndicator1 {
            display: inline-block;
            vertical-align: middle;
        }

        #audioIndicator {
        	display: none;
        	vertical-align: middle;
        }

        /* 
        --------------
        MEDIA QUERIES
        --------------
        */

        /*Cellphone Screens in portrait*/	
        @media only screen and (max-width: 480px) and (orientation: portrait){

        	.container {
        	        padding: 0;
        	}
        		
        	.hide-on-phone {
        		display: none;
        	}

            .input-group {
                padding: 0 12px; /* Add a dedicated structural layout margin from phone borders */
            }

            .wrapper {
                padding: 0 12px; /* Match .input-group's phone padding above */
            }

            /* The toggle row (Auto Speak / Correction / Translation)
               fits 2 across on most phones with a 3rd wrapping alone,
               which reads as lopsided. Stack them into a clean single
               column instead - also makes each toggle a taller,
               easier tap target. */
            #line1 {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }

            /* Larger chat bubble text on mobile for readability. */
            .message-container {
                font-size: 18px;
            }
        } /*Close media query*/
    </style>
	
</head>

<body>

    <div class="container w3-animate-opacity">
        <div id="main-image" class="w3-center w3-round w3-padding">

			<img src="assets/ebot2.png" alt="Gospelbot" class="hero-image" loading="eager" fetchpriority="high">

			<h2 class="space-letters"><b>Gospel Bot</b></h2>

            <h4 class="space-letters hero-subtitle">Sharing the Good News in 5 Steps</h4>

            <a href="about.php" id="about-link">About</a>

        </div>
        <main id="chat" class="texts">
            <!-- Add more message containers here -->
            <!-- The div for the spinner gets added and deleted here. -->
        </main>
        <div class="sticky-bar">

            <div id="suggested-prompts" class="suggested-prompts">
                <button type="button" class="suggested-prompt-btn" onclick="submit_text_to_php('Hello')">Heaven is a free gift.<br>Say hello to learn more...</button>
            </div>

            <form id="myForm" action="main.php" method="post">
                <div class="input-group">
                    <input id="user-input" type="text" name="my_message" placeholder="Type or talk..." autofocus>
                    <input type="hidden" name="robotblock">
                    <input id="submit-btn" type="submit" value="Send">
                </div>
                <div class="w3-padding space-letters">
                    <button type="button" class="" id="start-voicechat-btn" onclick="toggle_voicechat(lang_code)" aria-label="Start Voicechat" title="Start Voicechat"><i class="fa fa-microphone" style="font-size:25px;"></i></button>
                    <button type="button" class="" id="accordion" aria-label="Settings" aria-expanded="false"><i class="fa fa-gear" style="font-size:25px;"></i></button>
                    <div id="audioIndicator">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <div id="audioIndicator1">
                        <div class="bar1"></div>
                        <div class="bar1"></div>
                        <div class="bar1"></div>
                    </div>
                </div>
                <div class="wrapper">
                    <div class="form-elements">
                        <div id="panel">
                          <div id="panel-inner">
                            <div id="line1" class="radio-group">
                                <label class="radio-option">
                                    <input id="speakid" class="w3-padding" type="radio" name="speak1" value="speak" onclick="toggleRadio(this)">
                                    Auto Speak
                                </label>
                            </div>
                            <div id="line3" class="radio-group" style="margin-top: 5px; margin-bottom: 5px;">
                                <label class="radio-option">
                                    <input id="theme-toggle" type="checkbox" onclick="toggleTheme(this)">
                                    Dark Mode
                                </label>
                            </div>
                            <div id="voice-picker-row" class="settings-row">
                                <label class="settings-row-label" for="voice-select">
                                    <span>Voice</span>
                                </label>
                                <div class="settings-row-controls">
                                    <div id="voice-select-wrap" class="dropdown-option">
                                        <select class="styled-dropdown" id="voice-select" aria-label="Choose the bot's voice" onchange="onVoiceSelected(this)">
                                            <option value="">Default</option>
                                        </select>
                                    </div>
                                    <button type="button" id="preview-voice-btn" aria-label="Preview this voice" title="Preview this voice" onclick="previewSelectedVoice()">
                                        <i class="fa fa-play" style="font-size:12px;"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="speed-picker-row" class="settings-row">
                                <label class="settings-row-label" for="speed-select">
                                    <span>Speed</span>
                                </label>
                                <div class="settings-row-controls">
                                    <!-- value/min/max here are an internal 0-100 "position"
                                         scale, NOT the speaking rate itself. See
                                         speedPositionToRate()/speedRateToPosition() in the
                                         script below: a plain linear 0.5x-2x mapping puts
                                         1.0x (the default/"normal" speed) at 33% along the
                                         track instead of visual center, since 1.0 isn't the
                                         midpoint of 0.5-2. This piecewise mapping keeps the
                                         full 0.5x-2x range while guaranteeing 1.0x always
                                         renders at exactly 50%. -->
                                    <input type="range" id="speed-select" min="0" max="100" step="0.1" value="50" aria-label="Speaking speed" aria-valuetext="1.0x" oninput="onSpeedChanged(this)">
                                    <span id="speed-value-label" aria-hidden="true">1.0x</span>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- The page gets scrolled up to this id. -->
    <div id="e-bot"></div>
    <!-- Onload a click is simulated on this to scroll the page to id="bottom-bar" -->
    <a href="#e-bot" id="scroll-page-up"></a>
    <a href="#test100" id="scroll-to-last-message"></a>
    <a href="#chatbot" id="scroll-to-bot-message"></a>

    <!--
    Dev-only panel showing per-request token counts and cost, as
    reported by OpenRouter. Hidden by default - flip DEV_MODE to true
    in the script below (or just delete this block) for production.
    -->
    <div id="dev-usage-panel" style="display:none; position:fixed; bottom:0; left:0; z-index:9999; max-width:340px; max-height:40vh; overflow-y:auto; background:rgba(0,0,0,0.85); color:#0f0; font-family:monospace; font-size:12px; padding:8px; border-top-right-radius:6px;"></div>

</body>
</html>


<script>
/* ============================================================
   Utility functions
   (formerly js/utils.js)
   ============================================================ */

// This function creates the three dot spinner.
// Calling this function starts the spinner.
function spinner() {
    // Select the element where the spinner will be displayed
    const spinnerElement = document.getElementById("spinner");
    
    // Define an array of dots
    const dots = ["", ".", "..", "..."];
    
    // Initialize the dot counter
    let dotIndex = 0;// Set the color and size of the spinner
	
    spinnerElement.style.color = "var(--text)";
    spinnerElement.style.fontSize = "25px";
	
	
    
    // Start the spinner animation
    setInterval(() => {
        // Update the text content of the spinner element with the current dot
        // This adds the >... symbol
        // spinnerElement.textContent = `>${dots[dotIndex]}`;
        
        // This does not have the >... symbol
        spinnerElement.textContent = `${dots[dotIndex]}`;
    
        // Increment the dot counter
        dotIndex = (dotIndex + 1) % dots.length;
    }, 500);
}


// We create the div containing the spinner.
// We append the div to the chat.
// This displays the spinner.
function create_spinner_div() {
    // Create a new div element
    const spinnerElement = document.createElement("div");
    
    // Set the id attribute of the div element to "spinner"
    spinnerElement.setAttribute("id", "spinner");
    
    var chat = document.getElementById("chat");
    
    // Append the div to the chat
    chat.appendChild(spinnerElement);
    
    // Start the spinner
    spinner();
}


// This function deletes the div containing the spinner.
// This causes the spinner to disappear.
function delete_spinner_div() {
    // Get the div element you want to delete
    const elementToDelete = document.getElementById("spinner");
    
    // Get the parent node of the div element
    const parentElement = elementToDelete.parentNode;
    
    // Remove the div element from its parent node
    parentElement.removeChild(elementToDelete);
}


// This functions takes a list of text (paragraphs).
// If the paragraph does not have p tags then it adds them.
function wrapInPTags(paragraphs) {
    let result = '';

    for (let i = 0; i < paragraphs.length; i++) {
        const paragraph = paragraphs[i];

        if (paragraph.includes('<p>')) {
            result += paragraph;
        } else {
            result += '<p>' + paragraph + '</p>';
        }
    }

    return result;
}


// This function formats the text into paragraphs.
function formatResponse(response) {
    // Split the response into lines
    const lines = response.split("\n");

    // Combine the lines into paragraphs
    const paragraphs = [];
    let currentParagraph = "";

    for (const line of lines) {
        if (line.trim()) {  // Check if the line is non-empty
            currentParagraph += line.trim() + " ";
        } else if (currentParagraph) {  // Check if the current paragraph is non-empty
            paragraphs.push(currentParagraph.trim());
            currentParagraph = "";
        }
    }

    // Append the last paragraph
    if (currentParagraph) {
        paragraphs.push(currentParagraph.trim());
    }

    // Some text thats returned has \n character but no <p> tags.
    // Other text has <p> tags that we can use when displaying the text on the page.
    // Here we check each list item (paragraph). If it doesn't have <p> tags then add them.
    // This is also important when we save and then reload the chat history.
    // If you change this make sure that the saving and reloading also works well.
    formattedResponse = wrapInPTags(paragraphs);
    
    // Add HTML tags to separate paragraphs
    // const formattedResponse = paragraphs.map(p => `<p>${p}</p>`).join("");
    
    return formattedResponse;
}


// Function to create a new message container
function createMessageContainer(message) {
    var messageContainer = document.createElement("div");
    messageContainer.classList.add("message-container");
    messageContainer.classList.add("w3-animate-opacity");
    
    // Add an id attribute. This will help to scroll to
    // the bot message. This gets detelted after the page
    // is scrolled to the bot message.
    messageContainer.setAttribute("id", "chatbot");

    var messageText = document.createElement("span"); // p

    // This if statement sets the coour of the name that gets displayed
    if (message.sender == bot_name) {
        messageText.innerHTML = "<span class='set-color1'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
    } else {
        messageText.innerHTML = "<span class='set-color2'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
    }

    messageContainer.appendChild(messageText);

    return messageContainer;
}


// Function to add a new message to the chat
function addMessageToChat(message) {
    var chat = document.getElementById("chat");
    var messageContainer = createMessageContainer(message);
    
    chat.appendChild(messageContainer);
    
    // Scroll the page up by cicking on a div at the bottom of the page.
    simulateClick('scroll-page-up');
}

// Function to remove html tags from a string
function removeHtmlTags(str) {
    return str.replace(/(<([^>]+)>)/gi, "");
}

// Function to mute the cahtbot
// when it is speaking.
//
// NOTE: speechSynthesis.cancel() does not reliably fire the
// utterance's 'onend' event in every browser. The mic restart
// used to live only inside onend, which meant muting mid-speech
// could permanently leave voicechat mode with the mic off and
// no visual indication. To fix this, muting now explicitly
// restarts the mic itself (if it's supposed to be on), instead
// of depending on onend to do it.
function quiet_please() {
    speechSynthesis.cancel();
    
    // Stop whichever per-message icon is currently pulsing.
    clearSpeakingIcon();
    
    // Hide the moving audio bars and show the three dots bars
    hide('audioIndicator');
    show('audioIndicator1');
    
    // If the mic is supposed to be listening (voicechat mode is on),
    // make sure it actually is - don't rely on onend firing.
    if (micShouldBeOn) {
        console.log('Muted mid-speech - restarting mic directly...');
        restart_recognition_if_needed();
    }
}


// Stops the currently-animating speaker icon (if any) from pulsing,
// and resets its tooltip back to "Click to play". Centralized here
// since speak(), quiet_please(), and the utterance's onend/onerror
// handlers all need to agree on which icon (if any) is "speaking".
function clearSpeakingIcon() {
    if (window.currentSpeakingIcon) {
        window.currentSpeakingIcon.classList.remove('speaking');
        window.currentSpeakingIcon.setAttribute('title', 'Click to play');
        window.currentSpeakingIcon = null;
    }
}


// Function that converts text to speech
function speak(text, speech_lang_code, speech_voice_name, speech_rate = 1, iconElement = null) {

	if (!speechSynthesisSupported) {
		console.log('Speech synthesis is not supported in this browser - skipping TTS.');
		return;
	}

    // Only one utterance plays at a time, so only one icon should ever
    // show the "speaking" animation - clear whichever one was
    // animating before starting this one.
    clearSpeakingIcon();

    // Flush any utterance that's currently speaking or still queued
    // before starting this one. Without this, calling speak() again
    // while a previous utterance hasn't finished (e.g. clicking "play
    // translation" right after the English reply auto-speaks) queues
    // the new utterance instead of replacing it - and some browsers
    // (Chrome in particular) can bleed the voice/lang from one queued
    // utterance into another when that happens. This is what caused
    // the Thai voice to occasionally speak the English text.
    speechSynthesis.cancel();

    // Create a new instance of SpeechSynthesisUtterance
    const utterance = new SpeechSynthesisUtterance();
    
    // Set the text that you want to speak
    utterance.text = text;
	
	// If speech recognition is currently running, stop it while the
	// bot talks (otherwise the mic will hear - and respond to - the
	// bot's own voice). We do NOT touch micShouldBeOn here: that flag
	// tracks the user's intent (voicechat mode on/off), independent of
	// this temporary pause. handleEnd() and quiet_please() both check
	// micShouldBeOn to decide whether to restart the mic.
	  if (window.recognition) {
		  
		  console.log('Stopping recognition while bot speaks...')
	  
		  window.recognition.removeEventListener('end', handleEnd);
		  window.recognition.stop();
	  
	  }
	
	
	/////////////
	
	 // Ensure the language is set
    utterance.lang = speech_lang_code;

    // Get the list of available voices. Prefer the cached list (populated
    // by onvoiceschanged) since getVoices() can return an empty array on
    // first call before the browser has finished loading its voice list.
    const voices = (cachedVoices && cachedVoices.length) ? cachedVoices : window.speechSynthesis.getVoices();

    // If the user has picked a voice from the Settings panel (see
    // populateVoiceSelect()/onVoiceSelected() above), it takes
    // priority over the server-provided default - but only for
    // English speech. Translated text is spoken with whatever
    // browser-default voice fits that language, so a user's English
    // voice preference shouldn't hijack, say, Thai playback.
    const userPreferredVoiceName = (speech_lang_code && speech_lang_code.toLowerCase().startsWith('en'))
        ? localStorage.getItem('gospelbot_preferred_voice_name')
        : null;
    const requestedVoiceName = userPreferredVoiceName || speech_voice_name;

    // Find the voice with the requested name (e.g. "Serena", "Jorge" -
    // these are hardcoded per-language in main.php, or picked by the
    // user from the Settings dropdown). This is a fragile,
    // OS/browser-specific exact-name match, so it will often come up
    // empty on non-Apple platforms - in that case we work down the
    // fallback chain below rather than failing.
    let selectedVoice = requestedVoiceName ? voices.find(voice => voice.name === requestedVoiceName) : null;

    // Fallback chain, English only: tried in order after the requested
    // voice (e.g. "Serena") comes up empty on this device/browser.
    // Each entry is checked by name AND lang, since voice names aren't
    // guaranteed unique across languages/platforms.
    const FALLBACK_VOICE_CHAIN = [
        { name: 'Arthur', lang: 'en-GB' },
        { name: 'Daniel', lang: 'en-GB' },
        { name: 'Samantha', lang: 'en-US' }
    ];
    if (!selectedVoice && speech_lang_code && speech_lang_code.toLowerCase().startsWith('en')) {
        for (const candidate of FALLBACK_VOICE_CHAIN) {
            const match = voices.find(voice =>
                voice.name === candidate.name &&
                voice.lang && voice.lang.toLowerCase() === candidate.lang.toLowerCase()
            );
            if (match) {
                selectedVoice = match;
                break;
            }
        }
    }

    if (selectedVoice) {
        utterance.voice = selectedVoice;
    } else if (requestedVoiceName) {
        console.log('Requested voice "' + requestedVoiceName + '" and the fallback chain (Arthur/Daniel/Samantha) were not found on this device/browser - using the browser\'s default voice instead.');
    } else {
        console.log('No specific voice requested for lang "' + speech_lang_code + '" - using the browser\'s default voice for it.');
    }
	
	
	/////////////
	
	
	// Set the speaking rate. The Settings panel's Speed slider (see
	// getPreferredSpeechRate() above) always wins here - unlike the
	// voice name override, speed isn't language-specific, so this
	// applies to any text this function is asked to speak.
    utterance.rate = getPreferredSpeechRate();
    

    // When the chatbot starts speaking display the sound bar animation
    hide('audioIndicator1');
    show('audioIndicator');
    
    // Pulse this message's speaker icon (if one was passed in) and
    // remember it so quiet_please()/onend/onerror can find it again.
    window.currentSpeakingIcon = iconElement;
    if (iconElement) {
        iconElement.classList.add('speaking');
        iconElement.setAttribute('title', 'Playing - click to mute');
    }
    
    utterance.onend = function() {
        // When the chatbot stops speaking hide the sound bar animation
        hide('audioIndicator');
        show('audioIndicator1');
        clearSpeakingIcon();
		
		// Only when the speech synthesis ends, start the mic again -
		// but only if voicechat mode is still supposed to be on.
		// If we don't gate this on micShouldBeOn, the mic could start
		// listening again even after the user muted or stopped voicechat.
		restart_recognition_if_needed();
    };
    
    utterance.onerror = function(event) {
        // 'interrupted' / 'canceled' fire whenever speechSynthesis.cancel()
        // preempts this utterance - which happens on every normal replay-
        // a-different-message click, every mute, and every new speak()
        // call (which now flushes the queue first), since speak() and
        // quiet_please() both call cancel(). That's expected, not a
        // failure, so it's logged at a lower level than a real error.
        if (event.error === 'interrupted' || event.error === 'canceled') {
            console.log('Speech synthesis interrupted (expected - a new message started playing or audio was muted).');
        } else {
            console.log('Speech synthesis error:', event.error);
        }
        
        // Don't leave the "speaking" animation stuck on if TTS fails.
        hide('audioIndicator');
        show('audioIndicator1');
        clearSpeakingIcon();
        
        // Make sure the mic doesn't stay stuck off just because TTS failed.
        restart_recognition_if_needed();
    };
    
    // Speak the text. This is deliberately deferred a tick rather than
    // called synchronously: Chrome (and some other browsers) have a
    // known bug where calling speak() immediately after cancel() can
    // silently drop the utterance entirely - no sound, no onerror,
    // nothing - because cancel() hasn't actually finished tearing down
    // the previous utterance yet even though it returns right away.
    // A short delay gives that teardown time to complete first.
    setTimeout(function() {
        speechSynthesis.speak(utterance);
    }, 50);
    
}


// Restarts speech recognition if (and only if) voicechat mode is
// supposed to be on. Centralizing this logic means quiet_please(),
// utterance.onend, and utterance.onerror all agree on whether the
// mic should be listening, instead of each having their own copy
// of this logic (which is how the old mute bug happened).
function restart_recognition_if_needed() {
    if (!micShouldBeOn) {
        return;
    }
    
    if (!window.recognition) {
        // Recognition object was torn down (e.g. by stop_recognition()) -
        // nothing to restart.
        return;
    }
    
    console.log('Restarting recognition...');
    
    window.recognition.removeEventListener('end', handleEnd);
    window.recognition.addEventListener('end', handleEnd);
    
    try {
        window.recognition.start();
    } catch (err) {
        // start() throws if recognition is already running (e.g. it
        // never actually stopped) - safe to ignore.
        console.log('Recognition already running or could not be restarted:', err);
    }
}


// Function to remove emojis from text
function removeEmojis(text) {
    return text.replace(/[\u{1F600}-\u{1F64F}]/gu, '')  // Emoticons
               .replace(/[\u{1F300}-\u{1F5FF}]/gu, '')  // Miscellaneous Symbols and Pictographs
               .replace(/[\u{1F680}-\u{1F6FF}]/gu, '')  // Transport and Map Symbols
               .replace(/[\u{1F700}-\u{1F77F}]/gu, '')  // Alchemical Symbols
               .replace(/[\u{1F780}-\u{1F7FF}]/gu, '')  // Geometric Shapes Extended
               .replace(/[\u{1F800}-\u{1F8FF}]/gu, '')  // Supplemental Arrows-C
               .replace(/[\u{1F900}-\u{1F9FF}]/gu, '')  // Supplemental Symbols and Pictographs
               .replace(/[\u{1FA00}-\u{1FA6F}]/gu, '')  // Chess Symbols
               .replace(/[\u{1FA70}-\u{1FAFF}]/gu, '')  // Symbols and Pictographs Extended-A
               .replace(/[\u{2600}-\u{26FF}]/gu, '')    // Miscellaneous Symbols
               .replace(/[\u{2700}-\u{27BF}]/gu, '')    // Dingbats
               .replace(/[\u{FE00}-\u{FE0F}]/gu, '')    // Variation Selectors
               .replace(/[\u{1F1E6}-\u{1F1FF}]/gu, '')  // Flags
               .replace(/[\u{1F900}-\u{1F9FF}]/gu, '')  // Supplemental Symbols and Pictographs
               .replace(/[\u{1FA70}-\u{1FAFF}]/gu, ''); // Symbols and Pictographs Extended-A
}


function hide(elementId) {
    document.getElementById(elementId).style.display = "none";
}


function show(elementId) {
    document.getElementById(elementId).style.display = "inline-block";
}


function removeITags(html) {
    // Use a regular expression to remove <i> tags and their content
    return html.replace(/<i[^>]*>.*?<\/i>/gi, '');
}


function speakText(pElement) {
    playOrToggleMuteMessageAudio(pElement, speech_lang_code, speech_voice_name);
}

function playOrToggleMuteMessageAudio(pElement, langCode, voiceName) {
    var icon = pElement.querySelector('.speaker-icon');

    // If this exact message is the one currently playing, clicking it
    // again mutes/stops playback rather than restarting it - the
    // pulsing icon is the visual cue that a click here means "mute".
    if (icon && icon.classList.contains('speaking')) {
        quiet_please();
        return;
    }

    // .textContent strips every tag inside pElement - including
    // whatever marked.js produced (<strong>, <code>, <ul>, etc.), not
    // just the <i> speaker icon - and decodes HTML entities natively.
    // The icon's glyph itself is a CSS ::before pseudo-element, not a
    // text node, so it doesn't show up here either.
    var processedText = pElement.textContent;

    speak(processedText, langCode, voiceName, speech_rate, icon);
}




// Simulates a click.
function simulateClick(tabID) {
    // Simulate a click.
    document.getElementById(tabID).click();
}


// Adding and removing the checked attribute ensures
// that the radio button remains checked or unchecked
// after the form is submitted. Otherwise it will return
// to its default status each time a chat message is sent.
function toggleRadio(radio) {
    // Check the previous state stored in a custom property
    if (radio.wasChecked) {
        // If it was previously checked, uncheck it
        radio.checked = false;
        radio.removeAttribute('checked');
        radio.wasChecked = false;  // Update the state to reflect that it's no longer checked
    } else {
        // If it was not checked, check it
        radio.checked = true;
        radio.setAttribute('checked', 'checked');
        radio.wasChecked = true;  // Update the state to reflect that it's now checked
    }
}


function checkRadioButton(radioName, radioID) {
    var radio = document.querySelector(`input[name="${radioName}"]`);
    if (radio) {
        radio.checked = true;
    }
    // This makes sure the button does not uncheck
    // when the form is submitted. It stays checked.
    document.getElementById(radioID).setAttribute('checked', 'checked');
}


function uncheckRadioButton(radioName, radioID) {
    var radio = document.querySelector(`input[name="${radioName}"]`);
    if (radio) {
        radio.checked = false;
    }
    document.getElementById(radioID).removeAttribute('checked');
}


/* ============================================================
   Config
   (formerly js/config.js)
   Speech recognition language for the mic input.
   ============================================================ */
lang_code = "en-GB";
</script>

<script>
// Cache of available TTS voices. getVoices() frequently returns an
// empty list on first page load until the browser's 'voiceschanged'
// event fires, so speak() should read from this cache rather than
// calling getVoices() fresh every time.
let cachedVoices = [];

// The key voice picking is stored under in localStorage, so the
// user's choice survives page reloads (sessionStorage isn't used
// here on purpose - the chat history is wiped per-tab via
// session_unset()/session_destroy() below, but a voice preference
// is a device/browser setting, not a conversation setting, so it
// should persist).
const VOICE_PREF_KEY = 'gospelbot_preferred_voice_name';

function cacheVoices() {
    const voices = speechSynthesis.getVoices();
    if (voices && voices.length) {
        cachedVoices = voices;
        console.log('Cached ' + voices.length + ' voices.');
        populateVoiceSelect();
    }
}

// Fills the #voice-select dropdown with the English voices this
// browser/OS actually has available, so the list only ever shows
// voices that will really work here (rather than a fixed list that
// might not exist on the visitor's device/browser - see the
// Chrome-vs-Safari voice mismatch this replaces).
function populateVoiceSelect() {
    const select = document.getElementById('voice-select');
    if (!select) return;

    // Practice language is English, so only offer English voices -
    // matches window.speech_lang_code ("en-GB" by default in main.php).
    const englishVoices = cachedVoices
        .filter(v => v.lang && v.lang.toLowerCase().startsWith('en'))
        .sort((a, b) => a.name.localeCompare(b.name));

    const previouslySelected = select.value || localStorage.getItem(VOICE_PREF_KEY) || '';

    // Rebuild the option list (keep the "Default" option first).
    select.innerHTML = '<option value="">Default</option>';
    englishVoices.forEach(voice => {
        const option = document.createElement('option');
        option.value = voice.name;
        option.textContent = voice.name + ' (' + voice.lang + ')';
        select.appendChild(option);
    });

    // Restore the saved/previous choice if it's still available on
    // this browser; otherwise fall back to "Default" quietly.
    if (previouslySelected && englishVoices.some(v => v.name === previouslySelected)) {
        select.value = previouslySelected;
    } else {
        select.value = '';
    }
}

// Called when the user picks a voice from the dropdown.
function onVoiceSelected(selectElement) {
    const voiceName = selectElement.value;
    if (voiceName) {
        localStorage.setItem(VOICE_PREF_KEY, voiceName);
    } else {
        localStorage.removeItem(VOICE_PREF_KEY);
    }
}

// Lets the user hear a short sample in the currently selected voice
// before committing to it, without having to send a chat message.
function previewSelectedVoice() {
    const select = document.getElementById('voice-select');
    if (!select) return;

    const voiceName = select.value || null; // null = browser default
    const langCode = (window.speech_lang_code) || 'en-GB';

    // Note: the 4th arg (rate) below is intentionally ignored by
    // speak() now - it always sources the rate from the Speed
    // slider itself via getPreferredSpeechRate(), so this preview
    // automatically plays at whatever speed is currently set.
    speak('Hi! This is a preview of my voice.', langCode, voiceName, 1, null);
}

// Same idea as VOICE_PREF_KEY above: speaking speed is a
// device/browser-level preference, so it's saved separately from
// the per-session chat state and persists across reloads.
const SPEED_PREF_KEY = 'gospelbot_preferred_speech_rate';

// The Speed slider's own value scale is 0-100 ("position" along the
// track) rather than the speaking rate directly. A plain linear
// mapping of a 0.5x-2x rate onto the track puts 1.0x (the default/
// "normal" speed) at 33% instead of visual center, since 1.0 isn't
// the midpoint of 0.5-2. These two conversions keep the full 0.5x-2x
// range while guaranteeing 1.0x always renders at exactly 50%: the
// left half of the track covers 0.5x-1.0x, the right half covers
// 1.0x-2.0x, each with its own (different) slope.
function speedPositionToRate(position) {
    return position <= 50
        ? 0.5 + (position / 50) * 0.5
        : 1.0 + ((position - 50) / 50) * 1.0;
}

function speedRateToPosition(rate) {
    return rate <= 1.0
        ? (rate - 0.5) * 100
        : 50 + (rate - 1.0) * 50;
}

// Restores the saved speed (or the 1.0x default) into the slider +
// its label. Called once on page load - the slider itself doesn't
// depend on voices being cached, so it doesn't need to wait for
// cacheVoices()/onvoiceschanged like the voice dropdown does.
function initSpeedSlider() {
    const slider = document.getElementById('speed-select');
    const label = document.getElementById('speed-value-label');
    if (!slider || !label) return;

    const saved = parseFloat(localStorage.getItem(SPEED_PREF_KEY));
    const rate = (!isNaN(saved) && saved >= 0.5 && saved <= 2) ? saved : 1;

    slider.value = speedRateToPosition(rate);
    slider.setAttribute('aria-valuetext', rate.toFixed(1) + 'x');
    label.textContent = rate.toFixed(1) + 'x';
}

// Called continuously while the user drags the slider (oninput, not
// onchange) so the "1.2x" label updates live as they move it. Rounds
// to the nearest 0.1x (matching the slider's old 0.1 step, before it
// was switched to an internal 0-100 position scale) so the stored/
// applied rate always matches what the label displays.
function onSpeedChanged(sliderElement) {
    const rawRate = speedPositionToRate(parseFloat(sliderElement.value));
    const rate = Math.round(rawRate * 10) / 10;
    sliderElement.setAttribute('aria-valuetext', rate.toFixed(1) + 'x');
    document.getElementById('speed-value-label').textContent = rate.toFixed(1) + 'x';
    localStorage.setItem(SPEED_PREF_KEY, rate);
}

// Returns the user's saved speaking speed, or 1 (normal speed) if
// they haven't set one yet.
function getPreferredSpeechRate() {
    const saved = parseFloat(localStorage.getItem(SPEED_PREF_KEY));
    return (!isNaN(saved) && saved >= 0.5 && saved <= 2) ? saved : 1;
}

initSpeedSlider();

// Some browsers (e.g. Chrome) have voices ready immediately; others
// only populate them once 'voiceschanged' fires. Try both.
cacheVoices();
speechSynthesis.onvoiceschanged = cacheVoices;
</script>

<script>
    // Opens/closes the settings panel with the smooth max-height
    // transition, keeping the gear button's active state and
    // aria-expanded in sync. Pulled out into named functions (rather
    // than only living inside the accordion's click handler) so the
    // click-outside-to-close listener below can call closeSettingsPanel()
    // directly without needing to fake a click on the accordion button.
    function isSettingsPanelOpen() {
        var panel = document.getElementById('panel');
        return !!panel.style.maxHeight;
    }

    function openSettingsPanel() {
        var panel = document.getElementById('panel');
        var accordionBtn = document.getElementById('accordion');
        var stickyBar = document.querySelector('.sticky-bar');
        panel.style.maxHeight = panel.scrollHeight + "px";
        accordionBtn.classList.add('settings-active');
        accordionBtn.setAttribute('aria-expanded', 'true');
        if (stickyBar) {
            stickyBar.classList.add('settings-open');
        }
    }

    function closeSettingsPanel() {
        var panel = document.getElementById('panel');
        var accordionBtn = document.getElementById('accordion');
        var stickyBar = document.querySelector('.sticky-bar');
        panel.style.maxHeight = null;
        accordionBtn.classList.remove('settings-active');
        accordionBtn.setAttribute('aria-expanded', 'false');
        if (stickyBar) {
            stickyBar.classList.remove('settings-open');
        }
    }

    // Event listener that prevents the form from submitting when
    // the "Settings" button is clicked.
    document.getElementById('accordion').addEventListener('click', function(event) {
        event.preventDefault();
        // Add your settings toggle code here
        console.log('Settings button clicked');
    });

    // JavaScript to toggle the visibility of the panel with a smooth transition
    document.getElementById('accordion').addEventListener('click', function() {
        if (isSettingsPanelOpen()) {
            closeSettingsPanel();
        } else {
            openSettingsPanel();
        }
    });

    // Click-outside-to-close: while the settings panel is open, a
    // click/tap anywhere outside the sticky bottom bar (which holds
    // the accordion button and the panel itself) closes it. Scoped to
    // .sticky-bar rather than just #panel so that clicking the message
    // input, the mic button, etc. also closes settings - not just
    // clicks on the chat messages above.
    document.addEventListener('click', function(event) {
        if (!isSettingsPanelOpen()) return;

        var stickyBar = document.querySelector('.sticky-bar');
        if (stickyBar && !stickyBar.contains(event.target)) {
            closeSettingsPanel();
        }
    });
	
	

	
	// *** ON LOAD ***
    // Comment or uncomment this line to check (select) the radio
    // when the page loads.
    window.onload = function() {
        checkRadioButton('speak1', 'speakid');
    };

    // Sync the Dark Mode checkbox as soon as the DOM is parsed rather
    // than waiting for window.onload (which only fires once every
    // resource on the page - including the hero image - has finished
    // loading). The <body> tag already renders in dark mode by default,
    // so on a slow connection the settings panel could be opened while
    // the checkbox still hadn't been ticked yet.
    document.addEventListener('DOMContentLoaded', function() {
        // Load saved theme preference on page load. Light mode is the
        // default (the <body> tag has no dark-mode class by default),
        // so dark mode is only applied when explicitly saved.
        const savedTheme = localStorage.getItem('theme');
        const themeToggle = document.getElementById('theme-toggle');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            if (themeToggle) {
                themeToggle.checked = true;
            }
        } else {
            document.body.classList.remove('dark-mode');
            if (themeToggle) {
                themeToggle.checked = false;
            }
        }
    });

    // Toggle theme function (activates/deactivates Dark Mode)
    function toggleTheme(checkbox) {
        if (checkbox.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    }
</script>

<script>
    // These names are set in PHP at the top of this file.
    const bot_name = "<?php echo $bot_name; ?>";
    const user_name = "<?php echo $user_name; ?>";
</script>

<script>
	// *** DEV_MODE ***
    // PHP Ajax Code
    ///////////////////

    // Dev-only: set to true locally to show the token/cost panel
    // (#dev-usage-panel). Leave false in production.
    var DEV_MODE = false; // true/false

    function renderDevUsagePanel(debugUsage) {
        var panel = document.getElementById('dev-usage-panel');
        if (!panel || !DEV_MODE || !debugUsage) {
            return;
        }

        function fmtAgent(label, usage) {
            if (!usage) {
                return label + ': (not called)';
            }
            var cost = (usage.cost !== undefined) ? '$' + Number(usage.cost).toFixed(6) : 'n/a';
            var model = usage.model_id ? ' [' + usage.model_id + ']' : '';
            return label + model + ': ' + (usage.prompt_tokens ?? '?') + ' in / '
                + (usage.completion_tokens ?? '?') + ' out - ' + cost;
        }

        var lines = [
            fmtAgent('Chat', debugUsage.agents.chat),
            '---',
            'History pairs: ' + debugUsage.history_pairs,
            'Total tokens: ' + debugUsage.total_prompt_tokens + ' in / ' + debugUsage.total_completion_tokens + ' out',
            'Total cost this request: $' + Number(debugUsage.total_cost).toFixed(6)
        ];

        panel.innerHTML = lines.join('<br>');
        panel.style.display = 'block';
    }

    var form = document.getElementById('myForm');

    form.onsubmit = function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Get the form data.
        var formData = new FormData(form);
        var $my_message = formData.get("my_message");

        // Reset the visible form right away so the UI feels responsive.
        form.reset();

        // This will prevent the form from submitting if there's no text.
        if ($my_message == "") {
            return;
        }

        // Prevent a second message being sent before the first
        // response has come back. Without this, the button/input
        // stay enabled the whole time the request is in flight, so
        // clicking Send (or pressing Enter) again - or a fast voice
        // submission - can fire a second request on top of the
        // first. Re-enabled in xhr.onload/xhr.onerror below,
        // whichever fires.
        var submitBtn = document.getElementById('submit-btn');
        var userInput = document.getElementById('user-input');
        if (submitBtn) submitBtn.disabled = true;
        if (userInput) userInput.disabled = true;

        // Hide the suggested-prompt chips once a real message goes
        // out - they're a first-message nudge, not a permanent
        // fixture, so they get out of the way after that.
        var suggestedPrompts = document.getElementById("suggested-prompts");
        if (suggestedPrompts) {
            suggestedPrompts.style.display = "none";
        }

        // Format the input into paragraphs.
        $my_message = formatResponse($my_message);

        var input_message = {
            sender: user_name,
            text: $my_message
        };

        console.log(input_message.text);

        // Add a user message to the chat
        addMessageToChat(input_message);

        // Show the spinner while waiting for the response
        create_spinner_div();

        // Scroll the page up by clicking on a div at the bottom of the page.
        simulateClick('scroll-page-up');

        // Delete the id from the message container.
        // It will get added again when the message container is created.
        var element = document.getElementById("chatbot");
        element.removeAttribute("id");

        // Send an AJAX request to the server to process the form data
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.onload = function() {

            if (xhr.status === 200) {

                var response;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (e) {
                    // Server returned 200 but the body wasn't valid
                    // JSON (e.g. a stray PHP warning/notice printed
                    // before the JSON payload). Without this guard,
                    // the throw would abort the handler right here,
                    // leaving the spinner on screen and the form
                    // disabled forever - the same failure mode as
                    // the non-200 case handled below.
                    console.log("Failed to parse server response:", e);
                    delete_spinner_div();
                    alert('Sorry, something went wrong processing your message. Please try again.');
                    if (submitBtn) submitBtn.disabled = false;
                    if (userInput) userInput.disabled = false;
                    return;
                }

                console.log("===API Output===");

				var check_text = response.check_array;

				console.log('==Check text==');
				console.log(check_text);

				// Dev-only token/cost panel (no-op unless DEV_MODE is true).
				renderDevUsagePanel(response.debug_usage);

				// Make these variables global by attaching
				// them to the window object.
				window.speech_lang_code = response.speech_lang_code;
				window.speech_voice_name = response.speech_voice_name;
				window.speech_rate = response.speech_rate;

                var text_to_speak = response.text_to_speak;
                var speak_status = response.speak_status;

				let chatAgentResponse = response.chat_text;

                // The model often replies in markdown (bold, lists,
                // scripture-style blockquotes, etc.) - render it to HTML
                // rather than dumping the raw asterisks/hashes into the
                // chat. marked.parse() converts markdown -> HTML;
                // DOMPurify.sanitize() strips anything dangerous before
                // it's ever set via innerHTML in createMessageContainer().
                chatAgentResponse = DOMPurify.sanitize(marked.parse(removeEmojis(chatAgentResponse)));

                console.log(speak_status);

                var chatText;

                // For Deaf Accessibility.
                // Deaf people won't know that the audio is on
                // and the chatbot is speaking.
                // marked's output can include block elements (<ul>, <pre>,
                // <h1-4>, etc.), which aren't valid inside a <p> - a
                // browser would silently close the <p> early and break
                // the "click anywhere in the reply to hear it" behaviour.
                // A <div> holds arbitrary block content correctly instead.
                if (speak_status == 'selected') {
                    chatText = `<div class="clickable bot-markdown" onclick="speakText(this)">${chatAgentResponse}<i class="fa fa-volume-up w3-text-teal display-block speaker-icon" style="font-size:18px" title="Click to play"></i></div>`;
                } else {
                    chatText = `<div class="clickable bot-markdown" onclick="speakText(this)">${chatAgentResponse}<i class="fa fa-volume-off w3-text-teal display-block speaker-icon" style="font-size:18px" title="Click to play"></i></div>`;
                }

                console.log(chatText);

                var input_message = {
                    sender: bot_name,
                    text: chatText
                };

                // *** Strip the markdown-rendered HTML back down to plain
                // text, then speak *** //
                ////////////////////////////////////////////
                // Speak the same rendered content that's on screen, minus
                // the markup - reading raw markdown syntax aloud
                // (asterisks, backticks, "hash hash" for headings) sounds
                // broken. A detached element's .textContent strips all
                // tags and decodes entities correctly in one step; it's
                // safe to use innerHTML here since chatAgentResponse
                // already went through DOMPurify.
                var speechSource = document.createElement('div');
                speechSource.innerHTML = chatAgentResponse;
                let cleaned_text = speechSource.textContent;

                // Delete the div containing the spinner
                delete_spinner_div();

                // Add Gospelbot's message to the chat
                addMessageToChat(input_message);

                if (speak_status == 'selected') {
                    // The message container still has its temporary
                    // #chatbot id at this point (createMessageContainer
                    // sets it, and it gets removed a few lines below),
                    // so this is the newly-added message's own icon -
                    // not some other message's.
                    var chatbotElement = document.getElementById('chatbot');
                    var newIcon = chatbotElement ? chatbotElement.querySelector('.speaker-icon') : null;
                    speak(cleaned_text, speech_lang_code, speech_voice_name, speech_rate, newIcon);
                }

                // Scroll the page up by clicking on a div at the bottom of the page.
                simulateClick('scroll-to-bot-message');

                // Delete the id from the message container.
                // It will get added again when the message container is created.
                var element = document.getElementById("chatbot");

                element.removeAttribute("id");

                // Only put the cursor into the input field
                // if the user is not using a cellphone.
                // If the cursor is in the input field on a phone then the keyboard
                // gets displayed. This affects the page scrolling to the bot message.
                var screenWidth = window.screen.width;

                // Assuming a threshold of 768 pixels as a cutoff for mobile devices
                var isMobile = screenWidth <= 768;
                if (isMobile) {
                    console.log("User is using a cellphone");
                } else {
                    console.log("User is not using a cellphone");
                    // Put the cursor in the form input field
                    const inputField = document.getElementById("user-input");
                    inputField.focus();
                }
            } else {
                // Server responded, but with a non-200 status (e.g. a
                // PHP fatal error, or a 500/502/504 from a proxy/gateway
                // timeout while waiting on the AI API). xhr.onerror does
                // NOT fire for this case - it only fires for network-
                // level failures - so without this branch the spinner
                // was left on screen forever with no feedback to the
                // user.
                console.log("Request failed with status: " + xhr.status);
                delete_spinner_div();
                alert('Sorry, something went wrong processing your message. Please try again.');
            }

            // Re-enable the form now that the response (success or
            // otherwise) has been handled, so the user can send
            // their next message.
            if (submitBtn) submitBtn.disabled = false;
            if (userInput) userInput.disabled = false;
        };
        xhr.onerror = function() {
            // Network-level failure (e.g. connection dropped). Make
            // sure the form doesn't stay stuck disabled forever.
            delete_spinner_div();
            if (submitBtn) submitBtn.disabled = false;
            if (userInput) userInput.disabled = false;
            alert('Sorry, something went wrong sending your message. Please try again.');
        };
        xhr.send(formData);
    };
</script>


<script>

// ============================================================
// Voicechat (Speech-to-Text) state & feature detection
// ============================================================

// Tracks whether the mic is SUPPOSED to be listening right now
// (i.e. whether voicechat mode is turned on). This is tracked
// independently of the SpeechRecognition 'end' event and of TTS
// playback state, so that muting or a stray 'end' event can't
// silently leave the mic stuck on or off - see quiet_please()
// and restart_recognition_if_needed() above.
let micShouldBeOn = false;

// Feature detection, done once up front. Some browsers (Firefox,
// most iOS Safari) don't implement SpeechRecognition at all - the
// old code just silently did nothing when clicked on those browsers.
window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const speechRecognitionSupported = !!window.SpeechRecognition;
const speechSynthesisSupported = !!window.speechSynthesis;

document.addEventListener('DOMContentLoaded', () => {
    const voicechatBtn = document.getElementById('start-voicechat-btn');
    if (voicechatBtn && !speechRecognitionSupported) {
        voicechatBtn.disabled = true;
        voicechatBtn.setAttribute('aria-label', 'Voicechat not supported in this browser');
        voicechatBtn.title = 'Speech recognition is not supported in this browser. Try Google Chrome on desktop or Android.';
        voicechatBtn.style.opacity = '0.5';
        voicechatBtn.style.cursor = 'not-allowed';
    }
});


// Event listener function.
// When the 'end' event fires, this restarts the mic - but only if
// voicechat mode is still supposed to be on. This is how "always
// listening" is simulated, since the API doesn't have a true
// "always on" mode.
function handleEnd() {
    console.log('Recognition ended.');
    if (micShouldBeOn) {
        console.log('Event listener restarting mic...');
        try {
            window.recognition.start();
        } catch (err) {
            console.log('Could not restart recognition:', err);
        }
    }
}


// Handles recognition errors so the user isn't left staring at
// "Listening..." forever with no idea what went wrong.
function handleRecognitionError(event) {
    console.log('Recognition error:', event.error);

    // Not a real problem - the mic just hasn't heard anything yet.
    // Let handleEnd() (which fires right after) restart it as normal.
    if (event.error === 'no-speech') {
        return;
    }

    let message;
    switch (event.error) {
        case 'not-allowed':
        case 'service-not-allowed':
            message = 'Microphone access was denied. Please allow microphone access in your browser and try again.';
            break;
        case 'audio-capture':
            message = 'No microphone was found. Please check your microphone and try again.';
            break;
        case 'network':
            message = 'A network error interrupted voice recognition. Please try again.';
            break;
        default:
            message = 'Voice recognition ran into a problem (' + event.error + '). Please try again.';
    }

    // Stop trying to auto-restart the mic after a real error, and
    // reset the UI so the user can see something went wrong.
    stop_recognition();
    alert(message);
}


function initialize_recognition(lang_code) {

    const recognition = new SpeechRecognition();

    //recognition.continuous = true;

    // *** Comment out this line for better performance on Android. ***
    // When this line is commented out there's no intermediate voice detections,
    // however, the bot works much better on Android.
    //recognition.interimResults = true;

    // Set the language you want
    recognition.lang = lang_code; //'ja-JP'; // or 'th-TH' for Thai // en-US

    console.log('Detection lang:');
    console.log(lang_code);

    // Make the recognition object available globally
    window.recognition = recognition;

    console.log('recognition initialized');

    window.recognition.addEventListener('end', handleEnd);
    window.recognition.addEventListener('error', handleRecognitionError);

    window.recognition.addEventListener("result", (e) => {

        let text = Array.from(e.results)
            .map((result) => result[0])
            .map((result) => result.transcript)
            .join("");

        if (e.results[0].isFinal) {

            // Format the input into paragraphs. This
            // adds paragrah html to the user's chat.
            // It's main use is where the bot's long response needs
            // to be formatted into separate paragraphs.
            text = formatResponse(text);

            // Use the form to submit the text to php for processing
            submit_text_to_php(text);
        }
    });

    window.recognition.start();

    // Select the button by ID
    const button = document.getElementById("start-voicechat-btn");

    // Show the mic as active/listening: swap to the "slash" icon,
    // add an orange border, and update the accessible label.
    if (button) {
        button.style.border = "2px solid orange";
        button.style.borderRadius = "8px";
        button.setAttribute('aria-label', 'Stop Voicechat');
        button.setAttribute('title', 'Stop Voicechat');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-microphone');
            icon.classList.add('fa-microphone-slash');
        }
    }

}


// Stops voicechat mode entirely (as opposed to the temporary stop
// that happens while the bot is speaking). Tears down the
// recognition object so a future Start click can't stack a second,
// leaked recognizer on top of a stale one.
function stop_recognition() {
    micShouldBeOn = false;

    if (window.recognition) {
        window.recognition.removeEventListener('end', handleEnd);
        window.recognition.removeEventListener('error', handleRecognitionError);
        try {
            window.recognition.stop();
        } catch (err) {
            console.log('Recognition already stopped:', err);
        }
        window.recognition = null;
    }

    const button = document.getElementById("start-voicechat-btn");
    if (button) {
        button.style.border = "";
        button.setAttribute('aria-label', 'Start Voicechat');
        button.setAttribute('title', 'Start Voicechat');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-microphone-slash');
            icon.classList.add('fa-microphone');
        }
    }
}


// Submits generated text
function submit_text_to_php(my_text) {
    // Select the input element by its id
    const inputElement = document.getElementById('user-input');

    // Set the value attribute
    inputElement.setAttribute('value', my_text);

    // Simulate a click on the form submit button
    // This will send the form to the php code for processing.
    simulateClick('submit-btn');

    // Clear the value that was set
    inputElement.setAttribute('value', "");
}


// Source: Speech Recognition App Using Vanilla JavaScript
// https://www.youtube.com/watch?v=-k-PgvbktX4
//
// Toggles voicechat mode on/off. Replaces the old start-only
// button - previously there was no way to turn the mic off short
// of muting (which only affects TTS) or leaving the page. Also
// guards against the old leak where clicking "Start" again while
// already running would spin up a second overlapping recognizer.
function toggle_voicechat(lang_code) {

    if (!speechRecognitionSupported) {
        alert('Sorry, voice recognition is not supported in this browser. Please try Google Chrome.');
        return;
    }

    // Already running - this click means "stop".
    if (window.recognition) {
        stop_recognition();
        return;
    }

    micShouldBeOn = true;
    initialize_recognition(lang_code);
}
</script>

<?php
// This is important.
// If this is not done then the session variables will still
// be available even after the tab is closed. By doing this the
// session variables get deleted when the tab is closed.
// You can print out the message history to confirm that the
// session variable has been deleted: print_r($_SESSION['message_history']);

// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>