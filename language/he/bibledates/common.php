<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
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
/*
* Note that You should also activate every language also uploaded in the main phpBB/language directory. 
* 
 To Do: We should think on a dedicated directory for multilangual files such as 'multilang' 
			for the files we are currently storing in language subdirectories from were we can import 
			or export using a ACP feature all the keys and values using DB table as FAQ Manager does.
*/
$lang = array_merge($lang, array(
	'Genesys 1:4'	=> '.',
	'Exodus 12:12'	=> '.',
	'וַיִּקְרָ֖א 23:6'	=> 'וּבַחֲמִשָּׁ֨ה עָשָׂ֥ר יֹום֙ לַחֹ֣דֶשׁ הַזֶּ֔ה חַ֥ג הַמַּצֹּ֖ות לַיהוָ֑ה שִׁבְעַ֥ת יָמִ֖ים מַצֹּ֥ות תֹּאכֵֽלוּ׃ ',
	'Numbers18:xx'	=> '.',
	'Deuteronomy 16:3'	=> '"."',

));
