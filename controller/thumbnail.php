<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2018 orynider - http://mxpcms.sourceforge.net
* @version $Id: thumbnail.php,v 1.5 2008/08/30 22:23:00 orynider Exp
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin, FlorinCB] MX-Publisher Project Team
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
	* @param \phpbb\template\template		 						$template
	* @param \phpbb\user													$user
	* @param \phpbb\log														$log
	* @param \phpbb\cache\service										$cache
	* @param \orynider\pafiledb\core\functions_cache		$functions_cache	
	* @param \phpbb\db\driver\driver_interface				$db
	* @param \phpbb\request\request		 							$request
	* @param \phpbb\pagination											$pagination
	* @param \phpbb\extension\manager								$ext_manager
	* @param \phpbb\path_helper										$path_helper
	* @param string 																$php_ext
	* @param string 																$root_path
	* @param string 																$custom_header_info
	* @param string 																$custom_header_info_config
	* @param \phpbb\files\factory										$files_factory
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
		$php_ext, $root_path,
		$custom_header_info_table,
		$custom_header_info_config_table,
		\phpbb\files\factory $files_factory = null)
	{
		$this->template 				= $template;
		$this->user 					= $user;
		$this->log 						= $log;
		$this->cache 					= $cache;
		$this->db 						= $db;
		$this->request 				= $request;
		$this->pagination 			= $pagination;
		$this->ext_manager	 		= $ext_manager;
		$this->path_helper	 		= $path_helper;
		$this->php_ext 				= $php_ext;
		$this->root_path 				= $root_path;
		$this->custom_header_info_table 	= $custom_header_info_table;
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
		/* */
		
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
		$font = $this->module_root_path . "styles/prosilver/theme/images/banners/DejaVuSerif.ttf";
		
		if (!is_file($font))
		{
			print("Invalid font filename: ".$font);
		}
		
		$header_info_image = $header_info_image ? str_replace('_info.', '_bg.', $header_info_image) : $this->module_root_path . "styles/prosilver/theme/images/banners/custom_header_bg.png";

		$file_header = 'Content-type: image/png';
		$src = str_replace('php', 'png', $header_info_image);
		$src_path = str_replace($phpbb_url, $this->root_path, $header_info_image);
		//srand ((float) microtime() * 10000000);
		//$quote = rand(1, 6);

		$pic_title = $header_info_desc;
		$pic_title_reg = preg_replace("/[^A-Za-z0-9]/", "_", $pic_title);

		$current_release = $header_info_name;

		$im = @ImageCreateFromPNG($src_path);
		$pic_size = @GetImageSize($src_path);

		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];

		$dimension_font = 1;
		$dimension_filesize = @FileSize($src_path);
		$dimension_string = intval($pic_width) . 'x' . intval($pic_height) . '(' . intval($dimension_filesize / 1024) . 'KB)';
		
		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		// integer representation of the color black (rgb: 0,0,0)
		$black = $background = ImageColorAllocate($im, 0, 0, 0);
		$blue = ImageColorAllocate($im, 6, 108, 159);
		$blure = ImageColorAllocate($im, 29, 36, 52);
		
		//ImageFilledRectAngle($im, 0, 0, 399, 29, $white);
		// removing the black from the placeholder
		ImageColorTransparent($im, $background);
		// turning on alpha channel information saving (to ensure the full range
		// of transparency is preserved)
		ImageSaveAlpha($im, true);
		
		$dimension_height = imagefontheight($dimension_font);
		$dimension_width = imagefontwidth($dimension_font) * strlen($current_release);
		$dimension_x = ($thumbnail_width - $dimension_width) / 2;
		$dimension_y = $thumbnail_height + ((16 - $dimension_height) / 2);
		
		//ImageString($im, 2, $dimension_x, $dimension_y, $current_release, $blue);
		ImageString($im, 2, 125, 2, $current_release, $blue);
		ImageString($im, 2, 20, 17, $header_info_longdesc, $blue);
		
		//ImageTtfText($im, 2, 20, $dimension_x, $dimension_y, $blue, 'DejaVuSerif.ttf', $pic_title_reg);
		Header($file_header);
		
		Header("Expires: Mon, 1, 1999 05:00:00 GMT");
		Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		Header("Cache-Control: no-store, no-cache, must-revalidate");
		Header("Cache-Control: post-check=0, pre-check=0", false);
		Header("Pragma: no-cache");
		
		// Add some shadow to the text
		ImageTtfText($im, 20, 0, 11, 21, $grey, $font, $current_release);

		// Add the text
		ImageTtfText($im, 20, 0, 10, 20, $black, $font, $current_release);

		
		ImagePNG($im);
		//ImageDestroy($im);
		exit;
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

}
?>