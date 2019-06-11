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
	'Genesys 1:1'	=> 'In beginning created Gods all the heavens and all the earth.',
	'Exodus 33:20'	=> 'And to say, You dont grab to see even My face because cant stare Me the man and live.',
	'Leviticus 23:6'	=> '‘And in fifteenth day of month, Festival of Crakers to YÂHWAH! Seven days crakers Him shall you eat!',
	'Numbers10:35'	=> 'And being, in pulling the ark, and said Moses, Rise Him YÂHWAH, and shall be scattered Your enemies, and shall pull Your hated from Your Face.',
	'Deuteronomy 8:3'	=> '"...to make it known that not alone by the bread to his belly lives the man, but on every takes out mouth of YÂHWAH lives the man."',

));
