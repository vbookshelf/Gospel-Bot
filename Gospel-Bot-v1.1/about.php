<?php
// ============================================================
// about.php
// Deliberately self-contained: its own copy of the palette and
// base styles rather than anything shared with index.php, so it
// has no dependency on that file and can't be broken by future
// changes there (or vice versa). If you re-theme index.php later,
// update the :root / .dark-mode values below to match.
//
// Theme (light/dark) IS shared with index.php, via the same
// localStorage 'theme' key that index.php's toggle writes to.
// See the inline script in <head> below.
// ============================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
    <title>About Gospel Bot</title>
    <meta name="description" content="An AI powered chatbot that respectfully shares the Good News in 5 steps and leads people to Jesus. Non-denominational. Non-profit. No ads.">

    <link rel="shortcut icon" type="image/png" href="assets/ebot3.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Apply whatever theme index.php last saved, before this page
         paints, so it never flashes the "wrong" mode and always
         matches index.php. index.php's toggle writes 'dark' or
         'light' to localStorage under the 'theme' key; light is the
         default when nothing has been saved yet (same default as
         index.php), so a first-time visitor sees light on both
         pages regardless of their OS-level color scheme. This runs
         as early as possible (top of <head>, before the stylesheet
         below) and targets <html> rather than <body>, since <body>
         doesn't exist yet at this point in parsing. -->
    <script>
        (function () {
            try {
                if (localStorage.getItem('theme') === 'dark') {
                    document.documentElement.classList.add('dark-mode');
                }
            } catch (e) {
                // localStorage unavailable (e.g. blocked) - fall back
                // to the light default, same as index.php would.
            }
        })();
    </script>

    <style>
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
        }

        /* Dark palette is applied via a class (added by the inline
           script above, or by toggleTheme() on index.php in a
           previous visit) rather than @media (prefers-color-scheme),
           so this page's mode always tracks index.php's saved
           choice instead of the OS setting. Placed on <html> since
           that's what the early script above sets. */
        html.dark-mode {
            --bg: #0d0f14; --bg2: #141720; --bg3: #1c2030;
            --border: rgba(255,255,255,0.08); --border2: rgba(255,255,255,0.14);
            --text: #e8eaf0; --text2: #8b90a4; --text3: #555b70;
            --accent: #5b8cff; --accent-dim: rgba(91,140,255,0.12); --accent-glow: rgba(91,140,255,0.25);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: Helvetica, Arial, sans-serif;
            font-size: 18px;
            margin: 0;
            padding: 50px 20px 80px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .hero {
            text-align: center;
            margin-bottom: 34px;
        }

        .hero-image {
            width: 100%;
            height: auto;
            max-width: 200px;
            margin: 0 auto 4px auto;
            display: block;
        }

        h1 {
            letter-spacing: -0.02em;
            font-size: clamp(2rem, 8vw, 2.6rem);
            margin: 0 0 4px 0;
        }

        .hero-subtitle {
            color: var(--text2);
            font-weight: normal;
            font-size: 17px;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            margin: 36px 0 12px 0;
            letter-spacing: -0.01em;
        }

        p {
            line-height: 1.7;
            color: var(--text);
            margin: 0 0 14px 0;
        }

        ul {
            margin: 0 0 14px 0;
            padding-left: 22px;
            line-height: 1.7;
        }

        li {
            margin-bottom: 6px;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        .card {
            background-color: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px 20px;
        }

        .privacy-card {
            background-color: var(--bg2);
            border: 1px solid var(--border2);
            border-left: 3px solid var(--accent);
            border-radius: 10px;
            padding: 18px 20px;
            margin-top: 10px;
        }

        .privacy-card p:last-child {
            margin-bottom: 0;
        }

        .back-link-wrap {
            text-align: center;
            margin-top: 44px;
        }

        .back-link {
            display: inline-block;
            background-color: var(--accent);
            color: #fff;
            text-decoration: none;
            padding: 12px 26px;
            border-radius: 8px;
            font-size: 16px;
        }

        .back-link:hover {
            opacity: 0.92;
        }

        .top-back-link {
            display: inline-block;
            color: var(--text2);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 26px;
        }

        .top-back-link:hover {
            color: var(--accent);
        }

        footer {
            text-align: center;
            color: var(--text3);
            font-size: 13px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="index.php" class="top-back-link"><- Back</a>

    <div class="hero">
        <img src="assets/ebot2.png" alt="Gospelbot" class="hero-image" loading="eager">
        <h1><b>About Gospel Bot</b></h1>
        <p class="hero-subtitle">Sharing the Good News in 5 Steps</p>
    </div>

    <div class="card">
        <p>Gospel Bot is a free AI powered chatbot that shares the good news in five simple steps. It follows the Evangelism Explosion (EE) gospel presentation format.</p>
        <p>This is an experimental concept aimed at testing the viability of using AI to share the gospel and provide support to evangelism students.</p>
		<p>Gospel Bot's sole purpose is to share the gospel. It's not designed to engage in pastoral counseling or to take part in spiritual discussions.</p>
    </div>

	
	<h2>Privacy & Safety</h2>
	<div class="privacy-card">
	  <ul>
	    <li>Your chat history is deleted as soon as you close the tab. Gospel Bot does not retain a long-term memory of your messages.</li>
	    <li>Older messages in your current chat are automatically trimmed over time to keep performance fast.</li>
	    <li>There are no ads, analytics, or user tracking built into Gospel Bot.</li>
	    <li>Gospel Bot sends your inputs to a third-party AI model provider that may retain prompt data. Please do not share sensitive or private information.</li>
	  </ul>
	</div>
	
	<h2>Helpful Resources</h2>
	<div class="privacy-card">
	  <ul>
	    <li>An online tutorial to help you learn to share your faith<br><a href="https://goshareyourfaith.com/" target="_blank" rel="noopener">GoShareYourFaith.com</a>
		</li>
	    <li>The Good News of Jesus Christ in 5 Simple Steps<br><a href="https://fivejc.com/" target="_blank" rel="noopener">FiveJC.com</a>
		</li>
		<li>How to share the gospel in one minute<br><a href="https://github.com/vbookshelf/One-Minute-Gospel" target="_blank" rel="noopener">One-Minute-Gospel</a>
		</li>
	    
	  </ul>
	</div>

    <h2>About this project</h2>
    <p>Gospel Bot is an experimental prototype.<br>
		The open-source code is available on <a href="https://github.com/vbookshelf/Gospel-Bot" target="_blank" rel="noopener">GitHub</a>.
		</p>


</div>

</body>
</html>
