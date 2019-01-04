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
    'ACP_HEADER_INFO_CONFIG'							=> 'Header Info Configuration',
	'ACP_MANAGE_CONFIG'									=> 'Configuration',

	'ACP_MANAGE_FORUMS'									=> 'Forums',
	'ACP_MANAGE_PAGES'									=> 'Pages',

	'HEADER_INFO_INTRO'									=> 'This is configuration page for the Custom Header Info Extension by orynider.',

	'HEADER_INFO_DONATE'				                => '<a href="https://www.paypal.me/orynider"><strong>Donate</strong></a>',
	'HEADER_INFO_DONATE_EXPLAIN'	                => 'If you like this extension considers a donation',

	'HEADER_INFO_VERSION'								=> 'Version',
 	'HEADER_INFO_CHECK'									=> 'Check Manually at <a href="http://mxpcms.sf.net/forum/index.php"><strong>Forum Home</strong></a>',

	'HEADER_INFO_EDIT'										=> 'Edit header info item to database.',
	'HEADER_INFO_ADD'									    => 'Add header info item to database.',

	'HEADER_INFO_NAME'									=> 'Name',
	'HEADER_INFO_NAME_EXPLAIN'						=> 'Name of new header info item. The name will appear in the header_info tooltip when you navigate with the mouse above.',

	'HEADER_INFO_DESC'										=> 'Short Description',
	'HEADER_INFO_DESC_EXPLAIN'						=> 'Short description of new header info item. The description will appear in the header info tooltip when you navigate with the mouse above.',

	'HEADER_INFO_LONGDESC'								=> 'Long Description',
	'HEADER_INFO_LONGDESC_EXPLAIN'				=> 'Long description of new header info item. The description will appear in the header info tooltip when you navigate with the mouse above. Note: Maximum 255 characters',

	'HEADER_INFO_URL'									    => 'URL',
	'HEADER_INFO_URL_EXPLAIN'							=> 'Enter the URL of the header_info, If you want the internal and external links to be recognized automatically I recommend this great extension <a href="https://www.phpbb.com/customise/db/extension/elonw" target="_blank">External Links Open in New Window</a>',

	'HEADER_INFO_IMAGE'									=> 'Image',
	'HEADER_INFO_IMAGE_EXPLAIN'						=> 'URL of image of header info banner. For a correct visual insert images of size <strong>458x50px</strong>. Header banners can be uploaded to the images/banners folder.',

	'HEADER_INFO_NAME_B'									=> 'Name or description of the header_info',
	'HEADER_INFO_IMAGE_B'								=> 'Image of the header info',
	'HEADER_INFO_URL_B'									=> 'URL of the header info',

	'HEADER_INFO_COPYRIGHT'					        => '<strong>Extension Custom Header Info by <a href="http://mxpcms.sf.net/">orynider</a></strong>',
	'HEADER_INFO_ADDED'							        => 'New header Info has been added!',
	'HEADER_INFO_UDPATED'						        => 'Header has been updated!',

	'HEADER_INFO_NAVBAR'								=> 'Header Info BreadCrumbs after (1)',
	'HEADER_INFO_SEARCHBOX'							=> 'Header Info SearchBox before (2)',
	'HEADER_INFO_HEADER'									=> 'Header Info HeaderBar after (4)',
	'HEADER_INFO_INDEX'									=> 'Header Info PageBody before (3)',
	'HEADER_INFO_POSITION'								=> 'Header Info Custom Position',
	'HEADER_INFO_POSITION_EXPLAIN'					=> 'Do you want to show the Custom Header Info in the NavBar or Header?',
	'HEADER_INFO_ENABLE'									=> 'Header Info Page Header before (0)',
	'HEADER_INFO_ENABLE_EXPLAIN'					=> 'Enable Custom Header Info in overall board.',

	'THUMB_CACHE'												=> 'Use thumbnail cache',
	'THUMB_CACHE_EXPLAIN'									=> 'Enable caching of thumbnail. If you use the Thumbnail Cache feature you must clear your thumbnail cache after editing your banners to make them re-generated.',

	'HEADER_INFO_PIC_WIDTH'									=> 'Width (pixel)',
	'HEADER_INFO_PIC_WIDTH_EXPLAIN'					=> 'Choose the width of the banner.',

	'HEADER_INFO_PIC_HEIGHT'								=> 'Height (pixel)',
	'HEADER_INFO_PIC_HEIGHT_EXPLAIN'					=> 'Choose the height of the banner.',

	'HEADER_INFO_LICENSE'							=> 'License',
	'HEADER_INFO_LICENSE_EXPLAIN'			=> 'This is the license type and version a user or developer must agree to download and use a banner or a text from the header.',

	'HEADER_INFO_PINNED'								=> 'Pin Info',
	'HEADER_INFO_PINNED_EXPLAIN'				=> 'Choose if you want the file pinned or not. Pinned files will always be shown at the top of the file list.',

	'HEADER_INFO_DISABLE'							=> 'Disable item display',
	'HEADER_INFO_DISABLE_EXPLAIN'			=> 'This setting makes the item disabled, but still visible. A message informs the user this item or the item url link is not available at the moment.',

	'HEADER_INFO_CONF_UDPATED'					    => 'Custom Header Info configuration was succesfully updated.',
	'HEADER_INFO_ERROR'						            => 'Custom Header Info setting error.',

	'MODULE_NAME'					=> 'Database Name',
	'MODULE_NAME_EXPLAIN'			=> 'This is the name of the database, such as \'Custom Header Info\'',

	'HEADER_INFO_LINK'							=> 'Allow Links',
	'HEADER_INFO_LINKS_MESSAGE'			=> 'Default \'No Links\' Message',
	'HEADER_INFO_LINKS_EXPLAIN'			=> 'If links are not allowed this text will be displayed instead',

	'WYSIWYG_PATH'						=> 'Path to WYSIWYG software',
	'WYSIWYG_PATH_EXPLAIN'			=> 'This is the path (from MX-Publisher/phpBB root) to the WYSIWYG software folder, eg \'assets/javascript/\' if you have uploaded, for example, TinyMCE in assets/javascript/tinymce.',

	'HEADER_INFO_TYPE'			=> 'default language category',
	'HEADER_INFO_TYPE_EXPLAIN'		=> 'Select default language category for Your banner or header info.',

	'MULTI_LANGUAGE_BANNER'	        => 'Multi Language Banner',
	'HTML_MULTI_LANGUAGE_TEXT'	        => 'Html Multi Language Text',
	'SIMPLE_DB_TEXT'	        => 'Simple Database Text',
	'SIMPLE_BG_LOGO'	        => 'Simple Background Logo',

	'BACKGROUNDS_DIR'								=> 'Header\'s Info backgrounds directory.',
	'BACKGROUNDS_PATH'							=> 'Header\'s Info backgrounds image storage path',
	'BACKGROUNDS_DIR_EXPLAIN' 				=> 'Path under your phpBB root directory, e.g. <samp>images/backgrounds</samp>.',
	
	'HEADER_INFO_DIR'									        => 'Header\'s Info language directory.',
	'HEADER_INFO_PATH'								        => 'Header\'s Info text language storage path.',
	'HEADER_INFO_DIR_EXPLAIN'						        => 'Path under your phpBB root directory, e.g. <samp>language/politics</samp>.',

	'BANNERS_DIR'									        => 'Header\'s Info banners directory.',
	'BANNERS_PATH'								        => 'Header\'s Info banners image storage path.',
	'BANNERS_DIR_EXPLAIN'						        => 'Path under your phpBB root directory, e.g. <samp>images/banners</samp>.',

	'ACP_NO_HEADER_INFO'                                 => 'There is no custom header info.',
    //Add permissions acp	
	'ACL_A_HEADER_INFO'									=> 'Can manage Custom Header Info',

	'SHOW_AMOUNT'											=> 'Minimum items to show',
	'SHOW_AMOUNT_EXPLAIN'								=> 'Minimum items to query for display in header info.',
));