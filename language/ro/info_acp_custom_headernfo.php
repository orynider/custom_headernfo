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
	'ACP_HEADER_INFO_TITLE'					=> 'Custom Header Info',
    'ACP_HEADER_INFO_CONFIG'				=> 'Configurare Header Info',
	'ACP_MANAGE_CONFIG'						=> 'Configuraţie',

	'ACP_MANAGE_FORUMS'						=> 'Forumuri',
	'ACP_MANAGE_PAGES'							=> 'Pagini',
	
	'ACP_NO_BANNER'								=> 'Fără Banner',
	
	'HEADER_INFO_INTRO'							=> 'Aceasta este o pagină de configurare pentru Extensia Custom Header Info de orynider (FlorinCB).',

	'HEADER_INFO_DONATE'						=> '<a href="https://www.paypal.me/orynider"><strong>Donează</strong></a>',
	'HEADER_INFO_DONATE_EXPLAIN'		=> 'Dacă îţi place această extensie ia în consideraţie să faci o donaţie',

	'HEADER_INFO_VERSION'						=> 'Versiune',
 	'HEADER_INFO_CHECK'							=> 'Verifică Versiune Manual la <a href="http://mxpcms.sf.net/forum/index.php"><strong>Forum Acasă</strong></a>',
	'HEADER_INFO_EDIT'							=> 'Modifică itemul header info în baza de date.',

	'ROW_HEIGHT'										=> 'Înălţime a fiecărui rând ticker în PX',
	'ROW_HEIGHT_EXPLAIN'						=> 'Fiecare banner este încărcat în info ticker ca un rând folosind acestă applicaţie JavaScript. Înălţimea fiecărui rând ticker trebuie să fie uniformă.',

	'THUMBNAIL_FONT_DADABASE_ERROR_FOR_ID'	=> 'Thumbnail font dadabase error pt id. <br />Modifică bannerul în ACP şi reselectează un font uploadad împreună cu fişierele lui css.',
	
	'SPEED'														=> 'Vizeză de tranzacţie animatiei în millisecunde',

	'SPEED_EXPLAIN'										=> 'Vizeză de tranzacţie a animatiei de defilare banner în info ticker.',

	'INTERVAL'												=> 'Timp între schimbare în millisecunde',
	'INTERVAL_EXPLAIN'									=> 'Timp să citeşti infomaţia între defilarea tickerului.',

	'MOUSESTOP'											=> 'Opreşte tickerul la mouseover',
	'MOUSESTOP_EXPLAIN'								=> 'Dacă este setat la adevărat, tickerul se va opri la mouseover.',

	'INFO_DIRECTION'									=> 'Direcţia de defilare',
	'INFO_DIRECTION_EXPLAIN'						=> 'Direcţia în care lista va defila. Care este sus ori jos.',

	'HEADER_INFO_EDIT'								=> 'Modifică idem header info în database',
	'HEADER_INFO_ADD'								=> 'Adaugă item header info în database.',

	'HEADER_INFO_NAME'								=> 'Nume Titlu Banner',
	'HEADER_INFO_NAME_EXPLAIN'				=> 'Titlu de nou item header info. Titlul va apărea în header_info tooltip când navighezi cu miceul deasupra.',

	'HEADER_INFO_TITLE_PIXELS'					=> 'Mărime Titlu Banner',
	'HEADER_INFO_TITLE_PIXELS_EXPLAIN'		=> 'Sets the number of pixels for banner title.<br /><em>The default for prosilver is 12px.</em>',

	'HEADER_INFO_DESC'								=> 'Scurtă Descriere',
	'HEADER_INFO_DESC_EXPLAIN'				=> 'Scurtă descriere a noului item header info. Descrierea va apărea în tooltipul header info când navigaţi cu miceul deoasupra.',

	'HEADER_INFO_DESC_PIXELS'					=> 'Mărime Descriere Banner',
	'HEADER_INFO_DESC_PIXELS_EXPLAIN'		=> 'Setează numărul de pixeli pt descriere banner.',

	'HEADER_INFO_LONGDESC'								=> 'Descriere Lungă',
	'HEADER_INFO_LONGDESC_EXPLAIN'				=> 'Descriere Lungă a noului item header info. Descrierea va apărea în tooltipul header info când navigaţi cu miceul deoasupra. Notează: Maximum 255 caractere',

	'HEADER_INFO_FORUM_DESC'						=> 'Notează: Deja adăugat forums este dezactivat. Modifică înschimb intrarea.',
	'CLICK_TO_SELECT'									=> 'Click în boxă să selectezi o culoare.',

	'USE_EXTENED_SITE_DESC'							=> 'Foloseşte descriere sit extinsă.',
	'USE_EXTENED_SITE_DESC_EXPLAIN'			=> 'Foloseşte descrierea sitului standard cu acest item header info.<br /><strong>Notează: Aceasta va dizactiva descrierea de mai sus şi filierul de limbei selectate, şi aşa ele nu vor avea deloc effect dacă această <em>descriere extinsă a sitului</em> a fost selectată.</strong> ',

	'HEADER_INFO_RADIUS'								=> 'Bordură Radius Banner din header info.',
	'HEADER_INFO_RADIUS_EXPLAIN'				=> 'Setează numărul de pixeli pt rotunjire de colţuri banner header info. Setarea acesteia la 0 însemnă căci bannerul va avea colţuri drepte.',

	'HEADER_INFO_PIXELS'								=> 'Bordură Radius Logo',
	'HEADER_INFO_PIXELS_EXPLAIN'					=> 'Setează numărul de pixeli pt rotunjire de colţuri logo header info.<br /><em>Implicit pt prosilver este 7px.</em>',

	'HEADER_INFO_LEFT'								=> 'Bordură Radius Banner colţuri stânga',
	'HEADER_INFO_LEFT_EXPLAIN'				=> 'Rounjeşte partea stângă a colţurilor să se potrivească cu bannerul header info.',
	
	'HEADER_INFO_RIGHT'							=> 'Bordură Radius Banner colţuri dreapta',
	'HEADER_INFO_RIGHT_EXPLAIN'				=> 'Rounjeşte partea dreaptă a colţurilor să se potrivească cu bannerul header info.',
	
	'HEADER_INFO_TITLE_COLOUR'					=> 'Culoare Titlu întru logo ori banner.',
	'HEADER_INFO_TITLE_COLOUR_EXPLAIN'		=> 'Selectează o culoare pt textul titlului.<br />Setarea acestei opţiuni va aplica, unde-i posibil, acelaşi offset gradient coloare fundal interior care este folosită în “prosilver”.<br /><em>Default = #12A3EB</em>',

	'HEADER_INFO_DESC_COLOUR'					=> 'Culoare Descriere întru logo ori banner.',
	'HEADER_INFO_DESC_COLOUR_EXPLAIN'		=> 'Selectează o culoare pt textul descrierii.<br />Setarea acestei opţiuni va aplica, unde-i acelaşi offset gradient coloare fundal interior care este folosită în “prosilver”.<br /><em>Default = #000000</em>',

	'HEADER_INFO_OPTIONS'							=> 'Opţiuni Antet ori Header',
	
	'FILE_NOT_EXISTS'									=> 'Fişierul nu există.',
	
	'HEADER_INFO_URL'									=> 'URL Link',
	'HEADER_INFO_URL_EXPLAIN'					=> 'Introdu URL-ul pt info antet, Dacă vrei ca legăturile interne şi externe să fie recunoscute în mod automat.',

	'HEADER_INFO_IMAGE'								=> 'URL Imagine',
	'HEADER_INFO_IMAGE_EXPLAIN'				=> 'URL pt imaginea bannerului antetului. Pentru afişarea vizuală corectă introdu imagini de mărime <strong>458x50px</strong>. Banerele pe antet pot fi uploadate în folderul images/banners.',

	'HEADER_INFO_NAME_B'							=> 'Name or description of the header info',
	'HEADER_INFO_IMAGE_B'							=> 'Image of the header info',
	'HEADER_INFO_URL_B'								=> 'URL of the header info',

	'HEADER_INFO_COPYRIGHT'						=> '<strong>Extensia Custom Header Info de <a href="http://mxpcms.sf.net/">orynider</a></strong>',
	'HEADER_INFO_ADDED'							=> 'Nou info a fost adăugat în antet!',
	'HEADER_INFO_UDPATED'						=> 'Antetul a fost actualizat!',

	'HEADER_INFO_NAVBAR'							=> 'După Header Info BreadCrumbs (1)',
	'HEADER_INFO_SEARCHBOX'						=> 'Înainte de Header Info SearchBox (2)',
	'HEADER_INFO_HEADER'								=> 'După Header Info HeaderBar (4)',
	'HEADER_INFO_INDEX'								=> 'Înainte de Header Info PageBody (3)',
	'HEADER_INFO_POSITION'							=> 'Poziţie Personalizată Header Info',
	'HEADER_INFO_POSITION_EXPLAIN'				=> 'Vrei să afişiezi Custom Header Info în NavBar ori HeaderBar, etc.?',
	'HEADER_INFO_ENABLE'								=> 'Înainte de Header Info PageHeader (0)',
	'HEADER_INFO_ENABLE_EXPLAIN'				=> 'Activează Custom Header Info în overall board.',

	'THUMB_CACHE'												=> 'Foloseşte thumbnail cache',
	'THUMB_CACHE_EXPLAIN'									=> 'Activează caşul pt thumbnail. Dacă foloseşti facilitatea Thumbnail Cache trebuie să cureţi caşul miniaturei thumbnail după ce editezi bannerele tale să le re-genererezi.',

	'HEADER_INFO_PIC_WIDTH'									=> 'Lăţime (pixeli)',
	'HEADER_INFO_PIC_WIDTH_EXPLAIN'					=> 'Alege lăţimea banerului.',

	'HEADER_INFO_PIC_HEIGHT'							=> 'Înălţime (pixeli)',
	'HEADER_INFO_PIC_HEIGHT_EXPLAIN'				=> 'Alege înălţimea banerului.',

	'HEADER_INFO_LICENSE'									=> 'Licenţă',
	'HEADER_INFO_LICENSE_EXPLAIN'					=> 'Aceasta este tipul de licenţă cu care un utilizator oru dezvoltator trebuie să fie de acord să descarce şi folosească un baner ori anumit text din antet.',

	'HEADER_INFO_PINNED'								=> 'Info Prioritate',
	'HEADER_INFO_PINNED_EXPLAIN'				=> 'Alege dacă vrei ca banerul să fie ori nu prioritar. Cele prioritare vor fi afişate în susul listei.',

	'HEADER_INFO_DISABLE'							=> 'Dezactivează afişare item',
	'HEADER_INFO_DISABLE_EXPLAIN'			=> 'Această setare dezactivează un item (imagine ori text), însă însă este vizibilă. Un mesaj informează utilizatorul că acesta ori linkul url nu este disponibil la momentul acesta.',

	'HEADER_INFO_CONF_UDPATED'				=> 'Configurarea pt Custom Header Info a fost actualizată cu succes.',
	'HEADER_INFO_ERROR'								=> 'Eroare de actualizare pt setările cofigurării Custom Header Info.',

	'MODULE_NAME'										=> 'Nume Bază de Date',
	'MODULE_NAME_EXPLAIN'						=> 'Acesta este numele bazei de date, de genul \'Custom Header Info\'',

	'HEADER_INFO_LINK'								=> 'Permite Legături',
	'HEADER_INFO_LINKS_MESSAGE'				=> 'Implicit Mesajul \'Fără Legături\'',
	'HEADER_INFO_LINKS_EXPLAIN'				=> 'Facă legăturile nu sunt permise acest text inloc va fi afişat',

	'WYSIWYG_PATH'									=> 'Cale spre soft WYSIWYG',
	'WYSIWYG_PATH_EXPLAIN'					=> 'Aceasta este calea (din MXP/phpBB root) spre folderul softului WYSIWYG, ex \'assets/javascript/\' dacă ai uploadat, de exemplu, TinyMCE în assets/javascript/tinymce.',

	'HEADER_INFO_TYPE'							=> 'Categorie multilingvistică implicită.',
	'HEADER_INFO_TYPE_EXPLAIN'				=> 'Selectează categoria mutilingvistică implicită pentru textul banerului ori info antet. Fiecare categorie mutilingvistică este un folder în directorul language al extensiei şi are un fişier common.php înăuntru cu textul înmânat ca imagine în miniatura thumnail sau ca text în banner.',
	
	'MULTI_LANGUAGE_BANNER'				=> 'Baner Multilingvistic',
	'HTML_MULTI_LANGUAGE_TEXT'			=> 'Text Html Multilingvistic',
	'SIMPLE_DB_TEXT'									=> 'Simplu Text BD',
	'SIMPLE_BG_LOGO'								=> 'Simplu Logo Fundal',

	'SP_WATERMARK' 							=> 'Setări WaterMark',
	'SP_WATERMARK_EXPLAIN'				=> 'Configură un WaterMark de ex. nume de director a categoriei mutilingvistică să fie plasat în baner pe miniatura thumbnail.',

	'WATERMARK' 						        => 'Foloseşte WaterMark',

	'WATERMARK_PLACENT' 				        => 'Poziţia WaterMark pe baner',
	'WATERMARK_PLACENT_EXPLAIN'	         => 'Selectează poziţia unde să fie plasat WaterMark pe miniatura thumbnail.',

	'BACKGROUNDS_DIR'								=> 'Director cu imagini de fundal pt Header Info',
	'BACKGROUNDS_PATH'							=> 'Cale către directorul cu imagini de fundal pt Header Info',
	'BACKGROUNDS_DIR_EXPLAIN' 				=> 'Cale sub phpBB root directory, de ex. <samp>images/backgrounds</samp>.',

	'HEADER_INFO_DIR'							        => 'Director multilingvistic cu text (informaţii, citate, ş.a.) pt Header Info.',
	'HEADER_INFO_PATH'								=> 'Cale către director de stocare a text multilingvistic.',
	'HEADER_INFO_DIR_EXPLAIN'					=> 'Cale sub directorul phpBB root, de ex. <samp>language/politics</samp>.',

	'HEADER_INFO_FONT'							=> 'Fişier Font pentru textul miniaturii thumbnail al Header\'s Info .',
	'HEADER_INFO_FONT_EXPLAIN'			=> 'Selectează nume font pentru extensie stocate în \'assets/fonts\', de ex. <samp>tituscbz</samp>.',

	'BANNERS_DIR'										=> 'Director cu nanere a Header\'s Info.',
	'BANNERS_PATH'									=> 'Cale spre director cu imagini de baner a Header\'s Info.',
	'BANNERS_DIR_EXPLAIN'						=> 'Cale sub phpBB root directory, de ex. <samp>images/banners</samp>.',

	'ACP_NO_HEADER_INFO'						=> 'Nu există niciun item.',
  
	'ACL_A_HEADER_INFO'							=> 'Poate adminitra Custom Header Info.',

	'SHOW_AMOUNT'								=> 'Itemuri minime de afişat.',
	'SHOW_AMOUNT_EXPLAIN'					=> 'Itemuri minime de interogat pentru afişare în header info.',
));