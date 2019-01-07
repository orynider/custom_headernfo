<?php
/**
*
* @package phpBB Extension - Custom Header Logo
* @copyright (c) 2018, orynider, https://mxpcms.sourceforge.net.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'HEADER_INFO_TITLE'							=> 'Custom Header Info',
	'CUSTOM_TEXT' 								=> 'Custom forum header text',
	'NO_CUSTOM_TEXT' 							=> 'No custom text',
	'BACKGROUNDS_PATH_EXPLAIN' 		=> 'Path under your phpBB root path, e.g. <samp>ext/orynider/custom_headernfo/styles/all/images/backgrounds/</samp>.',
	'CUSTOM_HEADER_BACKGROUND' 		=> 'Custom header background image',
	'NO_HEADER_BACKGROUND' 				=> 'No custom header background image',
	'HEADER_INFO_SCROLL_INDEX'		=> 'Random Header Banner Scroll',
	'HEADER_INFO_SCROLL_NOTHING'	=> 'There is no custom header info',
	'bibledates' 		=> 'Bible Dates',
	'biblequotes' 		=> 'Bible Quotes',
	'codaleppo' 		=> 'Codex Aleppo',
	'hisquotes' 		=> 'His Quotes',
	'movies' 		=> 'Movies',
	'peshitta' 		=> 'Peshitta',
	'politics' 		=> 'Politics',
	'wlcodex' 		=> 'Lelingrad Codex',
	'PREVIOUS_SCROLL'							=> 'Back',
	'NEXT_SCROLL'									=> 'Forward',
	'START_SCROLL'								=> 'Play',
	'STOP_SCROLL'									=> 'Stop',
));
