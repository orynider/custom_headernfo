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
	'GENESYS_VERSE'	=> '',
	'EXODUS_VERSE'	=> '',
	'LEVITICUS_VERSE'	=> '',
	'NUMBERS_VERSE'	=> '',
	'DEUTERONOMY_VERSE'	=> '"...man does not live by bread alone, but man lives by every word that comes from the mouth of the LORD." (Deuteronomy 8:3)',

));
