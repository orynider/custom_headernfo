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

class admin_controller extends \orynider\customheadernfo\core\customheadernfo
{
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
	
	/** @var \phpbb\language\language */
	protected $language;

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
	
	/** @var \phpbb\config\config */	
	protected $config;
	
	/** @array language_list */	
	protected $language_list = array();
	
	/** @var dir_select_from */	
	protected $dir_select_from;
	
	/** @var dir_select_into */	
	protected $dir_select_into;
	
	/** @var string */
	public $tpl_name;

	/** @var string */
	public $page_title;
	
	/**
	* Constructor
	*
	* @param \phpbb\template\template		 			$template
	* @param \phpbb\user											$user
	* @param \phpbb\log											$log
	* @param \phpbb\cache\service							$cache
	* @param \phpbb\db\driver\driver_interface			$db
	* @param \phpbb\request\request		 				$request
	* @param \phpbb\pagination								$pagination
	* @param \phpbb\extension\manager					$ext_manager
	* @param \phpbb\path_helper								$path_helper
	* @param \phpbb\language\language					$language
	* @param string 													$php_ext
	* @param string 													$root_path
	* @param string 													$custom_header_info
	* @param \phpbb\files\factory								$files_factory
	* @param \phpbb\config\config 							$config
	* @var $this->config
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\log\log $log,
		\phpbb\cache\service $cache,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\pagination $pagination,
		\phpbb\extension\manager $ext_manager,
		\phpbb\path_helper $path_helper,
		\phpbb\language\language $language, 
		$php_ext, $root_path,
		$custom_header_info_table,
		\phpbb\files\factory $files_factory = null,
		$config)
	{
		$this->template 			= $template;
		$this->user 					= $user;
		$this->log 						= $log;
		$this->cache 					= $cache;
		$this->db 						= $db;
		$this->request 				= $request;
		$this->pagination 		= $pagination;
		$this->ext_manager	 	= $ext_manager;
		$this->path_helper	 	= $path_helper;
		$this->language			= $language;
		$this->php_ext 				= $php_ext;
		$this->root_path 			= $root_path;
		$this->config					= $config;
		
		// Read out custom_header_info values
		$this->custom_header_info_table = $custom_header_info_table;
		
		$this->files_factory 		= $files_factory;
		
		$this->ext_name = $this->request->variable('ext_name', 'orynider/customheadernfo');
		$this->module_root_path	= $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->module_root_path);
		$this->set_languages();
	}
	
	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
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
	 * Load available module languages list
	 *
	 * @return array available languages list: KEY = folder name
	 */
	function get_lang_list($path, $lang_from = '', $add_path = '', $lang_into = '')
	{
		if (count($this->language_list))
		{
			return $this->language_list;
		}
		$dir = opendir($this->module_root_path . customheadernfo::MULTILANG_DIR);
		while($f = readdir($dir))
		{
			if (($f == '.' || $f == '..') || !is_dir($this->module_root_path . customheadernfo::MULTILANG_DIR . $f) )
			{
				continue;
			}
			
			if ($this->language_from == '')
			{
				$this->language_from = $this->set_cookie($this->ext_name . 'language_from', $f);
			}
			$this->module_language_list[$f] =  $this->ucstrreplace('lang_', '', $f);
		}
		closedir($dir);
		return $this->module_language_list;
	}
	
	/**
	*
	* Load available module language file names list for a language dir
	*/
	function load_lang_dirs($path, $lang_from = '', $add_path = '', $lang_into = '')
	{
		
		/* root path at witch we add ie. extension path */  
		$root_path = $this->module_root_path;
		
		$php_ext = $this->php_ext;
		
		if (($this->dir_select_from == $this->dir_select_into) && ($this->language_from !== $this->language_into))
		{
			$this->dir_select_from = str_replace($this->language_into, $this->language_from, $this->dir_select_from);
			$this->dir_select_from = ($this->trisstr('\.' . $php_ext . '$', $this->dir_select_from) == false) ? $this->dir_select_from : dirname($this->dir_select_from);
			$this->dir_select_into = ($this->trisstr('\.' . $php_ext . '$', $this->dir_select_into) == false) ? $this->dir_select_into : dirname($this->dir_select_into);
		}

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
			//we user @ for php5 open_basedir restriction in effect if safemode
			if (@is_dir($folder_path . '/' . $file))
			{
				$lang_dirs[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
				$sub_dirs = $this->load_lang_dirs($folder_path, $language, $add_path . '/'. $file);
				$lang_dirs = is_array($sub_dirs) ? array_merge($lang_dirs, $sub_dirs) : $lang_dirs;
			}
		}
		@closedir($dir);
		//we user @ for php5 open_basedir restriction in effect if safemode
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
			//we user @ for php5 open_basedir restriction in effect if safemode
			if(@is_dir($subdir_select_from . '/' . $file))
			{
				$sub_dirs[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
				$lang_dirs = array_merge($lang_dirs, $sub_dirs);
			}
		}
		@closedir($subdir);
		
		return $lang_dirs;
	}
	
	/**
	*
	* Load available module language files list for a language
	*
	*/
	function load_lang_files($path, $language, $add_path = '', $dir_select = '')
	{
		/* root path at witch we add ie. extension path */  
		$root_path = $this->module_root_path;
		
		$php_ext = $this->php_ext;		
		
		if (($this->dir_select_from == $this->dir_select_into) && ($this->language_from !== $this->language_into))
		{
			$this->dir_select_from = str_replace($this->language_into, $this->language_from, $this->dir_select_from);
			$this->dir_select_from = ($this->trisstr('\.' . $php_ext . '$', $this->dir_select_from) == false) ? $this->dir_select_from : dirname($this->dir_select_from);
			$this->dir_select_into = ($this->trisstr('\.' . $php_ext . '$', $this->dir_select_into) == false) ? $this->dir_select_into : dirname($this->dir_select_into);
		}

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
		
		$lang_files = array();
		$folder_path = $folder_from = $path . customheadernfo::MULTILANG_DIR . $this->language_from;
		$folder_into = $path . customheadernfo::MULTILANG_DIR . $this->language_into;
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
			//we user @ for php5 open_basedir restriction in effect if safemode
			if (@is_dir($folder_path . '/' . $file))
			{
				$sub_files = $this->load_lang_files($folder_path, $language, $add_path . '/'. $file);
				$lang_files = is_array($sub_files) ? array_merge($lang_files, $sub_files) : $lang_files;
			}
			else if( is_file($folder_path . '/' . $file))
			{
				$lang_files[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
			}
		}
		@closedir($dir);
		//we user @ for php5 open_basedir restriction in effect if safemode
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
			
			if(is_file($subdir_select_from . '/' . $file))
			{
				$sub_files[$add_path . (!empty($add_path) ? '/' : '') . $file] = $add_path . (!empty($add_path) ? '/' : '') . $file;
				$lang_files = array_merge($lang_files, $sub_files);
			}
		}
		@closedir($subdir);
		
		return $lang_files;
	}
	
	/**
	 * Set and get value from posted or cookie
	 * @return mixed value generated from posted, geted or cookie
	 * @param $name string cookie name of the value
	 * @param $value mixed value which should be setted for cookie
	 */
	function set_cookie($name, $value = '')
	{
		/* cookie_name', 'phpbb3_li1e6', 0 */
		$return = '';
		if ($value != '')
		{
			$return = $value;
			$this->user->set_cookie($name, $value, (time() + 21600), false);
			$this->set_cookie[$name] = $value;
			
		}
		else if (isset($_COOKIE[$name]))
		{
			$value = $this->set_cookie[$name] = $this->request->variable($name, 0, false, \phpbb\request\request_interface::COOKIE);
			$this->user->set_cookie($name, $value, (time() + 21600), false);
			
		}
		$this->set_cookie['test' . $name] = $value;		
		return $value;
	}
	
	/**
	 * Get html select list - from array().
	 * ported from mxp-cms by orynider
	 * This function generates and returns a html select list (name = $nameselect).
	 *
	 * @access public
	 * @param string $name_select select name
	 * @param array $row source data
	 * @param string $id needle
	 * @param boolean $full_list expanded or dropdown list
	 * @return unknown
	 */
	function get_list_static($name_select, $row, $id, $full_list = true)
	{
		$rows_count = ( count($row) < '25' ) ? count($row) : '25';
		$full_list_true = $full_list ? ' size="' . $rows_count . '"' : '';

		$column_list = '<select name="' . $name_select .'" ' . $full_list_true . '>';
		foreach( $row as $idfield => $namefield )
		{
			$selected = ( $idfield == $id ) ? ' selected="selected"' : '';
			$column_list .= '<option value="' . $idfield . '"' . $selected . '>' . $namefield . "</option>\n";
		}
		$column_list .= '</select>';

		unset($row);
		return $column_list;
	}


	/**
	 * Generate option list
	 * @return HMTML option list
	 * @param $html string generate option list in HTML or JS format /now available only HTML/
	 * @param $name_select string which option list should be generated /'
	 * @param $selected string key of selected item
	 * @param $disabled mixed list of disabed key items
	 * @param $from_select boolean is the list initial?
	 */
	function gen_fonts_select_list($html, $name_select = 'header_info_font', $selected = '', $disabled = '', $full_list = true)
	{
		$list_ary = $this->get_fonts();

		if (count($list_ary) < 1)
		{
			return '';
		}

		asort($list_ary);
		reset($list_ary);
		$option_list = '';
		$num_args = func_num_args();

		$rows_count = (count($list_ary) < '25' ) ? count($list_ary) : '25';
		$full_list_true = $full_list ? ' size="' . $rows_count . '"' : '';
		$option_list = '<select id="' . $name_select .'" name="' . $name_select .'" ' . $full_list_true . '>';
		
		switch ($html)
		{
			case 'html':
				foreach($list_ary as $key => $value)
				{
					if ((is_array($disabled) && in_array($key, $disabled)) || (!is_array($disabled) && $key == $disabled))
					{
						continue;
					}
					
					$option_list .= '<option value="' . $key . '"';
					if ( $selected == $key )
					{
						$option_list .= ' selected';
					}
					$option_list .=  '>' . $value . '</option>';
				}
			break;
			case 'in_array':
			default:
				foreach($list_ary as $key => $value)
				{
					if ((is_array($disabled) && in_array($key, $disabled)) || (!is_array( $disabled) && $key == $disabled))
					{
						continue;
					}
					if (empty($key) || empty($value))
					{
						return '';
					}
					$option_list .= '<option value="' . $key . '"';
					if ( $selected == $key )
					{
						$option_list .= ' selected';
					}
					$option_list .= '>' . $value . '</option>';
				}
			break;
		}
		$option_list .= '</select>';
		return $option_list;
	}
	
	/**
	 * Generate option list
	 * @return HMTML option list
	 * @param $html string generate option list in HTML or JS format /now available only HTML/
	 * @param $which_list string which option list should be generated /'
	 * @param $selected string key of selected item
	 * @param $disabled mixed list of disabed key items
	 * @param $from_select boolean is the list initial?
	 */
	function gen_lang_dirs_select_list($html, $name_select, $selected = '', $disabled = '', $full_list = true)
	{
		$dir_list_ary = $this->load_lang_files($this->module_root_path, $this->language_from);
		$file_list_ary = $this->load_lang_files($this->module_root_path, $this->language_from);
		
		if ((count($dir_list_ary) < 1) && (count($file_list_ary) > 0))
		{
			$list_ary = $file_list_ary;
		}
		elseif ((count($file_list_ary) < 1) && (count($dir_list_ary) > 0))
		{
			$list_ary = $dir_list_ary;
		}
		elseif ((count($file_list_ary) > 0) && (count($dir_list_ary) > 0))
		{
			$list_ary = array_merge($dir_list_ary, $file_list_ary);
		}
		else
		{
			$list_ary = array('no_file' => 'no_file');
		}		
		
		$option_list = '';
		$num_args = func_num_args();

		$rows_count = (count($list_ary) < '25' ) ? count($list_ary) : '25';
		$full_list_true = $full_list ? ' size="' . $rows_count . '"' : '';
		$option_list = '<select name="' . $name_select .'" ' . $full_list_true . '>';
		
		switch ($html)
		{
			case 'html':
				foreach($list_ary as $key => $value)
				{
					//Strip sufixes
					$key = str_replace(array('common.'. $this->php_ext, $this->php_ext), '', $key);
					$value = str_replace(array('common.'. $this->php_ext, $this->php_ext), '', $value);
					if ((is_array($disabled) && in_array($key, $disabled)) || (!is_array($disabled) && $key == $disabled))
					{
						continue;
					}
					$option_list .= '<option value="' . $key . '"';
					if ( ucwords($selected) == ucwords($key) )
					{
						$option_list .= ' selected';
					}
					$option_list .=  '>' . (!empty($this->language->lang($value)) ? $this->language->lang($value) : $value) . '</option>';
				}
			break;
			case 'in_array':
			default:
				foreach($list_ary as $key => $value)
				{
					//Strip sufixes
					$key = str_replace(array('common.'. $this->php_ext, $this->php_ext), '', $key);
				
					$value = str_replace(array('common.'. $this->php_ext, $this->php_ext), '', $value);
					if ((is_array($disabled) && in_array($key, $disabled)) || (!is_array( $disabled) && $key == $disabled))
					{
						continue;
					}
					if (empty($key) || empty($value))
					{
						return '';
					}
					$option_list .= '<option value="' . $key . '"';
					if ( ucwords($selected) == ucwords($key) )
					{
						$option_list .= ' selected';
					}
					$option_list .= '>' . $value . '</option>';
				}
			break;
		}
		$option_list .= '</select>';
		return $option_list;
	}
	
	/**
	* Get lang key or value
	* To do: update this->user->lang[] into this->language->lang()
	* Not used, to be removed in 1.0.0-RC4
	* @return unknown
	 */	
	function get_lang($key)
	{
		return ((!empty($key) && isset($this->user->lang[$key])) ? $this->user->lang[$key] : $key);
	}

	function get_fonts()
	{
		// get all fonts installed
		$fonts = array();
		$dir = @opendir($this->module_root_path . 'assets/fonts/');

		while ($font = @readdir($dir))
		{
			if ((substr(strrchr($font, '.'), 1) == 'ttf') && is_file($this->module_root_path . 'assets/fonts/' . $font) && !is_link($this->module_root_path . 'assets/fonts/' . $font))
			{
				$filename = trim(basename($font));
				$displayname = substr($filename, 0, strrpos($filename, '.'));
				$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $displayname);
				$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
				$fonts[$font] = ucfirst($displayname);
			}
		}

		@closedir($dir);
		@asort($fonts);

		return $fonts;
	}
}
