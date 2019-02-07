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
$rand_quote = "About This Tool";
$lang = array_merge($lang, array(
		$rand_quote => "MXP-CMS Team, mxp.sf.net",
		$rand_quote => "in between milestones edition ;)",
		$rand_quote => "MX-Publisher, Fully Modular Portal & CMS for phpBB",
		$rand_quote => "Portal & CMS Site Creation Tool",
		$rand_quote => "pafileDB, FAP, MX-Publisher, Translator",
		$rand_quote => "...Calendar, Links & News...modules",
));
