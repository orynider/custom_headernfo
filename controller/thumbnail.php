<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2018 orynider - http://mxpcms.sourceforge.net
* @version $Id: thumbnail.php,v 1.5 2008/08/30 22:23:00 orynider Exp
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, Smartor, FlorinCB] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

namespace orynider\custom_headernfo\controller;

/**
 * Enter description here...
 *
 */
class thumbnail
{
	/** @var \phpbb\config\config $config */
	protected $config;
	
	/** @var \phpbb\language\language $language */
	protected $language;
	
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\cache\cache */
	protected $cache;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;
	
	/** @var \phpbb\extension\manager "Extension Manager" */
	protected $ext_manager;
	
	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var ContainerBuilder */
	protected $phpbb_container;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var string */
	protected $php_ext;

	/** @var string phpBB root path */
	protected $root_path;

	/**
	* The database tables
	*
	* @var string
	*/
	protected $custom_header_info_table;

	protected $custom_header_info_config_table;

	/** @var \phpbb\files\factory */
	protected $files_factory;

	/**
	* Constructor
	*
	 * @param \phpbb\config\config							              $config
	 * @param \phpbb\language\language							         $language
	* @param \phpbb\template\template		 							$template
	* @param \phpbb\user														$user
	* @param \phpbb\log														$log
	* @param \phpbb\cache\service										$cache
	* @param \orynider\pafiledb\core\functions_cache				$functions_cache
	* @param \phpbb\db\driver\driver_interface						$db
	* @param \phpbb\request\request		 								$request
	* @param \phpbb\pagination												$pagination
	* @param \phpbb\extension\manager									$ext_manager
	* @param \phpbb\path_helper											$path_helper
	* @param string 																$php_ext
	* @param string 																$root_path
	* @param string 																$custom_header_info
	* @param string 																$custom_header_info_config
	* @param \phpbb\files\factory											$files_factory
	*
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\language\language $language, 
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\log\log $log,
		\phpbb\cache\service $cache,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\pagination $pagination,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		$php_ext, $root_path,
		$custom_header_info_table,
		$custom_header_info_config_table,
		\phpbb\files\factory $files_factory = null)
	{
		$this->config							 			= $config;
		$this->language									= $language;
		$this->template 									= $template;
		$this->user 										= $user;
		$this->log 											= $log;
		$this->cache 										= $cache;
		$this->db 											= $db;
		$this->request 									= $request;
		$this->pagination 								= $pagination;
		$this->ext_manager	 							= $ext_manager;
		$this->path_helper	 							= $path_helper;
		$this->php_ext 									= $php_ext;
		$this->root_path 									= $root_path;
		$this->custom_header_info_table 			= $custom_header_info_table;
		$this->custom_header_info_config_table 	= $custom_header_info_config_table;
		$this->files_factory 		= $files_factory;

		$this->ext_name 		= $this->request->variable('ext_name', 'orynider/custom_headernfo');
		$this->module_root_path	= $this->ext_path = $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web		= $this->path_helper->update_web_root_path($this->module_root_path);

		if (!class_exists('parse_message'))
		{
			include($this->root_path . 'includes/message_parser.' . $this->php_ext);
		}

		global $debug;

		// Read out config values
		//$custom_header_info_config = $this->config_values();
		//$this->backend = $this->confirm_backend();
		//$language->set_default_language($this->user->data['user_lang']);
		//$language->add_lang(array('common', 'acp/common', 'cli'));
		//$user->setup();
		$this->language_from = (isset($this->config['default_lang'])) ? $this->config['default_lang'] : $this->user->lang['USER_LANG'];
		$this->language_into = (isset($this->user->data['user_lang'])) ? $this->user->data['user_lang'] : $this->language_from;
		
		//print_r($custom_header_info_config);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 */
	function handle_thumbnail( $action  = false )
	{

		// =======================================================
		// Request vars
		// =======================================================
		$info_id = $this->request->variable('info_id', 1);
		
		// get languages installed
		//$this->countries = $this->get_countries();

		// get packs installed and init some variables
		$this->packs = $this->load_lang_dirs($this->module_root_path);

		// =======================================================
		// =======================================================
		$sql = 'SELECT *
				FROM ' . $this->custom_header_info_table . '
				WHERE header_info_id = ' . $info_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		$header_info_name = $row['header_info_name'];
		$header_info_desc = $row['header_info_desc'];
		$header_info_longdesc = $row['header_info_longdesc'];
		$header_info_type = $row['header_info_type'];
		$header_info_dir = $row['header_info_dir']; //ext/orynider/custom_headernfo/language/movies/
		$header_info_font = $row['header_info_font'];
		$header_info_font_size = $row['header_info_pixels'];
		$header_info_title_font_size = $row['header_info_title_pixels'];
		$header_info_desc_font_size = $row['header_info_desc_pixels'];
		
		// populate entries (all lang keys)
		$this->language_from = (isset($this->config['default_lang'])) ? $this->config['default_lang'] : $this->user->lang['USER_LANG'];
		$this->language_into = (isset($this->user->data['user_lang'])) ? $this->user->data['user_lang'] : $this->language_from;
		$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : $this->language_from;
		$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : 'en';
		$this->entries = $this->load_lang_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext);

		//die(print_r($this->language_into, true));
		//$row['header_info_desc_colour'] 	= isset($this->user->lang["{$header_info_dir}_colour"]) ? $this->user->lang["{$header_info_dir}_colour"] : $row['header_info_desc_colour'];

		$header_info_title_colour			= isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '';
		$header_info_title_colour_1		= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 1) : '';
		$header_info_title_colour_2		= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 2) : '';
		$header_info_desc_colour			= isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '';
		$header_info_desc_colour_1		= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 1) : '';
		$header_info_desc_colour_2		= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 2) : '';

		$i = 0;
		$pic_title = array();
		$pic_desc = array();
		srand ((float) microtime() * 10000000);

		if ((count($this->entries) == 0) || ($header_info_type != 'language')) 
		{
			$l_keys[0] = $header_info_name;
			$l_values[0] = $header_info_desc;
			
			$l_keys[1] = $header_info_name;
			$l_values[1] = $header_info_longdesc;
			$j = rand(0, 1);
			$pic_title = $l_keys[$j];
			$pic_desc = $l_values[$j];
		}
		else
		{
			$i = count($this->entries);
			$j = rand(0, $i);
			//$j = 4;
			$l_keys = array_keys($this->entries);
			$l_values = array_values($this->entries);
			$pic_title = $l_keys[$j];
			$pic_desc = $l_values[$j];
		}
		//die(print_r($pic_desc, true));
		//$pic_desc = "ויענך וירעבך ויאכלך את המן אשר לא ידעת ולא ידעון אבתיך  למען הודיעך כי לא על הלחם לבדו יחיה האדם—כי על כל מוצא פי יהוה יחיה האדם";

		$header_info_image	= $row['header_info_image'];

		/* * /
		$s_header_info_link_checked	= $row['header_info_link'];
		$header_info_url = $row['header_info_url'];
		$header_info_license	= $row['header_info_license'];
		$header_info_time = $row['header_info_time'];
		$header_info_last = $row['header_info_last'];
		$s_header_info_pin_checked = $row['header_info_pin'];
		$s_header_info_disable	= $row['header_info_disable']; // settings_disable,
		/* */

		$this->db->sql_freeresult($result);

		//
		// Output all
		//
		$board_url = generate_board_url();
		$phpbb_url = $board_url . '/';

		// Replace path by your own font path
		$font_name = $this->request->variable('font', $header_info_font);
		$font = $this->module_root_path . "assets/fonts/" . $font_name . '.ttf';

		if (!is_file($font))
		{
			$font = $this->module_root_path . "assets/fonts/" . $header_info_font; //. '.ttf';
		}

		$header_info_image = $header_info_image ? str_replace('_info.', '_bg.', $header_info_image) : $this->module_root_path . "styles/prosilver/theme/images/banners/custom_header_bg.png";

		$src = str_replace('php', 'png', $header_info_image);
		$src_path = str_replace($phpbb_url, $this->root_path, $header_info_image);
		$pic_filename = basename($src_path);
		$pic_filetype = strtolower(substr($pic_filename, strlen($pic_filename) - 4, 4)); // .png
		$pic_ext = str_replace('jpg', 'jpeg', substr(strrchr($pic_filename, '.'), 1));
		$file_header = 'Content-type: image/' . $pic_ext;
		$pic_title_reg = preg_replace("/[^A-Za-z0-9]/", "_", $pic_title);
		$read_function = 'imagecreatefrom'.$pic_ext;

		$im = $read_function($src_path); //ImageID

		$pic_size = @GetImageSize($src_path);
		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];

		$resize_width = $this->request->variable('resize_width', $pic_width); 
		$resize_height = $this->request->variable('resize_height', $pic_height);

		// Create some colors
		$white = ImageColorAllocate($im, 255, 255, 255); //#ffffff
		$grey = ImageColorAllocate($im, 128, 128, 128); //#808080
		// integer representation of the color black (rgb: 0,0,0)
		$black = $background = ImageColorAllocate($im, 0, 0, 0); //#000000
		//blue: #0000ff or #0c6a99 or #066c9f
		$blue = ImageColorAllocate($im, 6, 108, 159); //#066c9f
		$blure = ImageColorAllocate($im, 29, 36, 52); //#1d2434
		$rand = ImageColorAllocate($im, rand(180, 255), rand(180, 255), rand(180, 255));

		//die(print_r($this->get_hex_colour($white), true));

		/* int ImageColorAllocate(resource $image, int $red, int $green, int $blue) */
		$title_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour, 'r'), $this->get_hexdec_colour($header_info_title_colour, 'g'), $this->get_hexdec_colour($header_info_title_colour, 'b'));
		$title_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_1, 'r'), $this->get_hexdec_colour($header_info_title_colour_1, 'g'), $this->get_hexdec_colour($header_info_title_colour_1, 'b'));
		$title_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_2, 'r'), $this->get_hexdec_colour($header_info_title_colour_2, 'g'), $this->get_hexdec_colour($header_info_title_colour_2, 'b'));

		$desc_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour, 'r'), $this->get_hexdec_colour($header_info_desc_colour, 'g'), $this->get_hexdec_colour($header_info_desc_colour, 'b'));
		$desc_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_1, 'r'), $this->get_hexdec_colour($header_info_desc_colour_1, 'g'), $this->get_hexdec_colour($header_info_desc_colour_1, 'b'));
		$desc_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_2, 'r'), $this->get_hexdec_colour($header_info_desc_colour_2, 'g'), $this->get_hexdec_colour($header_info_desc_colour_2, 'b'));

		/**/
		if (($resize_width !== 0) && ($resize_width !== $pic_width))
		{
			//$resize = $im;
			$resize = ($this->gdVersion() == 1) ? ImageCreate($resize_width, $resize_height) : ImageCreateTrueColor($resize_width, $resize_height);
			$resize_function = ($this->gdVersion() == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			$resize_function($resize, $im, 0, 0, 0, 0, $resize_width, $resize_height, $pic_width, $pic_height);
			ImageDestroy($im);
			$pic_width = $resize_width;
			$pic_height = $resize_height;
			
			$im = $resize;
			
			ImageFilledRectAngle($im, 0, 0, $resize_width, $pic_height, $white);
			
			if (function_exists('imageantialias'))
			{
				ImageAntialias($im, true);
			}
			ImageAlphaBlending($im, false);
			if (function_exists('imagesavealpha'))
			{
				ImageSaveAlpha($im, true);
			}
		}
		else
		{
			if (function_exists('imageantialias'))
			{
				ImageAntialias($im, true);
			}
			ImageAlphaBlending($im, false);
			if (function_exists('imagesavealpha'))
			{
				ImageSaveAlpha($im, true);
			}
		}
		/**/
		$dimension_filesize = @FileSize($src_path);

		$dimension_font = 1;
		$dimension_string = intval($pic_width) . 'x' . intval($pic_height) . '(' . intval($dimension_filesize / 1024) . 'KB)';

		// removing the black from the placeholder
		ImageColorTransparent($im, $background);
		// turning on alpha channel information saving (to ensure the full range
		// of transparency is preserved)
		ImageSaveAlpha($im, true);

		$dimension_height = ImageFontHeight($dimension_font);
		$dimension_width = ImageFontWidth($dimension_font) * mb_strlen($pic_desc, 'utf-8');
		$dimension_x = (($thumbnail_width - $dimension_width) / 2) - mb_strlen($pic_desc, 'utf-8');
		$dimension_y = $thumbnail_height + ((16 - $dimension_height) / 2);

		//ideea: https://stackoverflow.com/a/8187653/9369810
		//credit: https://stackoverflow.com/users/1046402/jeff-wilbert
		$middle_title = mb_strrpos(mb_substr($pic_title, 0, floor(mb_strlen($pic_title) / 2 )), ' ') + 1;
		//$middle = strrpos(substr($pic_desc, 0, floor(strlen($pic_desc) / 2)), ' ') + 4;
		$middle_desc = mb_strrpos(mb_substr($pic_desc, 0, floor(mb_strlen($pic_desc) / 2 )), ' ') + 1;

		$pic_title = $this->convert_encoding($pic_title);
		$pic_desc = $this->convert_encoding($pic_desc); 

		$pic_title1 = $this->convert_encoding(mb_substr($pic_title, 0, $middle_title)); 
		$pic_title2 = $this->convert_encoding(mb_substr($pic_title, $middle_title));

		$pic_desc1 = $this->convert_encoding(mb_substr($pic_desc, 0, $middle_desc)); 
		$pic_desc2 = $this->convert_encoding(mb_substr($pic_desc, $middle_desc)); 

		//$font = $this->module_root_path . "assets/fonts/tituscbz.ttf";
		//imageloadfont($font);
		//die(print_r($blue, true));
		//ImageTtfText($im, 2, 20, $dimension_x, $dimension_y, $blue, 'DejaVuSerif.ttf', $pic_title_reg);
		Header($file_header);

		Header("Expires: Mon, 1, 1999 05:00:00 GMT");
		Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		Header("Cache-Control: no-store, no-cache, must-revalidate");
		Header("Cache-Control: post-check=0, pre-check=0", false);
		Header("Pragma: no-cache");

		//4 x 138 >= 458
		//ImageString (resource 1 $image , int 2 $font , 3 int $x , 4 int $y , string 5 $string , int 6 $color)
		//ImageTtfText (resource 1 $image , float 2 $size ie 18, float 3 $angle ie 0, int 4 $x , int 5 $y , int 6 $color , string 7 $fontfile , string 8 $text)
		if (((6 * mb_strlen($pic_desc, 'utf-8')) >= $resize_width) || (mb_strlen($pic_desc2, 'utf-8') >= $resize_width))
		{
			//ImageString($im, 2, 10, $dimension_y, $pic_desc1, $blue);
			ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1);
			//ImageString($im, 2, 10, 36, $pic_desc2, $blue);
			ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2);
		}
		else
		{
			//ImageString($im, 2, 10, $dimension_y, $pic_desc, $blue);
			ImageTtfText($im, $header_info_font_size, 0, 10, $dimension_y + 10, $desc_colour, $font, $pic_desc);
		}

		/* return with no uppercase if patern not in string */
		if (strpos($pic_title, ',') !== false)
		{
			$header_info_title_font_size = $header_info_title_font_size - 2;
		}
	
		//ImageString($im, 2, 20, 17, $pic_desc, $blue);
		//$bbox = ImageTtfBBox(20, 0, $font , $pic_title);
		//$textWidth = $bbox[2] - $bbox[0];
		if (((6 * mb_strlen($pic_title, 'utf-8')) >= $resize_width) || (mb_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			//ImageString($im, 2, 10, $dimension_y, $pic_desc1, $blue);
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + 8, $title_colour, $font, $pic_title1);
			//ImageString($im, 2, 10, 36, $pic_desc2, $blue);
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + 43, $title_colour, $font, $pic_title2);
		}
		else
		{
			//ImageString($im, 2, 10, $dimension_y, $pic_desc, $blue);
		// Add some shadow to the text
			ImageTtfText($im,  $header_info_title_font_size, 0, 11, $dimension_y + 28, $grey, $font, $pic_title);

			// Add the text
			ImageTtfText($im, $header_info_title_font_size, 0, 10, $dimension_y + 29, $title_colour, $font, $pic_title);
		}
		
		ImagePNG($im);
		//ImageDestroy($im);
		exit;
	}

	/**
	* Description: converting a color string
	*
	* by orynider (c) 2019
	*
	* @return $r color string;
	* @return $b color string;
	* @return $g color string;
	* @return $dec_colour dec string;
	* @return $hex_colour;
	* @return $hex_colour hex string
	* @return $dec_colour array(r, b, g)
	* @access ?
	*/
	function get_hexdec_colour($hex_colour, $offset)
	{
		$dec_colour = array();
		
		//Check if first character of hex colour
		if ((int) ord(substr($hex_colour, 1, 1)) > 57)
		{
			//array conversion switch
			switch($hex_colour)
			{
				case 'white': 
					$hex_colour = "#ffffff";
				break;
				case 'red': 
					$hex_colour = "#ff0000";
				break;
			    case 'orange': 
					$hex_colour = "#ffa500";
				break;
				case 'yellow': 
					$hex_colour = "#ffff00"; 
				break;
			    case 'fuchsia': 
					$hex_colour = "#ff00ff";
				break;
			    case 'green': 
					$hex_colour = "#008000";
				break;
			    case 'grey': 
					$hex_colour = "#808080";
				break;
			    case 'lime': 
					$hex_colour = "#00ff00";
				break;
			    case 'maroon': 
					$hex_colour = "#800000";
				break;
			    case 'navy': 
					$hex_colour = "#000080";
				break;
			    case 'olive': 
					$hex_colour = "#808000";
				break;
			    case 'purple': 
					$hex_colour = "#800080";
				break;
				case 'aqua': 
					$hex_colour = "#00ffff";
				break;
			    case 'black': 
					$hex_colour = "#000000";
				break;
			    case 'blue': 
					$hex_colour = "#0000ff";
				break;
			    case 'silver': 
					$hex_colour = "#c0c0c0";
				break;
			    case 'teal': 
					$hex_colour = "#008080";
				break;
				default:
					$hex_colour = "#12a3eb";
				break;
			}
			
		}
		
		list($r, $g, $b) = sscanf($hex_colour, "#%02x%02x%02x");
		
		$dec_colour['r'] = $r;
		$dec_colour['b'] = $b;
		$dec_colour['g'] = $g;
		
		switch($offset)
		{
			case 'r': 
				return $r;
			break;
			case 'b': 
				return $b;
			break;
			case 'g': 
				return $g;
			break;
			case 'd': 
				return hexdec(ltrim($hex_colour, '#'));
			break;
			case 'h': 
				return $hex_colour;
			break;
			default:
				return $dec_colour;
			break;
		}
	}

	/**
	* Based on get_hex_colour() by david63
	* Ported by orynider in 2019
	* Description:
	* Get a offset color we need for a gradient
	* Uses about same offset as prosilver
	*
	* @return $offset_colour hex colour
	* @access ?
	*/
	function get_gradient_colour($header_colour, $offset)
	{
		//Check if first character of hex colour
		if ((int) ord(substr($header_colour, 1, 1)) > 57)
		{
			$offset_colour = $header_colour;
		}
		else
		{
			$header_colour	= hexdec(ltrim($header_colour, '#'));
			$offset_colour		= '#' . dechex(($offset == 1) ? $header_colour + 5778196 : $header_colour - 1191226);
		}
		return $offset_colour;
	}

	/**
	* Based on get_hex_colour() by david63
	* Ported by orynider in 2019
	* Description:
	* Get the hex color of a decimal color
	* Uses about same offset as prosilver
	*
	* @return $hex_colour hex colour
	* @access ?
	*/
	function get_hex_colour($dec_colour)
	{
		//Check first character of number if is not an english color
		if ((int) ord(substr($dec_colour, 1, 1)) > 57)
		{
			$hex_colour = $this->get_hexdec_colour($dec_colour, 'h');
		}
		else
		{
			$hex_numb		= dechex($dec_colour);
			switch(strlen($hex_numb))
			{
				case 3:
					$hex_colour	= '#000' . $hex_numb;
				break;
				case 4:
					$hex_colour	= '#00' . $hex_numb;
				break;
				case 5:
					$hex_colour	= '#0' . $hex_numb;
				break;
				default:
					$hex_colour	= '#' . $hex_numb;
				break;
			}
		}
		return $hex_colour;
	}

	/**
	 *
	 * Add here conversion code for 
	 * preparing text for php_gd2 function ImageTtfText()
	 *
	* @return $out
	 */
	function convert_encoding($text)
	{
		/* */
		//die(print_r($this->language_into, true));
		// Convert UTF-8 string to HTML entities
		//$text = mb_convert_encoding($text, 'HTML-ENTITIES',"UTF-8");
		//$opts = array('http' => array('header' => 'Accept-Charset: windows-1255,utf-8;q=0.7,*;q=0.7'));
		//$context = stream_context_create($opts);
		//$content = file_get_contents('my_url', false, $context);
		//$text = iconv("UTF-8", "cp1255", $text);
		// Convert HTML entities into ISO-8859-1
		//$text = html_entity_decode($text, ENT_NOQUOTES, "ISO-8859-1");
		/* */
		
		//Reverse string for RTL languages 
		switch($this->language_into)
		{
			case 'he':
			case 'ar':
				preg_match_all('/./us' , $text, $rtl);
				$text = join('' , array_reverse($rtl[0])); 
			break;
			
			default:
			break;
		}
		
		return $text;
	}
	
	/**
	 * utf8 strlen
	 *
	* @return $return
	 */
	function utf8_strlen($text) 
	{
		if (function_exists('mb_strlen'))
		{
			return mb_strlen($text, 'utf-8');
		}
		else
		{
			return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $text, $return);
		}
	}
	
	/**
	 * Confirm Forum Backend Name
	 *
	* @return $backend
	 */
	function confirm_backend($backend_name = true)
	{
		if (isset($this->config['version'])) 
		{
			if ($this->config['version']  >= '4.0.0')
			{
				$this->backend = 'phpbb4';
			}
			if (($this->config['version']  >= '3.3.0') && ($this->config['version'] < '4.0.0'))
			{
				$this->backend = 'proteus';
			}
			if (($this->config['version']  >= '3.2.0') && ($this->config['version'] < '3.3.0'))
			{
				$this->backend = 'rhea';
			}
			if (($this->config['version']  >= '3.1.0') && ($this->config['version'] < '3.2.0'))
			{
				$this->backend = 'ascraeus';
			}
			if (($this->config['version']  >= '3.0.0') && ($this->config['version'] < '3.1.0'))
			{
				$this->backend = 'olympus';
			}
			if (($this->config['version']  >= '2.0.0') && ($this->config['version'] < '3.0.0'))
			{
				$this->this->backend = 'phpbb2';
			}
			if (($this->config['version']  >= '1.0.0') && ($this->config['version'] < '2.0.0'))
			{
				$this->backend = 'phpbb';
			}
		}
		else if (isset($this->config['portal_backend']))
		{
			$this->backend = $this->config['portal_backend'];
		}
		else
		{
			$this->backend = 'internal';
		}
		
		$this->is_phpbb20	= phpbb_version_compare($this->config['version'], '2.0.0@dev', '>=') && phpbb_version_compare($this->config['version'], '3.0.0@dev', '<');		
		$this->is_phpbb30	= phpbb_version_compare($this->config['version'], '3.0.0@dev', '>=') && phpbb_version_compare($this->config['version'], '3.1.0@dev', '<');		
		$this->is_phpbb31	= phpbb_version_compare($this->config['version'], '3.1.0@dev', '>=') && phpbb_version_compare($this->config['version'], '3.2.0@dev', '<');
		$this->is_phpbb32	= phpbb_version_compare($this->config['version'], '3.2.0@dev', '>=') && phpbb_version_compare($this->config['version'], '3.3.0@dev', '<');		
		$this->is_phpbb33	= phpbb_version_compare($this->config['version'], '3.3.0@dev', '>=') && phpbb_version_compare($this->config['version'], '3.4.0@dev', '<');		
		
		$this->is_block = isset($this->config['portal_backend']) ? true : false;
		
		if ($this->config['version'] < '3.1.0')
		{
			define('EXT_TABLE',	$table_prefix . 'ext');
		}
		
		if ($backend_name == true)
		{
			return $this->backend;
		}
	}
	

	
	function get_lang($key)
	{
		return ((!empty($key) && isset($this->user->lang[$key])) ? $this->user->lang[$key] : $key);
	}
	
	/*
	* Function to find version (1 or 2) of the GD extension.
	*   Usage : gdVersion()
	*   Returns : version number as integer
	* Ported by orynider from mx_smartor and MG's Full Albun Pack
	*/
	function gdVersion($user_ver = 0)
	{
		if (! extension_loaded('gd'))
		{
			return;
		}
		static $gd_ver = 0;
		if ($user_ver == 1)
		{
			$gd_ver = 1; return 1;
		}
		if ($user_ver !=2 && $gd_ver > 0 )
		{
			return $gd_ver;
		}
		if (function_exists('gd_info'))
		{
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];
			return $match[0];
		}
		if (preg_match('/phpinfo/', ini_get('disable_functions')))
		{
			if ($user_ver == 2)
			{
				$gd_ver = 2;
				return 2;
			}
			else
			{
				$gd_ver = 1;
				return 1;
			}
		}
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];
		return $match[0];
	}
	

	function get_countries()
	{
		if (count($this->language_list))
		{
			return $this->language_list;
		}
		// get all countries installed
		$countries = array();
		$dir = @opendir($this->module_root_path . 'language');
		while ($file = @readdir($dir))
		{
			$f = trim(str_replace('lang_', '', $file));
			
			if (($f == '.' || $f == '..') || !is_dir($this->module_root_path . 'language/' . $f) || is_link($this->module_root_path . 'language/' . $file))
			{
				continue;
			}
			
			$this->module_language_list[$f] = $countries[$file] = $this->ucstrreplace("lang_", '', $f);
		}
		@closedir($dir);
		@asort($countries);

		return $countries;
	}
	

	
	function load_lang_dirs($path, $lang_from = '', $add_path = '', $lang_into = '')
	{
		global $countries;
		
		$this->language_from = (isset($this->config['default_lang'])) ? $this->config['default_lang'] : 'en';
		$this->language_into	= (isset($user->lang['USER_LANG'])) ? $user->lang['USER_LANG'] : $this->language_from;
		
		if (($this->dir_select_from == $this->dir_select_into) && ($this->language_from !== $this->language_into))
		{
			$this->dir_select_from = str_replace($this->language_into, $this->language_from, $this->dir_select_from);
			$this->dir_select_from = ($this->trisstr('\.' . $this->php_ext . '$', $this->dir_select_from) == false) ? $this->dir_select_from : dirname($this->dir_select_from);
			$this->dir_select_into = ($this->trisstr('\.' . $this->php_ext . '$', $this->dir_select_into) == false) ? $this->dir_select_into : dirname($this->dir_select_into);
		}
		
		/* MG Lang DB - BEGIN */
		$skip_files = array(('lang_bbcode.' . $this->php_ext), ('lang_faq.' . $this->php_ext), ('lang_rules.' . $this->php_ext));
		/* MG Lang DB - END */

		// get all the extensions installed
		$lang_dirs = array();
		
		@reset($countries);
		
		/* root path at witch we add ie. extension path */  
		$root_path = $this->module_root_path;
		
		$php_ext = $this->php_ext;
		if (!file_exists($root_path . 'mx_meta.inc') && !file_exists($root_path . 'modcp'.$php_ext))
		{
			$language = $this->encode_lang($language);
			if ($this->language_from == '')
			{
				$this->language_from = 'en';
			}
		}
		if ($this->language_from == '')
		{
			return null;
		}
		
		$lang_dirs = array();
		$folder_path = $folder_from = $path . 'language/' . $this->language_from;
		$folder_into = $path . 'language/' . $this->language_into;
		$subdir_select_from = $this->dir_select_from;
		$subdir_select_into = $this->dir_select_into;
		$subdirs = glob($folder_from . '/*' , GLOB_ONLYDIR);

		if (!is_dir($folder_path . '/'))
		{
			$dir = 'Resource id #53'.'Resource id #54'.'Resource id #55'.'Resource id #56'.'Resource id #57'.'Resource id #58';
			return false;
		}
		else
		{
			$dir = opendir($folder_path);
		}

		while($file = @readdir($dir))
		{
			if ( $file == '.' || $file == '..' || $file == 'CVS')
			{
				continue;
			}
			
			if (is_dir($folder_path . '/' . $file))
			{
				$lang_dirs[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
				$sub_dirs = $this->load_lang_dirs($folder_path, $language, $add_path . '/'. $file);
				$lang_dirs = is_array($sub_dirs) ? array_merge($lang_dirs, $sub_dirs) : $lang_dirs;
			}
		}
		@closedir($dir);
		
		if (is_dir($subdir_select_from . '/') && is_array($subdirs))
		{
			$subdir = opendir($subdir_select_from);
		}
		
		while($file = @readdir($subdir))
		{
			if ($file == '.' || $file == '..' || $file == 'CVS')
			{
				continue;
			}
			
			if(is_dir($subdir_select_from . '/' . $file))
			{
				$sub_dirs[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
				$lang_dirs = array_merge($lang_dirs, $sub_dirs);
			}
		}
		@closedir($subdir);
		
		@asort($lang_dirs);

		return $lang_dirs;
	}

	function read_one_pack($country_dir, $pack_dir, &$entries, $pack_file_name = 'common')
	{
		$countries = $this->countries; 
		$packs = $this->packs;

		// get filename
		$pack_file =  $pack_file_name . '.' . $this->php_ext;
		$file = $this->module_root_path . 'language/' . $country_dir . '/' . $pack_dir . '/' . $pack_file;

		// process first admin then standard keys
		for ($i = 0; $i < 2; $i++)
		{
			$lang_extend_admin = ($i == 0);

			/* MG Lang DB - BEGIN */
			// fix the filename for standard keys
			if ($pack_file == 'lang')
			{
				$file = $this->module_root_path . 'language/' . $country_dir . '/' . ($lang_extend_admin ? 'lang_admin.' : 'lang_main.') . $this->php_ext;
			}
			// fix the filename for custom keys
			if ($pack_file == 'common')
			{
				$file = $this->module_root_path . 'language/' . $country_dir . '/' . 'common' . $this->php_ext;
			}
			/* MG Lang DB - END */

			// process
			$lang = array();
			@include($file);
			@reset($lang);
			while (list($key_main, $data) = @each($lang))
			{
				$custom = ($pack_file == 'common');
				$first = !is_array($data);
				while ((is_array($data) && (list($key_sub, $value) = @each($data))) || $first)
				{
					$first = false;
					if (!is_array($data))
					{
						$key_sub = '';
						$value = $data;
					}
					$pack = $pack_file;
					$original = '';
					if ($custom && isset($entries['pack'][$key_main][$key_sub]))
					{
						$pack = $entries['pack'][$key_main][$key_sub];
						$original = $entries['pack'][$key_main][$key_sub][$country_dir];
					}
					$entries['pack'][$key_main][$key_sub] = $pack;
					$entries['value'][$key_main][$key_sub][$country_dir] = $value;
					$entries['original'][$key_main][$key_sub][$country_dir] = $original;
					$entries['admin'][$key_main][$key_sub] = $lang_extend_admin;
					// status : 0 = original, 1 = modified, 2 = added
					$entries['status'][$key_main][$key_sub][$country_dir] = (!$custom ? 0 : (($pack != $pack_file) ? 1 : 2));
					
					$this->entries = $entries;
				}
			}
		}
	}
	

	
	function get_entries($modified = true)
	{
		$config = $this->config;
		$countries = $this->countries;
		$packs = $this->packs;

		// init
		$entries = array();

		// process by countries first
		/* MG Lang DB - BEGIN */
		/*
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			// phpBB lang keys
			$pack_file = 'lang';
			$this->read_one_pack($country_dir, $pack_file, $entries);
		}

		// process other packs except custom one
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			@reset($packs);
			while (list($pack_file, $pack_name) = @each($packs))
			{
				if (($pack_file != 'lang') && ($pack_file != 'custom'))
				{
					$this->read_one_pack($country_dir, $pack_file, $entries);
				}
			}
		}
		*/
		/* MG Lang DB - END */

		/* MG Lang DB - BEGIN */
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			@reset($packs);
			while (list($pack_file, $pack_name) = @each($packs))
			{
				$this->read_one_pack($country_dir, $pack_file, $entries);
			}
		}
		/* MG Lang DB - END */

		// process the added/modified keys
		if ($modified)
		{
			/* MG Lang DB - BEGIN */
			@reset($countries);
			while (list($country_dir, $country_name) = @each($countries))
			{
				$pack_file = 'common';
				$this->read_one_pack($country_dir, $pack_file, $entries);
			}
			/* MG Lang DB - END */

			// add the missing keys in a language
			$default_lang = $config['default_lang'];
			$english_lang = 'en';
			@reset($entries['pack']);
			while (list($key_main, $data) = @each($entries['pack']))
			{
				@reset($data);
				while (list($key_sub, $pack_file) = @each($data))
				{
					// add the key to the default lang if missing by using the english one
					if (!isset($entries['value'][$key_main][$key_sub][$default_lang]))
					{
						// add the key to english lang if missing
						if (!isset($entries['value'][$key_main][$key_sub][$english_lang]))
						{
							// find the first not empty value
							$found = false;
							$new_value = '';
							@reset($entries['value'][$key_main][$key_sub]);
							while (list($country_dir, $value) = @each($entries['value'][$key_main][$key_sub]))
							{
								$found = !empty($value);
								if ($found)
								{
									$new_value = $value;
								}
							}
							// add it (even if empty)
							$entries['value'][$key_main][$key_sub][$english_lang] = $new_value;
							$entries['status'][$key_main][$key_sub][$english_lang] = 2; // 2=added
						}

						// fill the default lang
						if ($default_lang!= $english_lang)
						{
							$entries['value'][$key_main][$key_sub][$default_lang] = $entries['value'][$key_main][$key_sub][$english_lang];
							$entries['status'][$key_main][$key_sub][$default_lang] = 2; // 2=added
						}
					}

					// process all langs for this key
					@reset($countries);
					while (list($country_dir, $country_name) = @each($countries))
					{
						if (!isset($entries['value'][$key_main][$key_sub][$country_dir]))
						{
							$entries['value'][$key_main][$key_sub][$country_dir] = $entries['value'][$key_main][$key_sub][$default_lang];
							$entries['status'][$key_main][$key_sub][$country_dir] = 2; // 2=added
						}
					}
				}
			}
		}

		// all is done : return the result
		return $entries;
	}

	function load_lang_file($filename)
	{
		if (!is_file($filename))
		{
			return array();
		}
		include($filename);
		return $lang;
	}

	/**
	 * encode_lang
	 *
	 * $default_lang = $mxp_translator->encode_lang($config['default_lang']);
	 *
	 * @param unknown_type $lang
	 * @return unknown
	 */
	function encode_lang($lang)
	{
			if ($this->backend == 'phpbb2')
			{
				return $lang;
			}
			else
			{
				$lang = str_replace('lang_', '', $lang);
			}			
			switch($lang)
			{
				case 'afar':
					$lang_name = 'aa';
				break;
				case 'abkhazian':
					$lang_name = 'ab';
				break;
				case 'avestan':
					$lang_name = 'ae';
				break;
				case 'afrikaans':
					$lang_name = 'af';
				break;
				case 'akan':
					$lang_name = 'ak';
				break;
				case 'amharic':
					$lang_name = 'am';
				break;
				case 'aragonese':
					$lang_name = 'an';
				break;
				case 'arabic':
					$lang_name = 'ar';
				break;
				case 'assamese':
					$lang_name = 'as';
				break;
				case 'avaric':
					$lang_name = 'av';
				break;
				case 'aymara':
					$lang_name = 'ay';
				break;
				case 'azerbaijani':
					$lang_name = 'az';
				break;
				case 'bashkir':
					$lang_name = 'ba';
				break;
				case 'belarusian':
					$lang_name = 'be';
				break;
				case 'bulgarian':
					$lang_name = 'bg';
				break;
				case 'bihari':
					$lang_name = 'bh';
				break;
				case 'bislama':
					$lang_name = 'bi';
				break;
				case 'bambara':
					$lang_name = 'bm';
				break;
				case 'bengali':
					$lang_name = 'bn';
				break;
				case 'tibetan':
					$lang_name = 'bo';
				break;
				case 'breton':
					$lang_name = 'br';
				break;
				case 'bosnian':
					$lang_name = 'bs';
				break;
				case 'catalan':
					$lang_name = 'ca';
				break;
				case 'chechen':
					$lang_name = 'ce';
				break;
				case 'chamorro':
					$lang_name = 'ch';
				break;
				case 'corsican':
					$lang_name = 'co';
				break;
				case 'cree':
					$lang_name = 'cr';
				break;
				case 'czech':
					$lang_name = 'cs';
				break;
				case 'slavonic':
					$lang_name = 'cu';
				break;
				case 'chuvash':
					$lang_name = 'cv';
				break;
				case 'welsh_cymraeg':
					$lang_name = 'cy';
				break;
				case 'danish':
					$lang_name = 'da';
				break;
				case 'german':
					$lang_name = 'de';
				break;
				case 'divehi':
					$lang_name = 'dv';
				break;
				case 'dzongkha':
					$lang_name = 'dz';
				break;
				case 'ewe':
					$lang_name = 'ee';
				break;
				case 'greek':
					$lang_name = 'el';
				break;
				case 'hebrew':
					$lang_name = 'he';
				break;
				case 'english':
					$lang_name = 'en';
				break;
				case 'english_us':
					$lang_name = 'en_us';
				break;
				case 'esperanto':
					$lang_name = 'eo';
				break;
				case 'spanish':
					$lang_name = 'es';
				break;
				case 'estonian':
					$lang_name = 'et';
				break;
				case 'basque':
					$lang_name = 'eu';
				break;
				case 'persian':
					$lang_name = 'fa';
				break;
				case 'fulah':
					$lang_name = 'ff';
				break;
				case 'finnish':
					$lang_name = 'fi';
				break;
				case 'fijian':
					$lang_name = 'fj';
				break;
				case 'faroese':
					$lang_name = 'fo';
				break;
				case 'french':
					$lang_name = 'fr';
				break;
				case 'frisian':
					$lang_name = 'fy';
				break;
				case 'irish':
					$lang_name = 'ga';
				break;
				case 'scottish':
					$lang_name = 'gd';
				break;
				case 'galician':
					$lang_name = 'gl';
				break;
				case 'guaraní':
					$lang_name = 'gn';
				break;
				case 'gujarati':
					$lang_name = 'gu';
				break;
				case 'manx':
					$lang_name = 'gv';
				break;
				case 'hausa':
					$lang_name = 'ha';
				break;
				case 'hebrew':
					$lang_name = 'he';
				break;
				case 'hindi':
					$lang_name = 'hi';
				break;
				case 'hiri_motu':
					$lang_name = 'ho';
				break;
				case 'croatian':
					$lang_name = 'hr';
				break;
				case 'haitian':
					$lang_name = 'ht';
				break;
				case 'hungarian':
					$lang_name = 'hu';
				break;
				case 'armenian':
					$lang_name = 'hy';
				break;
				case 'herero':
					$lang_name = 'hz';
				break;
				case 'interlingua':
					$lang_name = 'ia';
				break;
				case 'indonesian':
					$lang_name = 'id';
				break;
				case 'interlingue':
					$lang_name = 'ie';
				break;
				case 'igbo':
					$lang_name = 'ig';
				break;
				case 'sichuan_yi':
					$lang_name = 'ii';
				break;
				case 'inupiaq':
					$lang_name = 'ik';
				break;
				case 'ido':
					$lang_name = 'io';
				break;
				case 'icelandic':
					$lang_name = 'is';
				break;
				case 'italian':
					$lang_name = 'it';
				break;
				case 'inuktitut':
					$lang_name = 'iu';
				break;
				case 'japanese':
					$lang_name = 'ja';
				break;
				case 'javanese':
					$lang_name = 'jv';
				break;
				case 'georgian':
					$lang_name = 'ka';
				break;
				case 'kongo':
					$lang_name = 'kg';
				break;
				case 'kikuyu':
					$lang_name = 'ki';
				break;
				case 'kwanyama':
					$lang_name = 'kj';
				break;
				case 'kazakh':
					$lang_name = 'kk';
				break;
				case 'kalaallisut':
					$lang_name = 'kl';
				break;
				case 'khmer':
					$lang_name = 'km';
				break;
				case 'kannada':
					$lang_name = 'kn';
				break;
				case 'korean':
					$lang_name = 'ko';
				break;
				case 'kanuri':
					$lang_name = 'kr';
				break;
				case 'kashmiri':
					$lang_name = 'ks';
				break;
				case 'kurdish':
					$lang_name = 'ku';
				break;
				case 'kv':
					$lang_name = 'komi';
				break;
				case 'cornish_kernewek':
					$lang_name = 'kw';
				break;
				case 'kirghiz':
					$lang_name = 'ky';
				break;
				case 'latin':
					$lang_name = 'la';
				break;
				case 'luxembourgish':
					$lang_name = 'lb';
				break;
				case 'ganda':
					$lang_name = 'lg';
				break;
				case 'limburgish':
					$lang_name = 'li';
				break;
				case 'lingala':
					$lang_name = 'ln';
				break;
				case 'lao':
					$lang_name = 'lo';
				break;
				case 'lithuanian':
					$lang_name = 'lt';
				break;
				case 'luba-katanga':
					$lang_name = 'lu';
				break;
				case 'latvian':
					$lang_name = 'lv';
				break;
				case 'malagasy':
					$lang_name = 'mg';
				break;
				case 'marshallese':
					$lang_name = 'mh';
				break;
				case 'maori':
					$lang_name = 'mi';
				break;
				case 'macedonian':
					$lang_name = 'mk';
				break;
				case 'malayalam':
					$lang_name = 'ml';
				break;
				case 'mongolian':
					$lang_name = 'mn';
				break;
				case 'moldavian':
					$lang_name = 'mo';
				break;
				case 'marathi':
					$lang_name = 'mr';
				break;
				case 'malay':
					$lang_name = 'ms';
				break;
				case 'maltese':
					$lang_name = 'mt';
				break;
				case 'burmese':
					$lang_name = 'my';
				break;
				case 'nauruan':
					$lang_name = 'na';
				break;
				case 'norwegian':
					$lang_name = 'nb';
				break;
				case 'ndebele':
					$lang_name = 'nd';
				break;
				case 'nepali':
					$lang_name = 'ne';
				break;
				case 'ndonga':
					$lang_name = 'ng';
				break;
				case 'dutch':
					$lang_name = 'nl';
				break;
				case 'norwegian_nynorsk':
					$lang_name = 'nn';
				break;
				case 'norwegian':
					$lang_name = 'no';
				break;
				case 'southern_ndebele':
					$lang_name = 'nr';
				break;
				case 'navajo':
					$lang_name = 'nv';
				break;
				case 'chichewa':
					$lang_name = 'ny';
				break;
				case 'occitan':
					$lang_name = 'oc';
				break;
				case 'ojibwa':
					$lang_name = 'oj';
				break;
				case 'oromo':
					$lang_name = 'om';
				break;
				case 'oriya':
					$lang_name = 'or';
				break;
				case 'ossetian':
					$lang_name = 'os';
				break;
				case 'punjabi':
				case 'panjabi':
				case 'gurmiki':				
					$lang_name = 'pa';
				break;
				case 'pali':
					$lang_name = 'pi';
				break;
				case 'polish':
					$lang_name = 'pl';
				break;
				case 'pashto':
					$lang_name = 'ps';
				break;
				case 'portuguese':
					$lang_name = 'pt';
				break;
				case 'portuguese_brasil':
					$lang_name = 'pt_br';
				break;
				case 'quechua':
					$lang_name = 'qu';
				break;
				case 'romansh':
					$lang_name = 'rm';
				break;
				case 'kirundi':
					$lang_name = 'rn';
				break;
				case 'romanian':
					$lang_name = 'ro';
				break;
				case 'russian':
					$lang_name = 'ru';
				break;
				case 'kinyarwanda':
					$lang_name = 'rw';
				break;
				case 'sanskrit':
					$lang_name = 'sa';
				break;
				case 'sardinian':
					$lang_name = 'sc';
				break;
				case 'sindhi':
					$lang_name = 'sd';
				break;
				case 'northern_sami':
					$lang_name = 'se';
				break;
				case 'sango':
					$lang_name = 'sg';
				break;
				case 'serbo-croatian':
					$lang_name = 'sh';
				break;
				case 'sinhala':
					$lang_name = 'si';
				break;
				case 'slovak':
					$lang_name = 'sk';
				break;
				case 'slovenian':
					$lang_name = 'sl';
				break;
				case 'samoan':
					$lang_name = 'sm';
				break;
				case 'shona':
					$lang_name = 'sn';
				break;
				case 'somali':
					$lang_name = 'so';
				break;
				case 'albanian':
					$lang_name = 'sq';
				break;
				case 'serbian':
					$lang_name = 'sr';
				break;
				case 'swati':
					$lang_name = 'ss';
				break;
				case 'sotho':
					$lang_name = 'st';
				break;
				case 'sundanese':
					$lang_name = 'su';
				break;
				case 'swedish':
					$lang_name = 'sv';
				break;
				case 'swahili':
					$lang_name = 'sw';
				break;
				case 'tamil':
					$lang_name = 'ta';
				break;
				case 'telugu':
					$lang_name = 'te';
				break;
				case 'tajik':
					$lang_name = 'tg';
				break;
				case 'thai':
					$lang_name = 'th';
				break;
				case 'tigrinya':
					$lang_name = 'ti';
				break;
				case 'turkmen':
					$lang_name = 'tk';
				break;
				case 'tagalog':
					$lang_name = 'tl';
				break;
				case 'tswana':
					$lang_name = 'tn';
				break;
				case 'tonga':
					$lang_name = 'to';
				break;
				case 'turkish':
					$lang_name = 'tr';
				break;
				case 'tsonga':
					$lang_name = 'ts';
				break;
				case 'tatar':
					$lang_name = 'tt';
				break;
				case 'twi':
					$lang_name = 'tw';
				break;
				case 'tahitian':
					$lang_name = 'ty';
				break;
				case 'uighur':
					$lang_name = 'ug';
				break;
				case 'ukrainian':
					$lang_name = 'uk';
				break;
				case 'urdu':
					$lang_name = 'ur';
				break;
				case 'uzbek':
					$lang_name = 'uz';
				break;
				case 'venda':
					$lang_name = 've';
				break;
				case 'vietnamese':
					$lang_name = 'vi';
				break;
				case 'volapuk':
					$lang_name = 'vo';
				break;
				case 'walloon':
					$lang_name = 'wa';
				break;
				case 'wolof':
					$lang_name = 'wo';
				break;
				case 'xhosa':
					$lang_name = 'xh';
				break;
				case 'yiddish':
					$lang_name = 'yi';
				break;
				case 'yoruba':
					$lang_name = 'yo';
				break;
				case 'zhuang':
					$lang_name = 'za';
				break;
				case 'chinese':
					$lang_name = 'zh';
				break;
				case 'chinese_simplified':
					$lang_name = 'zh_cmn_hans';
				break;
				case 'chinese_traditional':
					$lang_name = 'zh_cmn_hant';
				break;
				case 'zulu':
					$lang_name = 'zu';
				break;
				default:
					$lang_name = (strlen($lang) > 2) ? substr($lang, 0, 2) : $lang;
				break;
			}
		return $lang_name;
	}
	
	function ucstrreplace($pattern = '%{$regex}%i', $matches = '', $string) 
	{
		/* return with no uppercase if patern not in string */
		if (strpos($string, $pattern) === false)
		{
			/* known languages */
			switch($string)
			{
				case 'aa':
					$lang_name = 'afar';
				break;
				case 'ab':
					$lang_name = 'abkhazian';
				break;
				case 'ae':
					$lang_name = 'avestan';
				break;
				case 'af':
					$lang_name = 'afrikaans';
				break;
				case 'ak':
					$lang_name = 'akan';
				break;
				case 'am':
					$lang_name = 'amharic';
				break;
				case 'an':
					$lang_name = 'aragonese';
				break;
				case 'ar':
					$lang_name = 'arabic';
				break;
				case 'as':
					$lang_name = 'assamese';
				break;
				case 'av':
					$lang_name = 'avaric';
				break;
				case 'ay':
					$lang_name = 'aymara';
				break;
				case 'az':
					$lang_name = 'azerbaijani';
				break;
				case 'ba':
					$lang_name = 'bashkir';
				break;
				case 'be':
					$lang_name = 'belarusian';
				break;
				case 'bg':
					$lang_name = 'bulgarian';
				break;
				case 'bh':
					$lang_name = 'bihari';
				break;
				case 'bi':
					$lang_name = 'bislama';
				break;
				case 'bm':
					$lang_name = 'bambara';
				break;
				case 'bn':
					$lang_name = 'bengali';
				break;
				case 'bo':
					$lang_name = 'tibetan';
				break;
				case 'br':
					$lang_name = 'breton';
				break;
				case 'bs':
					$lang_name = 'bosnian';
				break;
				case 'ca':
					$lang_name = 'catalan';
				break;
				case 'ce':
					$lang_name = 'chechen';
				break;
				case 'ch':
					$lang_name = 'chamorro';
				break;
				case 'co':
					$lang_name = 'corsican';
				break;
				case 'cr':
					$lang_name = 'cree';
				break;
				case 'cs':
					$lang_name = 'czech';
				break;
				case 'cu':
					$lang_name = 'slavonic';
				break;
				case 'cv':
					$lang_name = 'chuvash';
				break;
				case 'cy':
					$lang_name = 'welsh_cymraeg';
				break;
				case 'da':
					$lang_name = 'danish';
				break;
				case 'de':
					$lang_name = 'german';
				break;
				case 'dv':
					$lang_name = 'divehi';
				break;
				case 'dz':
					$lang_name = 'dzongkha';
				break;
				case 'ee':
					$lang_name = 'ewe';
				break;
				case 'el':
					$lang_name = 'greek';
				break;
				case 'he':
					$lang_name = 'hebrew';
				break;
				case 'en':
					$lang_name = 'english';
				break;
				case 'en_us':
					$lang_name = 'english';
				break;
				case 'eo':
					$lang_name = 'esperanto';
				break;
				case 'es':
					$lang_name = 'spanish';
				break;
				case 'et':
					$lang_name = 'estonian';
				break;
				case 'eu':
					$lang_name = 'basque';
				break;
				case 'fa':
					$lang_name = 'persian';
				break;
				case 'ff':
					$lang_name = 'fulah';
				break;
				case 'fi':
					$lang_name = 'finnish';
				break;
				case 'fj':
					$lang_name = 'fijian';
				break;
				case 'fo':
					$lang_name = 'faroese';
				break;
				case 'fr':
					$lang_name = 'french';
				break;
				case 'fy':
					$lang_name = 'frisian';
				break;
				case 'ga':
					$lang_name = 'irish';
				break;
				case 'gd':
					$lang_name = 'scottish';
				break;
				case 'gl':
					$lang_name = 'galician';
				break;
				case 'gn':
					$lang_name = 'guaraní';
				break;
				case 'gu':
					$lang_name = 'gujarati';
				break;
				case 'gv':
					$lang_name = 'manx';
				break;
				case 'ha':
					$lang_name = 'hausa';
				break;
				case 'he':
					$lang_name = 'hebrew';
				break;
				case 'hi':
					$lang_name = 'hindi';
				break;
				case 'ho':
					$lang_name = 'hiri_motu';
				break;
				case 'hr':
					$lang_name = 'croatian';
				break;
				case 'ht':
					$lang_name = 'haitian';
				break;
				case 'hu':
					$lang_name = 'hungarian';
				break;
				case 'hy':
					$lang_name = 'armenian';
				break;
				case 'hz':
					$lang_name = 'herero';
				break;
				case 'ia':
					$lang_name = 'interlingua';
				break;
				case 'id':
					$lang_name = 'indonesian';
				break;
				case 'ie':
					$lang_name = 'interlingue';
				break;
				case 'ig':
					$lang_name = 'igbo';
				break;
				case 'ii':
					$lang_name = 'sichuan_yi';
				break;
				case 'ik':
					$lang_name = 'inupiaq';
				break;
				case 'io':
					$lang_name = 'ido';
				break;
				case 'is':
					$lang_name = 'icelandic';
				break;
				case 'it':
					$lang_name = 'italian';
				break;
				case 'iu':
					$lang_name = 'inuktitut';
				break;
				case 'ja':
					$lang_name = 'japanese';
				break;
				case 'jv':
					$lang_name = 'javanese';
				break;
				case 'ka':
					$lang_name = 'georgian';
				break;
				case 'kg':
					$lang_name = 'kongo';
				break;
				case 'ki':
					$lang_name = 'kikuyu';
				break;
				case 'kj':
					$lang_name = 'kwanyama';
				break;
				case 'kk':
					$lang_name = 'kazakh';
				break;
				case 'kl':
					$lang_name = 'kalaallisut';
				break;
				case 'km':
					$lang_name = 'khmer';
				break;
				case 'kn':
					$lang_name = 'kannada';
				break;
				case 'ko':
					$lang_name = 'korean';
				break;
				case 'kr':
					$lang_name = 'kanuri';
				break;
				case 'ks':
					$lang_name = 'kashmiri';
				break;
				case 'ku':
					$lang_name = 'kurdish';
				break;
				case 'kv':
					$lang_name = 'komi';
				break;
				case 'kw':
					$lang_name = 'cornish_kernewek';
				break;
				case 'ky':
					$lang_name = 'kirghiz';
				break;
				case 'la':
					$lang_name = 'latin';
				break;
				case 'lb':
					$lang_name = 'luxembourgish';
				break;
				case 'lg':
					$lang_name = 'ganda';
				break;
				case 'li':
					$lang_name = 'limburgish';
				break;
				case 'ln':
					$lang_name = 'lingala';
				break;
				case 'lo':
					$lang_name = 'lao';
				break;
				case 'lt':
					$lang_name = 'lithuanian';
				break;
				case 'lu':
					$lang_name = 'luba-katanga';
				break;
				case 'lv':
					$lang_name = 'latvian';
				break;
				case 'mg':
					$lang_name = 'malagasy';
				break;
				case 'mh':
					$lang_name = 'marshallese';
				break;
				case 'mi':
					$lang_name = 'maori';
				break;
				case 'mk':
					$lang_name = 'macedonian';
				break;
				case 'ml':
					$lang_name = 'malayalam';
				break;
				case 'mn':
					$lang_name = 'mongolian';
				break;
				case 'mo':
					$lang_name = 'moldavian';
				break;
				case 'mr':
					$lang_name = 'marathi';
				break;
				case 'ms':
					$lang_name = 'malay';
				break;
				case 'mt':
					$lang_name = 'maltese';
				break;
				case 'my':
					$lang_name = 'burmese';
				break;
				case 'na':
					$lang_name = 'nauruan';
				break;
				case 'nb':
					$lang_name = 'norwegian';
				break;
				case 'nd':
					$lang_name = 'ndebele';
				break;
				case 'ne':
					$lang_name = 'nepali';
				break;
				case 'ng':
					$lang_name = 'ndonga';
				break;
				case 'nl':
					$lang_name = 'dutch';
				break;
				case 'nn':
					$lang_name = 'norwegian_nynorsk';
				break;
				case 'no':
					$lang_name = 'norwegian';
				break;
				case 'nr':
					$lang_name = 'southern_ndebele';
				break;
				case 'nv':
					$lang_name = 'navajo';
				break;
				case 'ny':
					$lang_name = 'chichewa';
				break;
				case 'oc':
					$lang_name = 'occitan';
				break;
				case 'oj':
					$lang_name = 'ojibwa';
				break;
				case 'om':
					$lang_name = 'oromo';
				break;
				case 'or':
					$lang_name = 'oriya';
				break;
				case 'os':
					$lang_name = 'ossetian';
				break;
				case 'pa':
					$lang_name = 'panjabi';
				break;
				case 'pi':
					$lang_name = 'pali';
				break;
				case 'pl':
					$lang_name = 'polish';
				break;
				case 'ps':
					$lang_name = 'pashto';
				break;
				case 'pt':
					$lang_name = 'portuguese';
				break;
				case 'pt_br':
					$lang_name = 'portuguese_brasil';
				break;
				case 'qu':
					$lang_name = 'quechua';
				break;
				case 'rm':
					$lang_name = 'romansh';
				break;
				case 'rn':
					$lang_name = 'kirundi';
				break;
				case 'ro':
					$lang_name = 'romanian';
				break;
				case 'ru':
					$lang_name = 'russian';
				break;
				case 'rw':
					$lang_name = 'kinyarwanda';
				break;
				case 'sa':
					$lang_name = 'sanskrit';
				break;
				case 'sc':
					$lang_name = 'sardinian';
				break;
				case 'sd':
					$lang_name = 'sindhi';
				break;
				case 'se':
					$lang_name = 'northern_sami';
				break;
				case 'sg':
					$lang_name = 'sango';
				break;
				case 'sh':
					$lang_name = 'serbo-croatian';
				break;
				case 'si':
					$lang_name = 'sinhala';
				break;
				case 'sk':
					$lang_name = 'slovak';
				break;
				case 'sl':
					$lang_name = 'slovenian';
				break;
				case 'sm':
					$lang_name = 'samoan';
				break;
				case 'sn':
					$lang_name = 'shona';
				break;
				case 'so':
					$lang_name = 'somali';
				break;
				case 'sq':
					$lang_name = 'albanian';
				break;
				case 'sr':
					$lang_name = 'serbian';
				break;
				case 'ss':
					$lang_name = 'swati';
				break;
				case 'st':
					$lang_name = 'sotho';
				break;
				case 'su':
					$lang_name = 'sundanese';
				break;
				case 'sv':
					$lang_name = 'swedish';
				break;
				case 'sw':
					$lang_name = 'swahili';
				break;
				case 'ta':
					$lang_name = 'tamil';
				break;
				case 'te':
					$lang_name = 'telugu';
				break;
				case 'tg':
					$lang_name = 'tajik';
				break;
				case 'th':
					$lang_name = 'thai';
				break;
				case 'ti':
					$lang_name = 'tigrinya';
				break;
				case 'tk':
					$lang_name = 'turkmen';
				break;
				case 'tl':
					$lang_name = 'tagalog';
				break;
				case 'tn':
					$lang_name = 'tswana';
				break;
				case 'to':
					$lang_name = 'tonga';
				break;
				case 'tr':
					$lang_name = 'turkish';
				break;
				case 'ts':
					$lang_name = 'tsonga';
				break;
				case 'tt':
					$lang_name = 'tatar';
				break;
				case 'tw':
					$lang_name = 'twi';
				break;
				case 'ty':
					$lang_name = 'tahitian';
				break;
				case 'ug':
					$lang_name = 'uighur';
				break;
				case 'uk':
					$lang_name = 'ukrainian';
				break;
				case 'ur':
					$lang_name = 'urdu';
				break;
				case 'uz':
					$lang_name = 'uzbek';
				break;
				case 've':
					$lang_name = 'venda';
				break;
				case 'vi':
					$lang_name = 'vietnamese';
				break;
				case 'vo':
					$lang_name = 'volapuk';
				break;
				case 'wa':
					$lang_name = 'walloon';
				break;
				case 'wo':
					$lang_name = 'wolof';
				break;
				case 'xh':
					$lang_name = 'xhosa';
				break;
				case 'yi':
					$lang_name = 'yiddish';
				break;
				case 'yo':
					$lang_name = 'yoruba';
				break;
				case 'za':
					$lang_name = 'zhuang';
				break;
				case 'zh':
					$lang_name = 'chinese';
				break;
				case 'zh_cmn_hans':
					$lang_name = 'chinese_simplified';
				break;
				case 'zh_cmn_hant':
					$lang_name = 'chinese_traditional';
				break;
				case 'zu':
					$lang_name = 'zulu';
				break;
				default:
					$lang_name = (strlen($string) > 2) ? ucfirst(str_replace($pattern, '', $string)) : $string;
				break;
			}		
			return ucwords(str_replace(array(" ","-","_"), ' ', $lang_name));
		}
		return ucwords(str_replace(array(" ","-","_"), ' ', str_replace($pattern, '', $string)));
	}
	
	/* replacement for eregi($pattern, $string); outputs 0 or 1*/
	function trisstr($pattern = '%{$regex}%i', $string, $matches = '') 
	{      
		return preg_match('/' . $pattern . '/i', $string, $matches);
	}
	

	
	function clean_string($string)
	{
		$array_find = array(
			"''",
			"'",
			"\r\n",
		);

		$array_replace = array(
			"'",
			"\'",
			"\n",
		);

		$string = str_replace($array_find, $array_replace, stripslashes(print_r($string, true)));
		return $string;
	}
// THE END
}
?>