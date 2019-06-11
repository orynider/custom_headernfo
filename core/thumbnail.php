<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/
namespace orynider\customheadernfo\core;
use orynider\customheadernfo\core\customheadernfo;

class thumbnail extends \orynider\customheadernfo\core\customheadernfo
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
	* @param \phpbb\log														$log
	* @param \phpbb\cache\service									$cache
	* @param \orynider\pafiledb\core\functions_cache		$functions_cache
	* @param \phpbb\db\driver\driver_interface				$db
	* @param \phpbb\request\request		 						$request
	* @param \phpbb\pagination											$pagination
	* @param \phpbb\extension\manager							$ext_manager
	* @param \phpbb\path_helper										$path_helper
	* @param string 																$php_ext
	* @param string 																$root_path
	* @param string 																$custom_header_info
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
		\phpbb\files\factory $files_factory = null,
		$language_list = array(),
		$dir_select_from = null,
		$dir_select_into = null)
	{
		$this->config							 			= $config;
		$this->language								= $language;
		$this->template 								= $template;
		$this->user 										= $user;
		$this->log 											= $log;
		$this->cache 										= $cache;
		$this->db 											= $db;
		$this->request 									= $request;
		$this->pagination 							= $pagination;
		$this->ext_manager	 						= $ext_manager;
		$this->path_helper	 						= $path_helper;
		$this->php_ext 									= $php_ext;
		$this->root_path 								= $root_path;
		$this->custom_header_info_table 	= $custom_header_info_table;
		$this->files_factory 							= $files_factory;
		$this->language_list							= $language_list;
		$this->ext_name = $this->request->variable('ext_name', 'orynider/customheadernfo');
		$this->module_root_path = $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->module_root_path);
		$this->set_languages();
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
	* Set languages
	*
	* @return null
	* @access public
	*/
	public function set_languages()
	{
		/* Check watever languages for thumbnail text are set and are uploaded or translated */
		$this->language_from = (isset($this->config['default_lang']) && (is_dir($this->module_root_path . 'language/' . $this->config['default_lang']) . '/')) ? $this->config['default_lang'] : 'en';
		$this->language_into	= (isset($user->lang['USER_LANG']) && (is_dir($this->module_root_path . 'language/' . $user->lang['USER_LANG']) . '/')) ? $user->lang['USER_LANG'] : $this->language_from;
		if (!is_dir($this->module_root_path . 'language/' . $this->language_from . '/'))
		{
			//Default language from uk english in case Resource id is #0 
			$this->language_from = (is_dir($this->module_root_path . 'language/en/')) ?  'en' : $this->language_into;
		}
		$this->user->add_lang_ext($this->ext_name, 'common');
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
		$folder_path = $folder_from = $path . customheadernfo::MULTILANG_DIR . $this->language_from;
		$folder_into = $path . customheadernfo::MULTILANG_DIR . $this->language_into;
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
		//Will not work for php5 open_basedir restriction.
		if (@is_dir($subdir_select_from . '/') && is_array($subdirs))
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
}
