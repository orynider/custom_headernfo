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

namespace orynider\customheadernfo\controller;

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
	* Name (including vendor) of the extension
	* @var string
	*/
	protected $ext_name;
	
	/**
	* Path to the module directory including root (sometime same with $ext_path)
	* @var string
	*/
	protected $module_root_path;
	
	/** @var path helper web url to root */	
	protected $ext_path_web;

	/**
	* The database tables
	*
	* @var string
	*/
	protected $custom_header_info_table;

	protected $custom_header_info_config_table;

	/** @var \phpbb\files\factory */
	protected $files_factory;
	
	/** @array language_list */	
	protected $language_list = array();
	
	/** @var dir_select_from */	
	protected $dir_select_from;
	
	/** @var dir_select_into */	
	protected $dir_select_into;
	
	/** @array entries */		
	protected $entries;
	
	/* @var get countries if is required and installed languages */		
	var $countries;
		
	/* @var get packs installed */
	var $packs;
	
	/**
	* Constructor
	*
	* @param \phpbb\config\config							        $config
	* @param \phpbb\language\language							$language
	* @param \phpbb\template\template		 					$template
	* @param \phpbb\user													$user
	* @param \phpbb\log													$log
	* @param \phpbb\cache\service									$cache
	* @param \orynider\pafiledb\core\functions_cache		$functions_cache
	* @param \phpbb\db\driver\driver_interface					$db
	* @param \phpbb\request\request		 						$request
	* @param \phpbb\pagination										$pagination
	* @param \phpbb\extension\manager							$ext_manager
	* @param \phpbb\path_helper										$path_helper
	* @param string 															$php_ext
	* @param string 															$root_path
	* @param string 															$custom_header_info
	* @param string 															$custom_header_info_config
	* @param \phpbb\files\factory										$files_factory
	* @array $language_list
	* @var $dir_select_from,
	* @var $dir_select_into	
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
		\phpbb\files\factory $files_factory = null,
		$language_list = array(),
		$dir_select_from = null,
		$dir_select_into = null)
	{
		$this->config							 			= $config;
		$this->language									= $language;
		$this->template 									= $template;
		$this->user 											= $user;
		$this->log 											= $log;
		$this->cache 										= $cache;
		$this->db 											= $db;
		$this->request 									= $request;
		$this->pagination 								= $pagination;
		$this->ext_manager	 							= $ext_manager;
		$this->path_helper	 							= $path_helper;
		$this->php_ext 									= $php_ext;
		$this->root_path 									= $root_path;
		$this->custom_header_info_table 		= $custom_header_info_table;
		$this->custom_header_info_config_table	= $custom_header_info_config_table;
		$this->files_factory 								= $files_factory;
		$this->language_list								= $language_list;
		$this->ext_name 		= $this->request->variable('ext_name', 'orynider/customheadernfo');
		$this->module_root_path	= $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web		= $this->path_helper->update_web_root_path($this->module_root_path);

		/* Check watever languages for thumbnail text are set and are uploaded or translated */
		$this->language_from = (isset($this->config['default_lang']) && (is_dir($this->module_root_path . 'language/' . $this->config['default_lang']) . '/')) ? $this->config['default_lang'] : 'en';
		$this->language_into	= (isset($user->lang['USER_LANG']) && (is_dir($this->module_root_path . 'language/' . $user->lang['USER_LANG']) . '/')) ? $user->lang['USER_LANG'] : $this->language_from;
		
		if (!is_dir($this->module_root_path . 'language/' . $this->language_from . '/'))
		{
			//Default language from uk english in case Resource id is #0 
			$this->language_from = (is_dir($this->module_root_path . 'language/en/')) ?  'en' : $this->language_into;
		}
	}
	
	/**
	* Handle image thumbnail
	*
	*/
	function handle_thumbnail()
	{
		// =======================================================
		// Request vars
		// =======================================================
		$info_id = $this->request->variable('info_id', 1);
		
		// Read out config values
		$custom_header_info_config = $this->config_values();
		
		// get countries if is required and installed languages		
		$this->countries = $this->get_countries();
		
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
		$header_info_dir = $row['header_info_dir']; /* i.e. ext/orynider/customheadernfo/language/movies/ */
		$header_info_font = $row['header_info_font'];
		$thumbnail_width = $thumbnail_height = 0;
		$db_width = $row['header_info_pic_width'];
		$db_height = $row['header_info_pic_height'];
		
		$header_info_title_font_size = $row['header_info_title_pixels'];
		$header_info_desc_font_size = $row['header_info_desc_pixels'];
		
		$header_info_font_size = !empty($row['header_info_desc_pixels']) ? $row['header_info_desc_pixels'] : $row['header_info_title_pixels'];
		
		// populate entries (all lang keys)
		$this->language_from = (isset($this->config['default_lang'])) ? $this->config['default_lang'] : $this->user->lang['USER_LANG'];
		$this->language_into = (isset($this->user->data['user_lang'])) ? $this->user->data['user_lang'] : $this->language_from;
		// Replace language with our request language
		$this->language_into = $this->request->variable('lang', $this->language_into); //$this->language_into = 'sy_lat';
		$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : $this->language_from;
		$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : 'en';
		$this->entries = $this->load_lang_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext);
		
		$header_info_title_colour		= isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '';
		$header_info_title_colour_1	= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 1) : '';
		$header_info_title_colour_2	= isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 2) : '';
		$header_info_desc_colour		= isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '';
		$header_info_desc_colour_1	= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 1) : '';
		$header_info_desc_colour_2	= isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 2) : '';
		
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
			$i = count($this->entries) -1;
			//Overwrite ramdom in ext.php, for ex. ?rand=29 for $j = 29.
			$j = $this->request->variable('rand', rand(0, $i));
			$l_keys = array_keys($this->entries);
			$l_values = array_values($this->entries);
			$pic_title = $l_keys[$j];
			$pic_desc = $l_values[$j];
		}
		/* Testing Hebre Text. Uncomment to test.
		$pic_desc = "ויענך וירעבך ויאכלך את המן אשר לא ידעת ולא ידעון אבתיך  למען הודיעך כי לא על הלחם לבדו יחיה האדם—כי על כל מוצא פי יהוה יחיה האדם";
		$pic_title = 'Test... Benjamin Netanyahu on ISIS and Nuclear Weapons, before the UN General Assembly, 03.03.2015.';
		$pic_desc = '"Test... They just disagree among themselves who will be the ruler of that empire. In this deadly game of thrones, there is no place for America or for Israel, no peace for Christians, Jews, or Muslims who don\'t share the Islamist medieval creed. No rights for women. No freedom for anyone. So when it comes to Iran and ISIS, the enemy of your enemy is your enemy."';
		*/
		
		$header_info_image	= $row['header_info_image'];

		$this->db->sql_freeresult($result);

		//
		// Output all
		//
		$board_url = generate_board_url();
		$phpbb_url = $board_url . '/';

		// Replace path by your own font path
		$font_name = $this->request->variable('font', $header_info_font);
		$font = $this->module_root_path . "assets/fonts/" . $font_name . '.ttf';
		
		/* fonts overwrite for other languages
		We only need this if there is no font for our language
		uncomment this if you add incompatible language * /
		switch($this->language_into)
		{
			case 'sy':
				//$font = $this->module_root_path . "assets/fonts/SyrCOMEdessa.ttf";
			break;
			
			default:
			break;
		}
		/* fonts overwrite for other languages */
		
		if (!is_file($font))
		{
			$font = $this->module_root_path . "assets/fonts/" . $header_info_font; //. '.ttf';
		}
		
		$header_info_image = $header_info_image ? str_replace('_info.', '_bg.', $header_info_image) : $this->module_root_path . "styles/prosilver/theme/images/banners/custom_header_bg.png";
		$header_info_image = str_replace(basename($header_info_image), $this->request->variable('image', basename($header_info_image)), $header_info_image);
		$header_info_image	= ($this->config['board_disable'] || ($row['header_info_id'] == 0)) ? generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/under_construction.gif' : $header_info_image;
		$header_info_image = str_replace(array('.php', '.pal'), '.png', $header_info_image);
		
		//user logged in ? user has custom style ?
		if (($this->user->data['user_id'] !== 1))
		{
			$this->default_style = (isset($this->config['default_style'])) ? $this->config['default_style'] : $this->user->data['user_style'];
			$this->user_style = (isset($this->user->data['user_style'])) ? $this->user->data['user_style'] : $this->default_style;
			
			if (is_file(str_replace('prosilver', $this->user_style, $header_info_image)))
			{
				$header_info_image = str_replace('prosilver', $this->user_style, $header_info_image);
			}
		}
		
		$src_path = str_replace($phpbb_url, $this->root_path, $header_info_image);
		$pic_filename = basename($src_path);
		$pic_filetype = strtolower(substr($pic_filename, strlen($pic_filename) - 4, 4)); 
		$pic_ext = str_replace('jpg', 'jpeg', substr(strrchr($pic_filename, '.'), 1));
		$file_header = 'Content-type: image/' . $pic_ext;
		$pic_title_reg = preg_replace("/[^A-Za-z0-9]/", "_", $pic_title);
		$read_function = 'imagecreatefrom'.$pic_ext;

		/* image id */
		$im = $read_function($src_path); 
		
		/* use php_GD2 GetImageSize() */
		if (function_exists('GetImageSize'))
		{
			$pic_size = GetImageSize($src_path);
			$pic_width = isset($pic_size[0]) ? $pic_size[0] : $db_width;
			$pic_height = isset($pic_size[1]) ? $pic_size[1] : $db_height;
		}
		else
		{
			$pic_width = $db_width;
			$pic_height = $db_height;
		}

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

		/* int ImageColorAllocate(resource $image, int $red, int $green, int $blue) */
		$title_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour, 'r'), $this->get_hexdec_colour($header_info_title_colour, 'g'), $this->get_hexdec_colour($header_info_title_colour, 'b'));
		$title_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_1, 'r'), $this->get_hexdec_colour($header_info_title_colour_1, 'g'), $this->get_hexdec_colour($header_info_title_colour_1, 'b'));
		$title_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_title_colour_2, 'r'), $this->get_hexdec_colour($header_info_title_colour_2, 'g'), $this->get_hexdec_colour($header_info_title_colour_2, 'b'));

		$desc_colour = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour, 'r'), $this->get_hexdec_colour($header_info_desc_colour, 'g'), $this->get_hexdec_colour($header_info_desc_colour, 'b'));
		$desc_colour_1 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_1, 'r'), $this->get_hexdec_colour($header_info_desc_colour_1, 'g'), $this->get_hexdec_colour($header_info_desc_colour_1, 'b'));
		$desc_colour_2 = ImageColorAllocate($im, $this->get_hexdec_colour($header_info_desc_colour_2, 'r'), $this->get_hexdec_colour($header_info_desc_colour_2, 'g'), $this->get_hexdec_colour($header_info_desc_colour_2, 'b'));

		$dimension_filesize = @FileSize($src_path);

		$dimension_font = 1;
		$dimension_string = intval($pic_width) . 'x' . intval($pic_height) . '(' . intval($dimension_filesize / 1024) . 'KB)';

		/* removing the black from the placeholder */
		ImageColorTransparent($im, $background);
		
		/* turning on alpha channel information saving 
		to ensure the full range of transparency is preserved */
		ImageSaveAlpha($im, true);

		$dimension_height = ImageFontHeight($dimension_font);
		$dimension_width = ImageFontWidth($dimension_font) * utf8_strlen($pic_desc, 'utf-8');
		$dimension_x = (($thumbnail_width - $dimension_width) / 2) - utf8_strlen($pic_desc, 'utf-8');
		$dimension_y = $thumbnail_height + ((16 - $dimension_height) / 2);

		/* ideea: https://stackoverflow.com/a/8187653/9369810
		credit: https://stackoverflow.com/users/1046402/jeff-wilbert */
		$middle_title = utf8_strrpos(utf8_substr($pic_title, 0, floor(utf8_strlen($pic_title) / 2 )), ' ') + 1;
		$middle_desc = utf8_strrpos(utf8_substr($pic_desc, 0, floor(utf8_strlen($pic_desc) / 2 )), ' ') + 1;

		$pic_title = $this->convert_encoding($pic_title);
		$pic_desc = $this->convert_encoding($pic_desc); 

		$pic_title1 = $this->convert_encoding(utf8_substr($pic_title, 0, $middle_title)); 
		$pic_title2 = $this->convert_encoding(utf8_substr($pic_title, $middle_title));

		$middle_title1 = utf8_strrpos(utf8_substr($pic_title1, 0, floor(utf8_strlen($pic_title1) / 2 )), ' ') + 1;
		$middle_title2 = utf8_strrpos(utf8_substr($pic_title2, 0, floor(utf8_strlen($pic_title2) / 2 )), ' ') + 1;

		//Title Split Level 2
		$pic_title1_1 = $this->convert_encoding(utf8_substr($pic_title1, 0, $middle_title1));
		$pic_title1_2 = $this->convert_encoding(utf8_substr($pic_title1, $middle_title1));
		$pic_title2_1 = $this->convert_encoding(utf8_substr($pic_title2, 0, $middle_title2));
		$pic_title2_2 = $this->convert_encoding(utf8_substr($pic_title2, $middle_title2));

		$pic_desc1 = $this->convert_encoding(utf8_substr($pic_desc, 0, $middle_desc)); 
		$pic_desc2 = $this->convert_encoding(utf8_substr($pic_desc, $middle_desc)); 

		$middle_desc1 = utf8_strrpos(utf8_substr($pic_desc1, 0, floor(utf8_strlen($pic_desc1) / 2 )), ' ') + 1;
		$middle_desc2 = utf8_strrpos(utf8_substr($pic_desc2, 0, floor(utf8_strlen($pic_desc2) / 2 )), ' ') + 1;

		//Description Split Level 2
		$pic_desc1_1 = $this->convert_encoding(utf8_substr($pic_desc1, 0, $middle_desc1));
		$pic_desc1_2 = $this->convert_encoding(utf8_substr($pic_desc1, $middle_desc1));
		$pic_desc2_1 = $this->convert_encoding(utf8_substr($pic_desc2, 0, $middle_desc2));
		$pic_desc2_2 = $this->convert_encoding(utf8_substr($pic_desc2, $middle_desc2));

		$resize_height = (($header_info_font_size * utf8_strlen($pic_desc, 'utf-8')) >= $resize_width) ? $resize_height + (2 * $header_info_font_size) : ((!empty($pic_desc2_2)) ? $resize_height + $header_info_font_size  : $resize_height);

		if (((6 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			$resize_height = $resize_height + $header_info_font_size;
			$dimension_title_y = 0;
		}
		else
		{
			$dimension_title_y = 8;
		}
		
		if (((5 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			$resize_height = $resize_height + $header_info_font_size;
			$dimension_title_y = 0;
		}
		else
		{
			$dimension_title_y = 6;
		}

		$pic_offset_desc1 = !empty($pic_desc1_2) ? utf8_strlen($pic_desc1_2, 'utf-8') - $resize_height + $header_info_title_font_size + 14 : 0;
		$pic_offset_desc2 =!empty($pic_desc2_2) ? utf8_strlen($pic_desc2_2, 'utf-8') - $resize_height + $header_info_title_font_size + 9 : 0;
		$dimension_desc_y = 1;
		if ((($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width) && (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width))
		{
			//Description Split Level 3
			if (($pic_offset_desc1 * utf8_strlen($pic_desc2_1, 'utf-8')) >= $resize_width)
			{
				$resize_height = $resize_height + (2 * $header_info_font_size);
				$dimension_desc_y = 6;
				$dimension_title_y = $dimension_title_y + 2;
			}
			else
			{
				$resize_height = $resize_height + $header_info_font_size;
				$dimension_desc_y = 6;
			}
		}
		
		if ((($resize_width !== 0) && ($resize_width !== $pic_width)) || ($dimension_desc_y == 6) || (!empty($pic_desc2_2) && ($resize_width !== $pic_width)))
		{
			$resize = ($this->gdVersion() == 1) ? ImageCreate($resize_width, $resize_height) : ImageCreateTrueColor($resize_width, $resize_height);
			$resize_function = ($this->gdVersion() == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			$resize_function($resize, $im, 0, 0, 0, 0, $resize_width, $resize_height, $pic_width, $pic_height);
			ImageDestroy($im);
			$pic_width = $resize_width;
			$pic_height = $resize_height;
			
			$im = $resize;
			
			ImageFilledRectAngle($im, 0, 0, $resize_width, $pic_height, $white);
			ImageColorTransparent($im, $white);
			
			/* We keep this code here commented
			Uncomment to use Antialias etc. * /
			if (function_exists('imageantialias'))
			{
				ImageAntialias($im, true);
			}
			ImageAlphaBlending($im, false);
			/* We keep this code here commented */
			
			/* removing the black from the placeholder */
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
			
			/* removing the black from the placeholder * /
			ImageColorTransparent($im, $white);
			/* removing the black from the placeholder */
			
			if (function_exists('imagesavealpha'))
			{
				ImageSaveAlpha($im, true);
			}
		}

		Header($file_header);

		Header("Expires: Mon, 1, 1999 05:00:00 GMT");
		Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		Header("Cache-Control: no-store, no-cache, must-revalidate");
		Header("Cache-Control: post-check=0, pre-check=0", false);
		Header("Pragma: no-cache");

		/* return with no uppercase if patern not in string */
		if (strpos($pic_title, ',') !== false)
		{
			$header_info_title_font_size = $header_info_title_font_size - 2;
		}

		if (((5 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title1);
			$dimension_y = $dimension_y + $header_info_font_size;
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title2);
		}	
		if (((6 * utf8_strlen($pic_title, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_title2, 'utf-8') >= $resize_width))
		{
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title1);
			$dimension_y = $dimension_y + $header_info_font_size;
			ImageTtfText($im, $header_info_title_font_size, 0, 12, $dimension_y + $dimension_title_y + 30, $title_colour, $font, $pic_title2);
		}
		else
		{
			// Add some shadow to the text
			ImageTtfText($im,  $header_info_title_font_size, 0, 11, $dimension_y + 28, $grey, $font, $pic_title);

			// Add the text
			ImageTtfText($im, $header_info_title_font_size, 0, 10, $dimension_y + 29, $title_colour, $font, $pic_title);
		}
		
		//4 x 138 >= 458
		if (((6 * utf8_strlen($pic_desc, 'utf-8')) >= $resize_width) || (utf8_strlen($pic_desc2, 'utf-8') >= $resize_width))
		{
			if ((($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width) && (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width))
			{
				//Description Split Level 3
				$middle_desc1_1 = utf8_strrpos(utf8_substr($pic_desc1_1, 0, floor(utf8_strlen($pic_desc1_1) / 2 )), ' ') + 1;
				$middle_desc1_2 = utf8_strrpos(utf8_substr($pic_desc1_2, 0, floor(utf8_strlen($pic_desc1_2) / 2 )), ' ') + 1;

				//Description Split Level 3
				$pic_desc1_1_1 = $this->convert_encoding(utf8_substr($pic_desc1_1, 0, $middle_desc1_1));
				$pic_desc1_1_2 = $this->convert_encoding(utf8_substr($pic_desc1_1, $middle_desc1_1));
				$pic_desc1_2_1 = $this->convert_encoding(utf8_substr($pic_desc1_2, 0, $middle_desc1_2));
				$pic_desc1_2_2 = $this->convert_encoding(utf8_substr($pic_desc1_2, $middle_desc1_2));
				
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + $dimension_desc_y, $desc_colour, $font, $pic_desc1_1);
				//ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + $header_info_font_size, $desc_colour, $font, $pic_desc1_1_2);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + $dimension_desc_y + $header_info_font_size, $desc_colour, $font, $pic_desc1_2);
				//ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + (2 * $header_info_font_size), $desc_colour, $font, $pic_desc1_2_2);
				
				//Description Split Level 3
				$middle_desc2_1 = utf8_strrpos(utf8_substr($pic_desc2_1, 0, floor(utf8_strlen($pic_desc2_1) / 2 )), ' ') + 1;
				$middle_desc2_2 = utf8_strrpos(utf8_substr($pic_desc2_2, 0, floor(utf8_strlen($pic_desc2_2) / 2 )), ' ') + 1;

				//Description Split Level 3
				$pic_desc2_1_1 = $this->convert_encoding(utf8_substr($pic_desc2_1, 0, $middle_desc2_1));
				$pic_desc2_1_2 = $this->convert_encoding(utf8_substr($pic_desc2_1, $middle_desc2_1));
				$pic_desc2_2_1 = $this->convert_encoding(utf8_substr($pic_desc2_2, 0, $middle_desc2_2));
				$pic_desc2_2_2 = $this->convert_encoding(utf8_substr($pic_desc2_2, $middle_desc2_2));
				
				if (($pic_offset_desc1 * utf8_strlen($pic_desc2_1, 'utf-8')) >= $resize_width)
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1_1);
					$dimension_y = $dimension_y + $header_info_font_size;
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1_2);
					$dimension_y = $dimension_y + $header_info_font_size;
				}
				else
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1);
					$dimension_y = $dimension_y + $header_info_font_size;
				}

				if (($pic_offset_desc1 * utf8_strlen($pic_desc2_2, 'utf-8')) >= $resize_width)
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2_1);
					$dimension_y = $dimension_y + $header_info_font_size;
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2_2);
					$dimension_y = $dimension_y + $header_info_font_size;
				}
				else
				{
					ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_2);
				}
			}
			elseif (($pic_offset_desc1 * utf8_strlen($pic_desc1, 'utf-8')) >= $resize_width)
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1_1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8 + $header_info_font_size, $desc_colour, $font, $pic_desc1_2);
			
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2);
			}
			elseif (($pic_offset_desc2 * utf8_strlen($pic_desc2, 'utf-8')) >= $resize_width)
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1);
			
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2_1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43 + $header_info_font_size, $desc_colour, $font, $pic_desc2_2);
			}
			else
			{
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 8, $desc_colour, $font, $pic_desc1);
				ImageTtfText($im, $header_info_font_size, 0, 12, $dimension_y + 43, $desc_colour, $font, $pic_desc2);
			}
		}
		else
		{
			ImageTtfText($im, $header_info_font_size, 0, 10, $dimension_y + 10, $desc_colour, $font, $pic_desc);
		}

		$wm = !empty($this->language->lang($row['header_info_dir'])) ? $this->language->lang($row['header_info_dir']) : $dimension_filesize;
		
		/* Position watermark and place on image */
		switch($custom_header_info_config['disp_watermark_at'])
		{
			case 0: // 1 top left
				$dest_x = 0;
				$dest_y = 0;
			break;

			case 1: // 2 top middle
				$dest_x = (($resize_width - utf8_strlen($wm, 'utf-8')) / 2);
				$dest_y = 0;
			break;

			case 2: // 3 top right
				$dest_x = $resize_width - utf8_strlen($wm, 'utf-8');
				$dest_y = 0;
			break;

			case 3: // 4 middle left
				$dest_x = 0;
				$dest_y = ($resize_width / 2) - ($header_info_font_size / 2);
			break;

			case 4: // 5 middle
				$dest_x = ($resize_width / 2 ) - (utf8_strlen($wm, 'utf-8') / 2);
				$dest_y = ($resize_height / 2 ) - $header_info_font_size;
			break;

			case 5: // 6 middle right
				$dest_x = $resize_width - utf8_strlen($wm, 'utf-8') - 100;
				$dest_y = ($resize_height / 2) - ($dimension_y + $header_info_font_size / 2);
			break;

			case 6: // 7 bottom left
				$dest_x = 0;
				$dest_y = $resize_height - $header_info_font_size;
			break;

			case 7: // 8 bottom middle
				$dest_x = (($resize_width - utf8_strlen($wm, 'utf-8')) / 2);
				$dest_y = $resize_height - $header_info_font_size;
			break;

			case 8: // 9 bottom right
				$dest_x = $resize_width - 60 - utf8_strlen($wm, 'utf-8');
				$dest_y =  $resize_height - $header_info_font_size;
			break;

			default:
			break;
		}

		//****************************************************************************
		// How add watermark at position
		//   Usage : WatermarkPos(Filename of the 24-bit PNG watermark file,
		//										position as 1 to 9 matrix,
		//										1		2		3
		//										4		5		6
		//										7		8		9
		//										maxsize as percentage,
		//										transition is the transparency level to be applied on the watermark)
		//   Returns : true on success and false on fail
		//****************************************************************************
		if ($custom_header_info_config['use_watermark'] == 1)
		{
			ImageTtfText($im, $header_info_font_size, 0,  $dest_x, $dest_y, $desc_colour, $font, $wm);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}

	/**
	 * Get custom_header_info configuration
	 *
	 * @param type array
	 * @return variable $config
	 */
	function config_values($use_cache = true)
	{
		if (($config = $this->cache->get('custom_header_info_config')) && ($use_cache))
		{
			return $config;
		}
		else
		{
			$sql = "SELECT *
				FROM " . $this->custom_header_info_config_table;
			$result = $this->db->sql_query($sql);
			while ( $row = $this->db->sql_fetchrow($result) )
			{
				$config[$row['config_name']] = trim($row['config_value']);
			}
			$this->db->sql_freeresult($result);
			
			if (empty($config))
			{		
				msg_handler(E_USER_ERROR, $this->language->lang('COULDNT_GET') . ' ' . $this->ext_name . ' ' . $this->language->lang('CONFIG'), __FILE__, __LINE__);
			}			
			$this->cache->put('custom_header_info_config', $config);
			
			return($config);
		}
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
		//Reverse string for RTL languages 
		switch($this->language_into)
		{
			case 'he':
			case 'ar':
			case 'sy':
			case 'ur':
				preg_match_all('/./us' , $text, $rtl);
				$text = join('' , array_reverse($rtl[0])); 
			break;
			
			default:
			break;
		}
		
		return $text;
	}
	
	/*
	*  Function to find version (1 or 2) of the GD extension.
	*  Description: Retrieve information about the currently installed GD library
	*  Usage: gdVersion()
	*  Returns: version number as integer
	*  Ported by orynider from mx_smartor and MG's Full Albun Pack
	*/
	function gdVersion($user_ver = 0)
	{
		if (!extension_loaded('gd'))
		{
			return;
		}
		
		static $gd_ver = 0;
		
		if ($user_ver == 1)
		{
			$gd_ver = 1; 
		}
		
		if ($user_ver != 2 && $gd_ver > 0)
		{
			$user_ver = 1;
		}
		
		if (function_exists('gd_info'))
		{
			//This shows the configuration of your gd extension. 
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];
		}
		
		if (($gd_ver == 0) && preg_match('/phpinfo/', ini_get('disable_functions')))
		{
			if ($user_ver == 2)
			{
				$gd_ver = 2;
			}
			else
			{
				$gd_ver = 1;
			}
		}		
		elseif ($gd_ver == 0) 
		{
			ob_start();
			phpinfo(8);
			$info = ob_get_contents();
			ob_end_clean();
			$info = stristr($info, 'gd version');
			preg_match('/\d/', $info, $match);
			$gd_ver = $match[0];
		}	
		return $gd_ver;
	}
	
	/**
	*
	* List all countries for witch languages files are installed 
	* and multilangual files uploaded
	* $this->countries = $this->get_countries()
	*/
	function get_countries()
	{
		if (count($this->language_list))
		{
			return $this->language_list;
		}
		/* get all countries installed */
		$countries = array();
		$dir = @opendir($this->module_root_path . 'language');
		while ($file = @readdir($dir))
		{
			/* downscrolls are not allowed */
			$f = trim(str_replace('_', '', $file));
			
			if (($f == '.' || $f == '..') || !is_dir($this->module_root_path . 'language/' . $f) || is_link($this->module_root_path . 'language/' . $file))
			{
				continue;
			}
			/* Decode language country iso codes returning country names with first uppercase
			We do need to overwrite the pattern using 'lang_' or "_" that are not allowed in iso dir names. */
			$this->module_language_list[$f] = $countries[$file] = $this->ucstrreplace("_", '', $f);
		}
		@closedir($dir);
		@asort($countries);

		return $countries;
	}
	
	/**
	*
	* Load available module language file names list for a language dir
	*/	
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
		if (!file_exists($root_path . 'app.'.$php_ext) && !file_exists($root_path . 'mcp'.$php_ext))
		{
			$language = $this->language_from;
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
			$dir = array('Resource id #53','Resource id #54','Resource id #55','Resource id #56','Resource id #57','Resource id #58');
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
	
	/**
	* Read entries (all lang keys) from all multilangual files of a package
	* $this->module_root_path . 'language/' . $country_dir . '/' . 'common' . $this->php_ext
	*
	*/	
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

	/**
	* Populate Language entries (all lang keys) from single multilangual file to a variable
	*
	* $this->entries = $this->load_lang_file($this->ext_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext);
	*
	* @param mixed $multi_lang_set specifies the mutilangual file to include
	 * @param array_type $lang
	 * @return $lang
	*/	
	function load_lang_file($multi_lang_set)
	{
		if (!is_file($multi_lang_set))
		{
			return array();
		}
		include($multi_lang_set);
		return $lang;
	}
	
	/**
	* Decode language country codes returning countries with first uppercase
	*
	 * ucstrreplace replacement for decode_lang()
	 * the reverse of encode_lang()
	 *
	 *  from MXP version 3.0.0-beta1
	 * $country_name = $this->ucstrreplace($lang_iso);
	 *
	 * @param array_type $lang
	 * @return unknown
	 */		
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
	
// THE END
}
?>