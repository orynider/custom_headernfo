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

$lang = array_merge($lang, array(
	'Genesys 31:1'	=> '.',
	'Exodus 33:20'	=> '.',
	'Leviticus 23:6'	=> '!',
	'Numbers10:35'	=> '.',
	'Deuteronomy 8:3'	=> '"."',

));

// THE END
?>