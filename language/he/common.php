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
	'HEADER_INFO_TITLE'							=> 'מידעות כותרת מותאמ אישית',
	'CUSTOM_TEXT' 									=> 'טקסט כותרת פורום המותאם אישית',
	'NO_CUSTOM_TEXT' 							=> 'אין טקסט מותאם אישית',
	'COULDNT_GET'									=> 'Couldnt get',
	'CONFIG'												=> 'הגדרות בסיס נתונים',
	'BACKGROUNDS_PATH_EXPLAIN' 		=> 'נתיב תחת תיקיית המערכת של phpBB3 לגלריית הסמלים האישיים, למשל <samp>ext/orynider/customheadernfo/styles/all/images/backgrounds/</samp>.<br />Double dots like <samp>../</samp> will be stripped from the path for security reasons.',
	'CUSTOM_HEADER_BACKGROUND' 		=> 'תמונת כותרת מותאמת אישית',
	'NO_HEADER_BACKGROUND' 				=> 'אין תמונה',
	'HEADER_INFO_SCROLL_INDEX'			=> 'גלילת כרזות', //scroll: גלול
	'HEADER_INFO_SCROLL_NOTHING'		=> 'אין טקסט',
	'CLICK_TO_SELECT'							=> 'לחץ כדי לבחור צבע בתיבה',
	'PIXELS'												=> 'פיקסלים',
	//Language Categories
	'bibledates' 											=> 'התנ"ך מועדים',
	'biblequotes' 										=> 'כִּתבֵי הַקוֹדֶשׁ', //ציטוטים התנ"ך
	'codaleppo' 										=> 'כֶּתֶר אֲרָם צוֹבָא‎',
	'hisquotes' 											=> 'ציטוטיםו', // בשורה
	'movies' 												=> 'סֶרֶטיָם',
	'peshitta' 											=> 'פְּשִׁיטְתָא', //ܦܫܝܛܐ
	'politics' 												=> 'פּוֹלִיטִיקָה',
	'wlcodex' 											=> 'כתב יד לנינגרד',
	//Language Strings for Totem Ticker's Plugin by Zach Dunn / www.buildinternet.com
	'PREVIOUS_SCROLL'							=> 'חִזוּר',
	'NEXT_SCROLL'									=> 'קָדִימָה',
	'START_SCROLL'								=> 'לְשַׂחֵק',
	'STOP_SCROLL'									=> 'עֲצוֹר',
	'DOWN'												=> 'העבר למטה',
	'UP'													=> 'העבר למעלה',
));
