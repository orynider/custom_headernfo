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
//To do: When done to be trasliterated from Hebrew to Latin script
$lang = array_merge($lang, array(
	'Bîreşit 1:1'	=> 'Bîreşit bâre Elehae iat deşâmeiae uîiat deaerâeae.',
	'Şemot 33:20'	=> '',
	'Ueieqârae 23:6'	=> '',
	'Bîmidbar 10:35'	=>  '',
	//In Targum Davarim is Meltae ???
	'Meltaie 8:3'	=> '',
));

// THE END
?>