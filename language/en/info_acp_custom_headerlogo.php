<?php
/**
 *
 * @package phpBB Extension - Custom Header Logo
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, orynider, https://mxpcms.sourceforge.net.org
 * @license GNU General Public License, version 2 (GPL-2.0)
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
	'ACP_HEADER_INFO_TITLE'								=> 'Custom Header Info',
    'ACP_HEADER_INFO_CONFIG'							=> 'Configuration',
	'ACP_MANAGE_FORUMS'									=> 'Forums',
	'ACP_MANAGE_PAGES'									=> 'Pages',
	'HEADER_INFO_INTRO'									=> 'This is configuration page for the Custom Header Info Extension by orynider.',
	'HEADER_INFO_DONATE'				                => '<a href="https://www.paypal.me/orynider"><strong>Donate</strong></a>',
	'HEADER_INFO_DONATE_EXPLAIN'	                => 'If you like this extension considers a donation offer a pizza',	
	'HEADER_INFO_VERSION'								=> 'Version',
 	'HEADER_INFO_CHECK'									=> 'Check Manually at <a href="http://mxpcms.sf.net/forum/index.php"><strong>Forum Home</strong></a>',	
	'HEADER_INFO_EDIT'										=> 'Edit header_info',
	'HEADER_INFO_ADD'									    => 'Add header_info',
	'HEADER_INFO_NAME'									=> 'Name',
	'HEADER_INFO_NAME_EXPLAIN'						=> 'Name of new header_info or description. The name or description will appear in the header_info tooltip when you navigate with the mouse above. Note: Maximum 255 characters',
	'HEADER_INFO_URL'									    => 'URL',
	'HEADER_INFO_URL_EXPLAIN'							=> 'Enter the URL of the header_info, If you want the internal and external links to be recognized automatically I recommend this great extension <a href="https://www.phpbb.com/customise/db/extension/elonw" target="_blank">External Links Open in New Window</a>',
	'HEADER_INFO_IMAGE'									=> 'Image',
	'HEADER_INFO_IMAGE_EXPLAIN'						=> 'URL of image of header_info. for a correct visual insert images of size <strong>458x50px</strong>. Headers can be uploaded to the images/header_info folder of extension.',
	'HEADER_INFO_NAME_B'									=> 'Name or description of the header_info',
	'HEADER_INFO_IMAGE_B'								=> 'Image of the header_info',
	'HEADER_INFO_URL_B'									=> 'URL of the header_info',
	'HEADER_INFO_COPYRIGHT'					        => '<strong>Extension Custom Header Info by <a href="http://mxpcms.sf.net/">orynider</a></strong>',
	'HEADER_INFO_ADDED'							        => 'New header Info has been added!',
	'HEADER_INFO_UDPATED'						        => 'Header has been updated!',
	'HEADER_INFO_ENABLE'									=> 'Enable or Disable',
	'HEADER_INFO_ENABLE_EXPLAIN'					=> 'Enable Custom Header Info in overall board.',
	'HEADER_INFO_CONF_UDPATED'					    => 'Custom Header Info configuration was succesfully updated.',
	'HEADER_INFO_ERROR'						            => 'Custom Header Info setting error.',
    'ACP_NO_HEADER_INFO'                                 => 'There is no custom header info.',
    //Add permissions acp	
	'ACL_A_HEADER_INFO'									=> 'Can manage Custom Header Info',
));