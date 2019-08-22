<?
/*
--------------------------------------------------------------------------------
Live Link / Release: 22nd August 2019
--------------------------------------------------------------------------------
Author:		orbitJUICE
Web:		orbitjuice.com
Twitter:	@orbitjuice
Requires:	PHP v5.x or higher

Live Link uses Twitter's "Cards" feature to embed your Mixer stream into a go-live tweet,
allowing people to watch your stream via Twitter.

Installation:
--------------------------------------------------------------------------------
Adjust the variables below as necessary and save as a .php file on your webserver.

More information:
--------------------------------------------------------------------------------
Twitter Cards:
https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/abouts-cards.html

Mixer API documentation:
https://dev.mixer.com/guides/core/introduction

Mixer API 1.0 REST documentation:
https://dev.mixer.com/rest/index.html

Mixer API /channels endpoint documentation:
https://dev.mixer.com/rest/index.html#channels

SECTION ONE:
--------------------------------------------------------------------------------
Change the variables as per the following table:

Variable		Purpose
--------------------------------------------------------------------------------
$mixer			Your lowercase Mixer username (e.g. username).
$twitter		Your lowercase Twitter username (e.g. @username).
*/
$mixer = "username";
$twitter = "@username";
/*
These are sending raw HTTP headers instructing the users browser not to cache the web page.
*/
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/*

SECTION TWO: Calling the Mixer API
--------------------------------------------------------------------------------
This next section is calling Mixer's API, retrieving your channel's information, and declaring them as variables.

The variables are as follows:

Variable		Purpose
--------------------------------------------------------------------------------
$url			The link to the API endpoint (Using the link to the API endpoint and the $mixer username variable).
$content		The raw JSON data from the API.
$json			The decoded JSON data from the API (JSON = JavaScript Object Notation).
$target			Your channel's username (with correct capitalisation).
$channel		Channel link (using Mixer's url and the $target variable (your username)).
$image			The stream image displaying in your tweet.  You can use either your avatar or social thumbnail.
			Comment and uncomment accordingly.
$player			Embedded video player link (using Mixer's url and the $target variable (your username)).
$streaming		The first declaration sets the variable to "Streaming: ".
			The second appends what is streaming to the variable (e.g. Fallout 76, Overwatch, Web Show, etc.).
$image			Social thumbnail link.  This is what is used to display the image in your tweet.
$avatar			Avatar link
$title			The title of your stream (e.g. Open lobby, playing with followers.).

The settings below configures your Twitter Card as follows:

$image			Your avatar
$title:			Playin' gamez wif frandz!  Follow meh on twottur!
$streaming		Streaming: Dead by Daylight

*/
$url = "https://mixer.com/api/v1/channels/$mixer";
$content = file_get_contents( $url );
$json = json_decode( $content, true );
$target = $json[ "user" ][ "username" ];
$channel = "https://mixer.com/$target";
$image = $json[ "user" ][ "avatarUrl" ];
/*
$image = $json[ "thumbnail" ][ "url" ];
*/
$player = "https://mixer.com/embed/player/$target";
$streaming = "Streaming: ";
$streaming .= $json[ "type" ][ "name" ];
$title = $json[ "name" ];

?>
<!--
SECTION THREE: Webpage HTML
--------------------------------------------------------------------------------
This is the HTML for the webpage
-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!-- The title of the page -->
	<title><? echo $title; ?></title>
	<!-- Uses your Mixer avatar as the shortcut icon -->
	<link rel="shortcut icon" href="<? echo $avatar; ?>" type="image/x-icon"/>
	<!-- Redirects the page to your Mixer channel -->
	<script type="text/javascript">window.location.href = "<?php echo $channel; ?>";</script>
	<!-- The CSS for styling the page and elements -->
	<style>
		body,
		html {
			background: #000;
			margin: 0px;
			padding: 0px;
			text-align: center;
		}
		iframe {
			border: 2px solid #000;
			height: 360px;
			width: 640px;
		}
	</style>
	<!-- Twitter Card settings -->
	<meta name="twitter:card" content="player">
	<meta name="twitter:site" content="<? echo $twitter; ?>">
	<meta name="twitter:title" content="<? echo $title; ?>">
	<meta name="twitter:description" content="<? echo $streaming; ?>">
	<meta name="twitter:image" content="<? echo $image; ?>">
	<meta name="twitter:player" content="<? echo $player; ?>">
	<meta name="twitter:player:width" content="640">
	<meta name="twitter:player:height" content="360">
	</head>
	<body>
		<!-- If redirecting to your Mixer channel fails, it displays your stream in an iframe -->
		<iframe src="<? echo $player; ?>"></iframe>
	</body>
</html>
