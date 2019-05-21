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
    'ACP_HEADER_INFO_CONFIG'							=> 'Configurare Header Info',
	'ACP_MANAGE_CONFIG'									=> 'Configuraţie',

	'ACP_MANAGE_FORUMS'									=> 'Forumuri',
	'ACP_MANAGE_PAGES'									=> 'Pagini',

	'HEADER_INFO_INTRO'									=> 'Aceasta este o pagină de configurare pentru Extensia Custom Header Info de orynider (FlorinCB).',

	'HEADER_INFO_DONATE'				                => '<a href="https://www.paypal.me/orynider"><strong>Donează</strong></a>',
	'HEADER_INFO_DONATE_EXPLAIN'	                => 'Dacă îţi place această extensie ia în consideraţie să faci o donaţie',

	'HEADER_INFO_VERSION'								=> 'Versiune',
 	'HEADER_INFO_CHECK'									=> 'Verifică Versiune Manual la <a href="http://mxpcms.sf.net/forum/index.php"><strong>Forum Acasă</strong></a>',

	'ROW_HEIGHT'											=> 'Înălţime a fiecărui rând ticker în PX',
	'ROW_HEIGHT_EXPLAIN'								=> 'Fiecare banner este încărcat în info ticker ca un rând folosind acestă applicaţie JavaScript. Înălţimea fiecărui rând ticker trebuie să fie uniformă.',

	'SPEED'														=> 'Viteză de tranziţie a animaţiei în milisecunde',
	'SPEED_EXPLAIN'										=> 'Speed of transition animation between banner scrolling in the info ticker.',

	'INTERVAL'												=> 'Time between change in milliseconds',
	'INTERVAL_EXPLAIN'									=> 'Time to read the info before scrolling the ticker.',

	'MOUSESTOP'											=> 'Stop on mouseover the ticker',
	'MOUSESTOP_EXPLAIN'								=> 'If set to true, the ticker will stop on mouseover.',

	'INFO_DIRECTION'										=> 'The scrolling direction',
	'INFO_DIRECTION_EXPLAIN'						=> 'Direction in witch the list will scroll. That is up or down.',

	'HEADER_INFO_EDIT'										=> 'Edit header info item to database',
	'HEADER_INFO_ADD'									    => 'Add header info item to database.',

	'HEADER_INFO_NAME'									=> 'Banner Title Name',
	'HEADER_INFO_NAME_EXPLAIN'						=> 'Title of new header info item. The title will appear in the header_info tooltip when you navigate with the mouse above.',

	'HEADER_INFO_TITLE_PIXELS'							=> 'Banner Title Size',
	'HEADER_INFO_TITLE_PIXELS_EXPLAIN'			=> 'Sets the number of pixels for banner title.<br /><em>The default for prosilver is 12px.</em>',

	'HEADER_INFO_DESC'										=> 'Short Description',
	'HEADER_INFO_DESC_EXPLAIN'						=> 'Short description of new header info item. The description will appear in the header info tooltip when you navigate with the mouse above.',

	'HEADER_INFO_DESC_PIXELS'							=> 'Banner Description Size',
	'HEADER_INFO_DESC_PIXELS_EXPLAIN'			=> 'Sets the number of pixels for banner description.',

	'HEADER_INFO_LONGDESC'								=> 'Long Description',
	'HEADER_INFO_LONGDESC_EXPLAIN'				=> 'Long description of new header info item. The description will appear in the header info tooltip when you navigate with the mouse above. Note: Maximum 255 characters',

	'HEADER_INFO_FORUM_DESC'						=> 'Note: already added forums are disabled. Edit the entry instead.',
	'CLICK_TO_SELECT'									=> 'Click in the box to select a colour',

	'USE_EXTENED_SITE_DESC'							=> 'Use extended site description',
	'USE_EXTENED_SITE_DESC_EXPLAIN'			=> 'Use the standard site description with this header info item.<br /><strong>Note: This will disable the above description and the language directory selected, and so they will have no affect if this <em>extended site description</em> has been selected.</strong> ',

	'HEADER_INFO_RADIUS'								=> 'Header info banner\'s border radius',
	'HEADER_INFO_RADIUS_EXPLAIN'				=> 'Sets the number of pixels for rounding the header info banner\'s corners. Setting this to 0 means that the banner will have square corners.',

	'HEADER_INFO_PIXELS'								=> 'Header info logo\'s radius',
	'HEADER_INFO_PIXELS_EXPLAIN'					=> 'Sets the number of pixels for rounding the header info logo\'s corners.<br /><em>The default for prosilver is 7px.</em>',

	'HEADER_INFO_LEFT'								=> 'Header info banner\'s left corners',
	'HEADER_INFO_LEFT_EXPLAIN'				=> 'Round the left side corners to match the header banner.',
	
	'HEADER_INFO_RIGHT'							=> 'Header info banner\'s right corners',
	'HEADER_INFO_RIGHT_EXPLAIN'				=> 'Round the right side corners to match the header banner.',
	
	'HEADER_INFO_TITLE_COLOUR'					=> 'Header info title colour inside logo or banner.',
	'HEADER_INFO_TITLE_COLOUR_EXPLAIN'		=> 'Select a colour for the header info title text.<br />Setting this option will apply, where possible, the same gradient offsets to the inner background colour that are used in “prosilver”.<br /><em>Default = #12A3EB</em>',

	'HEADER_INFO_DESC_COLOUR'					=> 'Header info description colour inside logo or banner.',
	'HEADER_INFO_DESC_COLOUR_EXPLAIN'		=> 'Select a colour for the header info description text.<br />Setting this option will apply, where possible, the same gradient offsets to the text colour that are used in “prosilver”.<br /><em>Default = #000000</em>',

	'HEADER_INFO_OPTIONS'							=> 'Header options',
	
	'FILE_NOT_EXISTS'										=> 'This file dos not exist.',
	
	'HEADER_INFO_URL'									=> 'URL',
	'HEADER_INFO_URL_EXPLAIN'						=> 'Enter the URL of the header info, If you want the internal and external links to be recognized automatically.',

	'HEADER_INFO_IMAGE'								=> 'Image',
	'HEADER_INFO_IMAGE_EXPLAIN'					=> 'URL of image of header info banner. For a correct visual insert images of size <strong>458x50px</strong>. Header banners can be uploaded to the images/banners folder.',

	'HEADER_INFO_NAME_B'								=> 'Name or description of the header info',
	'HEADER_INFO_IMAGE_B'							=> 'Image of the header info',
	'HEADER_INFO_URL_B'								=> 'URL of the header info',

	'HEADER_INFO_COPYRIGHT'					        => '<strong>Extension Custom Header Info by <a href="http://mxpcms.sf.net/">orynider</a></strong>',
	'HEADER_INFO_ADDED'							        => 'New header Info has been added!',
	'HEADER_INFO_UDPATED'						        => 'Header has been updated!',

	'HEADER_INFO_NAVBAR'							=> 'Header Info BreadCrumbs after (1)',
	'HEADER_INFO_SEARCHBOX'						=> 'Header Info SearchBox before (2)',
	'HEADER_INFO_HEADER'								=> 'Header Info HeaderBar after (4)',
	'HEADER_INFO_INDEX'								=> 'Header Info PageBody before (3)',
	'HEADER_INFO_POSITION'							=> 'Header Info Custom Position',
	'HEADER_INFO_POSITION_EXPLAIN'				=> 'Do you want to show the Custom Header Info in the NavBar or Header?',
	'HEADER_INFO_ENABLE'								=> 'Header Info Page Header before (0)',
	'HEADER_INFO_ENABLE_EXPLAIN'				=> 'Enable Custom Header Info in overall board.',

	'THUMB_CACHE'												=> 'Use thumbnail cache',
	'THUMB_CACHE_EXPLAIN'									=> 'Enable caching of thumbnail. If you use the Thumbnail Cache feature you must clear your thumbnail cache after editing your banners to make them re-generated.',

	'HEADER_INFO_PIC_WIDTH'									=> 'Width (pixel)',
	'HEADER_INFO_PIC_WIDTH_EXPLAIN'					=> 'Choose the width of the banner.',

	'HEADER_INFO_PIC_HEIGHT'							=> 'Height (pixel)',
	'HEADER_INFO_PIC_HEIGHT_EXPLAIN'				=> 'Choose the height of the banner.',

	'HEADER_INFO_LICENSE'									=> 'License',
	'HEADER_INFO_LICENSE_EXPLAIN'					=> 'This is the license type and version a user or developer must agree to download and use a banner or a text from the header.',

	'HEADER_INFO_PINNED'								=> 'Pin Info',
	'HEADER_INFO_PINNED_EXPLAIN'				=> 'Choose if you want the file pinned or not. Pinned files will always be shown at the top of the file list.',

	'HEADER_INFO_DISABLE'							=> 'Disable item display',
	'HEADER_INFO_DISABLE_EXPLAIN'			=> 'This setting makes the item disabled, but still visible. A message informs the user this item or the item url link is not available at the moment.',

	'HEADER_INFO_CONF_UDPATED'			    => 'Custom Header Info configuration was succesfully updated.',
	'HEADER_INFO_ERROR'				            => 'Custom Header Info configuration settings update error.',

	'MODULE_NAME'									=> 'Database Name',
	'MODULE_NAME_EXPLAIN'						=> 'This is the name of the database, such as \'Custom Header Info\'',

	'HEADER_INFO_LINK'								=> 'Allow Links',
	'HEADER_INFO_LINKS_MESSAGE'				=> 'Default \'No Links\' Message',
	'HEADER_INFO_LINKS_EXPLAIN'				=> 'If links are not allowed this text will be displayed instead',

	'WYSIWYG_PATH'								=> 'Path to WYSIWYG software',
	'WYSIWYG_PATH_EXPLAIN'					=> 'This is the path (from MX-Publisher/phpBB root) to the WYSIWYG software folder, eg \'assets/javascript/\' if you have uploaded, for example, TinyMCE in assets/javascript/tinymce.',

	'HEADER_INFO_TYPE'							=> 'default language category',
	'HEADER_INFO_TYPE_EXPLAIN'			=> 'Select default language category for Your banner or header info.',

	'MULTI_LANGUAGE_BANNER'		        => 'Multi Language Banner',
	'HTML_MULTI_LANGUAGE_TEXT'	        => 'Html Multi Language Text',
	'SIMPLE_DB_TEXT'						        => 'Simple Database Text',
	'SIMPLE_BG_LOGO'						        => 'Simple Background Logo',

	'SP_WATERMARK' 							=> 'WaterMark Settings',
	'SP_WATERMARK_EXPLAIN' 			=> 'Configure a WaterMark i.e. the categoty language directory to be placed on the thumbnail banner.',

	'WATERMARK' 						        => 'Use WaterMark',

	'WATERMARK_PLACENT' 				        => 'WaterMark position on the banner',
	'WATERMARK_PLACENT_EXPLAIN'	         => 'Select the position were the WaterMark to be placed on the banner.',

	'BACKGROUNDS_DIR'								=> 'Header\'s Info backgrounds directory.',
	'BACKGROUNDS_PATH'							=> 'Header\'s Info backgrounds image storage path',
	'BACKGROUNDS_DIR_EXPLAIN' 				=> 'Path under your phpBB root directory, e.g. <samp>images/backgrounds</samp>.',

	'HEADER_INFO_DIR'							        => 'Header\'s Info language directory.',
	'HEADER_INFO_PATH'							        => 'Header\'s Info text language storage path.',
	'HEADER_INFO_DIR_EXPLAIN'				        => 'Path under your phpBB root directory, e.g. <samp>language/politics</samp>.',

	'HEADER_INFO_FONT'							        => 'Header\'s Info thumbnail banner font file.',
	'HEADER_INFO_FONT_EXPLAIN'			        => 'Select font name from the extension \'assets/fonts\' root folder, e.g. <samp>tituscbz</samp>.',

	'BANNERS_DIR'									        => 'Header\'s Info banners directory.',
	'BANNERS_PATH'								        => 'Header\'s Info banners image storage path.',
	'BANNERS_DIR_EXPLAIN'						        => 'Path under your phpBB root directory, e.g. <samp>images/banners</samp>.',

	'ACP_NO_HEADER_INFO'                                 => 'There is no custom header info.',
    //Add permissions acp	
	'ACL_A_HEADER_INFO'									=> 'Can manage Custom Header Info',

	'SHOW_AMOUNT'											=> 'Minimum items to show',
	'SHOW_AMOUNT_EXPLAIN'								=> 'Minimum items to query for display in header info.',
));