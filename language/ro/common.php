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
	'HEADER_INFO_TITLE'									=> 'Header Info Personalizat',
	'CUSTOM_TEXT' 											=> 'Text forum header personalizat',
	'NO_CUSTOM_TEXT' 									=> 'Nu există text personalizat',
	'COULDNT_GET'											=> 'Nu se poate obţine',
	'CONFIG'														=> 'Configurare',
	'BACKGROUNDS_PATH_EXPLAIN' 				=> 'Path under your phpBB root path, e.g. <samp>ext/orynider/customheadernfo/styles/all/images/backgrounds/</samp>.',
	'CUSTOM_HEADER_BACKGROUND' 				=> 'Imagine fundal header persolaizată',
	'NO_HEADER_BACKGROUND' 						=> 'Nu există imagine de fundal header personalizată',
	'HEADER_INFO_SCROLL_INDEX'					=> 'Random Header Banner Scroll',
	'HEADER_INFO_SCROLL_NOTHING'				=> 'Nu există nici o informaţie în header info personalizat',
	'CLICK_TO_SELECT'										=> 'Dă click in boxă să selectezi o coloană',
	'PIXELS'														=> 'pilxeli',
	//Language Categories
	'bibledates' 											=> 'Date Biblice',
	'biblequotes' 										=> 'Citate Biblice',
	'codaleppo' 										=> 'Codex Aleppo',
	'hisquotes' 											=> 'Citate de El',
	'movies' 												=> 'Filme',
	'peshitta' 											=> 'Peşita',
	'politics' 												=> 'Politică',
	'wlcodex' 											=> 'Leningrad Codex',
	//Language Strings for Totem Ticker's Plugin by Zach Dunn / www.buildinternet.com
	'PREVIOUS_SCROLL'							=> 'Înapoi',
	'NEXT_SCROLL'									=> 'Înainte',
	'START_SCROLL'								=> 'Play',
	'STOP_SCROLL'									=> 'Stop',
	'UP'													=> 'Sus',
	'DOWN'												=> 'Jos',
));
