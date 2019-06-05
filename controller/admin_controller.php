<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/

namespace orynider\customheadernfo\controller;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class admin_controller
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

	protected $custom_header_info_config_table;

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
	* @param string 													$custom_header_info_config
	* @param \phpbb\files\factory								$files_factory
	* @param \phpbb\config\config 							$config
	* @var $custom_header_info_config
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
		$custom_header_info_config_table,
		\phpbb\files\factory $files_factory = null,
		$config)
	{
		$this->template 				= $template;
		$this->user 						= $user;
		$this->log 						= $log;
		$this->cache 					= $cache;
		$this->db 						= $db;
		$this->request 				= $request;
		$this->pagination 			= $pagination;
		$this->ext_manager	 		= $ext_manager;
		$this->path_helper	 		= $path_helper;
		$this->language				= $language;
		$this->php_ext 				= $php_ext;
		$this->root_path 				= $root_path;
		$this->config					= $config;
		
		// Read out config values
		/* Not loaded with class ? $custom_header_info_config = $this->config_values(); */
		$this->custom_header_info_table = $custom_header_info_table;
		$this->custom_header_info_config_table 	= $custom_header_info_config_table;
		
		$this->files_factory 		= $files_factory;
		
		$this->ext_name = $this->request->variable('ext_name', 'orynider/customheadernfo');
		$this->module_root_path	= $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web = $this->path_helper->update_web_root_path($this->module_root_path);
		
		$this->user->add_lang_ext($this->ext_name, 'common');
			
		/* get packs installed and init some variables
		* This code is added here for future implementations commented for now
		* We could load a local language detected in setup() for anonymouse users 
		* that is not installed in main language dir
		$this->packs = $this->load_lang_dirs($this->module_root_path);
		*/
		
		/* Check watever languages for thumbnail text are set and are uploaded or translated */
		$this->language_from = (isset($this->config['default_lang']) && (is_dir($this->module_root_path . 'language/' . $this->config['default_lang']) . '/')) ? $this->config['default_lang'] : 'en';
		$this->language_into	= (isset($user->lang['USER_LANG']) && (is_dir($this->module_root_path . 'language/' . $user->lang['USER_LANG']) . '/')) ? $user->lang['USER_LANG'] : $this->language_from;
		if (!is_dir($this->module_root_path . 'language/' . $this->language_from . '/'))
		{
			//Default language from uk english in case Resource id is #0 
			$this->language_from = (is_dir($this->module_root_path . 'language/en/')) ?  'en' : $this->language_into;
		}
	}

	public function manage_header_info_config()
	{
		$form_action = $this->u_action . '&amp;action=add';

		$this->table = $this->custom_header_info_table;

		$this->tpl_name = 'acp_custom_header_info';
		$this->page_title = $this->user->lang('HEADER_INFO_TITLE');

		$form_key = 'acp_header_info';
		add_form_key($form_key);

		$lang_list = $this->get_lang_list($this->module_root_path, $this->language_from, '', $this->language_into);
		$file_list = $this->load_lang_files($this->module_root_path, $this->language_from);
		$lang_dirs = $this->load_lang_dirs($this->module_root_path, $this->language_from, '', $this->language_into);

		$sql = 'SELECT *
	            FROM ' . $this->custom_header_info_table . '
	            ORDER BY header_info_id ASC';
		$result = $this->db->sql_query($sql);
		while( $row = $this->db->sql_fetchrow($result) )
		{
			$header_info_type_select = $this->get_list_static('header_info_type', 
											array('language' => $this->user->lang('MULTI_LANGUAGE_BANNER'),
														'lang_html_text' => $this->user->lang('HTML_MULTI_LANGUAGE_TEXT'), 
														'simple_db_text' => $this->user->lang('SIMPLE_DB_TEXT'), 
														'simple_bg_logo' => $this->user->lang('SIMPLE_BG_LOGO')
														), 
														$row['header_info_type']);

			//Populate info to display starts
			$info_title = array();
			$info_desc = array();
			
			$header_info_name = $row['header_info_name'];
			$header_info_desc = $row['header_info_desc'];
			$header_info_longdesc = $row['header_info_longdesc'];
			$header_info_use_extdesc = $row['header_info_use_extdesc'];
			
			if ($row['header_info_type'] == 'lang_html_text')
			{
				$header_info_dir = $row['header_info_dir'];
				$header_info_font = $row['header_info_font'];
				
				// populate entries (all lang keys)
				$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : $this->language_from;
				$this->entries = $this->load_lang_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext);
				
				$i = 0;
				srand ((float) microtime() * 10000000);

				if (count($this->entries) == 0)
				{
					$l_keys[0] = $header_info_name;
					$l_values[0] = $header_info_desc;
					
					$l_keys[1] = $header_info_name;
					$l_values[1] = $header_info_longdesc;
					$j = rand(0, 1);
					$info_title = $l_keys[$j];
					$info_desc = $l_values[$j];
				}
				else
				{
					$i = count($this->entries);
					$j = rand(0, $i);
					//$j = 4;
					$l_keys = array_keys($this->entries);
					$l_values = array_values($this->entries);
					$info_title = $l_keys[$j];
					$info_desc = $l_values[$j];
				}
			}
			else
			{
				$l_keys[0] = $header_info_name;
				$l_values[0] = $header_info_desc;
					
				$l_keys[1] = $header_info_name;
				$l_values[1] = $header_info_longdesc;
				$j = rand(0, 1);
				$info_title = $l_keys[$j];
				$info_desc = $l_values[$j];
			}
			
			$header_info_font = isset($row['header_info_font']) ? $row['header_info_font'] : 'tituscbz.ttf';
			
			//Populate info to display ends
			$this->template->assign_block_vars('header_info_scroll', array(
				'HEADER_INFO_ID'							=> $row['header_info_id'],
				'HEADER_INFO_NAME'						=> $row['header_info_name'],
				'HEADER_INFO_TITLE'						=> $info_title,
				'HEADER_INFO_DESC'						=> $row['header_info_desc'],
				'HEADER_INFO_LONGDESC'				=> $row['header_info_longdesc'],
				'HEADER_INFO_USE_EXTDESC'			=> $row['header_info_use_extdesc'],
				'EXTENED_SITE_DESC'						=> $row['header_info_use_extdesc'],
				'HEADER_INFO_RANDDESC'				=> $info_desc,
				'HEADER_INFO_TYPE_SELECT'			=> $header_info_type_select,
				'HEADER_INFO_DIR'							=> $this->user->lang[$row['header_info_dir']],
				'HEADER_INFO_TYPE'						=> $row['header_info_type'],
				'HEADER_INFO_DIR_SELECT' 			=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', $row['header_info_dir']), //ext/orynider/customheadernfo/language/movies/
				'HEADER_INFO_FONT_SELECT' 			=> $this->gen_fonts_select_list('html', 'header_info_font', $row['header_info_font']), //ext/orynider/customheadernfo/assets/fonts/
				'HEADER_INFO_DB_FONT' 				=> substr($header_info_font, 0, strrpos($header_info_font, '.')),
				'HEADER_INFO_IMAGE'						=> $row['header_info_image'],
				'THUMBNAIL_URL'   							=> generate_board_url() . '/app.php/thumbnail',
				//New 0.9.0 start
				'HEADER_INFO_TITLE_COLOUR'		=> isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '',
				'HEADER_INFO_DESC_COLOUR'		=> isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '',
				//New 0.9.0 ends
				'S_HEADER_INFO_LINK_CHECKED'	=> $row['header_info_image_link'],
				'HEADER_INFO_URL'						=> $row['header_info_url'],
				'HEADER_INFO_LICENSE'				=> $row['header_info_license'],
				'HEADER_INFO_TIME'					=> $row['header_info_time'],
				'HEADER_INFO_LAST'					=> $row['header_info_last'],
				'HEADER_INFO_PIC_WIDTH'			=> $row['header_info_pic_width'],
				'HEADER_INFO_PIC_HEIGHT'			=> $row['header_info_pic_height'],
				'S_FORUM_OPTIONS'					=> make_forum_select($row['forum_id'], array(), true, false, false),
				'S_HTML_MULTI_TEXT_ENABLED'	=> ($row['header_info_type'] == 'lang_html_text'),
				'S_SIMPLE_DB_TEXT_ENABLED'		=> ($row['header_info_type'] == 'simple_db_text'),
				'S_HEADER_INFO_PIN_CHECKED'	=> $row['header_info_pin'],
				'S_HEADER_INFO_DISABLE'			=> $row['header_info_disable'], // settings_disable,
				'U_EDIT'										=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=edit",
				'U_DELETE'									=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=delete"
			));
		}
		$this->db->sql_freeresult($result);

		// Read out config values
		$custom_header_info_config = $this->config_values();

		$header_info_direction_select	= $this->get_list_static('direction', 
											array('up' => $this->user->lang('UP'),
														'down' => $this->user->lang('DOWN')
														), 
														$custom_header_info_config['direction']);

		$this->template->assign_vars(array(
			'S_HEADER_INFO_ENABLED'   		=> $custom_header_info_config['header_info_enable'], // settings_disable
			'S_HEADER_INFO_POSITION1'		=> $custom_header_info_config['banner_position1'],
			'S_HEADER_INFO_POSITION2'		=> $custom_header_info_config['banner_position2'],
			'S_HEADER_INFO_POSITION3'		=> $custom_header_info_config['banner_position3'],
			'S_HEADER_INFO_POSITION4'		=> $custom_header_info_config['banner_position'],
			'HEADER_INFO_TYPE_SELECT'		=> $header_info_type_select,
			'HEADER_INFO_DIR_SELECT' 		=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', 'politics'), //ext/orynider/customheadernfo/language/movies/
			'HEADER_INFO_FONT_SELECT'		=> $this->gen_fonts_select_list('html', 'header_info_font', ''), //ext/orynider/customheadernfo/assets/fonts/
			'HEADER_INFO_IMAGE'					=> generate_board_url() . '/' . $custom_header_info_config['banners_dir'] . 'custom_header_bg.png',

			'ROW_HEIGHT'								=> $custom_header_info_config['row_height'],		/* Height of each ticker row in PX. Should be uniform. */
			'SPEED'											=> $custom_header_info_config['speed'],		/* Speed of transition animation in milliseconds */
			'INTERVAL'									=> $custom_header_info_config['interval'],		/* Time between change in milliseconds */
			'SHOW_AMOUNT'						=> $custom_header_info_config['show_amount'],		/* Integer for how many items to query and display at once. Resizes height accordingly (OPTIONAL) */
			'S_MOUSESTOP_ENABLED'			=> $custom_header_info_config['mousestop'],		/* If set to true, the ticker will stop on mouseover */
			'DIRECTION_SELECT'						=> $header_info_direction_select,		/* Direction that list will scroll */

			/*--------------------
			* WaterMark Section
			* From admin_album_clown_SP.php
			* Credits: clown@pimprig.com, Volodymyr (CLowN) Skoryk
			*--------------------*/
			//watermark
			//'L_WATERMARK' => $lang['SP_Watermark'],
			'S_WATERMARK_ENABLED' => $custom_header_info_config['use_watermark'],
			'WATERMARK_ENABLED' => ($custom_header_info_config['use_watermark'] == 1) ? 'checked="checked"' : '',
			'WATERMARK_DISABLED' => ($custom_header_info_config['use_watermark'] == 0) ? 'checked="checked"' : '',

			//watermark placement
			//'L_WATERMARK_PLACENT' => $lang['SP_Watermark_placent'],
			'WATERMAR_PLACEMENT_AT' => $custom_header_info_config['disp_watermark_at'],
			'WATERMAR_PLACEMENT_0' => ($custom_header_info_config['disp_watermark_at'] == 0) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_1' => ($custom_header_info_config['disp_watermark_at'] == 1) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_2' => ($custom_header_info_config['disp_watermark_at'] == 2) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_3' => ($custom_header_info_config['disp_watermark_at'] == 3) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_4' => ($custom_header_info_config['disp_watermark_at'] == 4) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_5' => ($custom_header_info_config['disp_watermark_at'] == 5) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_6' => ($custom_header_info_config['disp_watermark_at'] == 6) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_7' => ($custom_header_info_config['disp_watermark_at'] == 7) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_8' => ($custom_header_info_config['disp_watermark_at'] == 8) ? 'checked="checked"' : '',

			'S_FORUM_OPTIONS'					=> make_forum_select(1, array(), true, false, false),
			'S_THUMBNAIL'   							=> (@function_exists('gd_info') && (@count(@gd_info()) !== 0)), 
			'S_THUMB_CACHE_ENABLED'		=> $custom_header_info_config['thumb_cache'],
			'HEADER_INFO_PIC_WIDTH'			=> $this->request->variable('header_info_pic_width', 458),
			'HEADER_INFO_PIC_HEIGHT'			=> $this->request->variable('header_info_pic_height', 50),
			'MODULE_NAME'							=> $custom_header_info_config['module_name'], // settings_dbname
			'WYSIWYG_PATH'							=> $custom_header_info_config['wysiwyg_path'],
			'BACKGROUNDS_DIR'					=> $custom_header_info_config['backgrounds_dir'],
			'BANNERS_DIR'		   						=> $custom_header_info_config['banners_dir'],
			'HEADER_INFOVERSION'				=> $custom_header_info_config['header_info_version'],
			'SITE_HOME_URL'   						=> $this->config['board_url'], /* CMS or SITE URL */
			'PHPBB_URL'   								=> generate_board_url() . '/', /* Forum URL */
		));

		$submit = ($this->request->is_set_post('submit')) ? true : false;
		$enable_submit = $this->request->is_set_post('enable_submit');
		$size = ( $this->request->is_set('max_file_size') ) ? $this->request->variable('max_file_size', @ini_get('upload_max_filesizefilesize(')) : '';

		$edit = ($this->request->is_set_post('edit')) ? true : false;
		$edit_id = $this->request->variable('edit', 0);

		//$enabled = $this->request->variable('header_info_enable', 0);

		if ($submit && !check_form_key($form_key) && !$edit)
		{
			trigger_error($this->user->lang['FORM_INVALID']);
		}

		if ($enable_submit && !check_form_key($form_key))
		{
			trigger_error($this->user->lang['FORM_INVALID']);
		}

		if ($submit)
		{
			$name = $this->request->variable('header_info_name', '', true);
			$desc = $this->request->variable('header_info_desc', '', true);
			$longdesc = $this->request->variable('header_info_longdesc', '', true);
			$use_extdesc = $this->request->variable('header_info_use_extdesc', '', true);
			$title_colour = $this->request->variable('header_info_title_colour', '#000000', true);
			$desc_colour = $this->request->variable('header_info_desc_colour', '#12A3EB', true);
			$dir = $this->request->variable('header_info_dir', 'politics', true);
			$type = $this->request->variable('header_info_type', '', true);
			$font = $this->request->variable('header_info_font', '', true);
			$image = $this->request->variable('header_info_image', generate_board_url() . $custom_header_info_config['banners_dir'] . 'custom_header_bg.png');
			$link = $this->request->variable('header_info_image_link', 0);
			$radius = $this->request->variable('header_info_banner_radius', 0);
			$pixels = $this->request->variable('header_info_pixels', 12);
			$title_pixels = $this->request->variable('header_title_info_pixels', 18);
			$desc_pixels = $this->request->variable('header_desc_info_pixels', 10);
			$left = $this->request->variable('header_info_left', 0);
			$right = $this->request->variable('header_info_right', 0);
			$url = $this->request->variable('header_info_url', '');
			$license = $this->request->variable('header_info_license', 'GNU GPL-2');
			$time = $this->request->variable('header_info_time', time());
			$pin = $this->request->variable('header_info_pin', 0);
			$disable = $this->request->variable('header_info_disable', 0);
			$thumb_cache = $this->request->variable('thumb_cache', 0);
			$src_path = str_replace(generate_board_url() . '/', $this->root_path, $image);
			
			$pic_size = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? @GetImageSize($src_path) : array(0 => 458, 1 => 50);
			$pic_width = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? $pic_size[0] : $this->request->variable('header_info_pic_width', 458);
			$pic_height = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? $pic_size[1] : $this->request->variable('header_info_pic_height', 50);

			if($name != '' && $url != '' && $image != '' && !$edit)
			{
				$sql_array = array(
					'header_info_name'				=> $name,
					'header_info_desc'				=> $desc,
					'header_info_longdesc'			=> $longdesc,
					'header_info_use_extdesc'		=> $use_extdesc,
					'header_info_title_colour'		=> $title_colour,
					'header_info_desc_colour'		=> $desc_colour,
					'header_info_dir'					=> $dir, //ext/orynider/customheadernfo/language/movies/
					'header_info_type'					=> $type,
					'header_info_font'					=> $font,
					'header_info_image'				=> $image, //str_replace('prosilver' 'all', $data_files['header_info_image'])
					'header_info_image_link'		=> $link,
					'header_info_banner_radius' 	=> $radius,
					'header_info_pixels'				=> $pixels,
					'header_info_title_pixels'		=> $title_pixels,
					'header_info_desc_pixels'		=> $desc_pixels,
					'header_info_left'					=> $left,
					'header_info_right'				=> $right,
					'header_info_url'					=> $url,
					'header_info_license'				=> $license,
					'header_info_time'				=> time(),
					'header_info_last'					=> 0,
					'header_info_pin'					=> $pin,
					'header_info_pic_width'			=> $pic_width,
					'header_info_pic_height'		=> $pic_height,
					'header_info_disable'				=> $disable, // settings_disable,
					'forum_id'								=> 0,
					'user_id'								=> $this->user->data['user_id'],
					'bbcode_bitfield'					=> 'QQ==',
					'bbcode_uid'							=> '2p5lkzzx',
					'bbcode_options'					=> '',
				);

				$sql = 'INSERT INTO ' . $this->custom_header_info_table . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['HEADER_INFO_ADDED'] . adm_back_link($this->u_action));
			}
			else if($name != '' && $url != '' && $image != '' && $font != '' && isset($edit) && ($edit_id !== 0))
			{
				$sql_array = array(
					'header_info_name'				=> $name,
					'header_info_desc'				=> $desc,
					'header_info_longdesc'			=> $longdesc,
					'header_info_use_extdesc'		=> $use_extdesc,
					'header_info_title_colour'		=> $title_colour,
					'header_info_desc_colour'		=> $desc_colour,
					'header_info_dir'					=> $dir, //ext/orynider/customheadernfo/language/movies/
					'header_info_type'					=> $type,
					'header_info_font'					=> $font,
					'header_info_image'				=> $image, //str_replace('prosilver' 'all', $data_files['header_info_image'])
					'header_info_image_link'		=> $link,
					'header_info_banner_radius' => $radius,
					'header_info_pixels'				=> $pixels,
					'header_info_title_pixels'		=> $title_pixels,
					'header_info_desc_pixels'		=> $desc_pixels,
					'header_info_left'					=> $left,
					'header_info_right'				=> $right,
					'header_info_url'					=> $url,
					'header_info_license'				=> $license,
					'header_info_time'				=> $time,
					'header_info_last'					=> time(),
					'header_info_pin'					=> $pin,
					'header_info_pic_width'			=> $pic_width,
					'header_info_pic_height'		=> $pic_height,
					'header_info_disable'				=> $disable, // settings_disable,
					'forum_id'								=> 0,
					'user_id'								=> $this->user->data['user_id'],
				);

				$sql = 'UPDATE ' . $this->custom_header_info_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE header_info_id = ' . $edit_id;
				//print_r($sql);
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['HEADER_INFO_UDPATED'] . adm_back_link($this->u_action));
			}
			else
			{
				trigger_error($this->user->lang['HEADER_INFO_ERROR'] . adm_back_link($this->u_action . '&amp;action=add'), E_USER_WARNING);
			}
		}

		if ($enable_submit)
		{
			// Update config values this::set_config($key, $new_value)
			//$this->set_config('header_info_enable', $enabled);
			$sql = "SELECT *
				FROM " . $this->custom_header_info_config_table;
			if ( !( $result = $this->db->sql_query($sql) ) )
			{
				$this->message_die( GENERAL_ERROR, 'Couldnt query portal configuration', '', __LINE__, __FILE__, $sql );
			}
			
			while ( $row = $this->db->sql_fetchrow( $result ) )
			{
				// Values for config
				$config_name = $row['config_name'];
				$config_value = trim($row['config_value']); 
				
				$new[$config_name] = ($this->request->is_set($config_name)) ? $this->request->variable($config_name, $config_value) : $config_value;
			
				//$new[$config_name] = trim($config_value);
			
				/* Here we make some checks for the module configuration * /
					if ( ( empty( $size ) ) && ( !$submit ) && ( $config_name == 'max_file_size' ) )
					{
						$size = ( intval( $custom_header_info_config[$config_name] ) >= 1048576 ) ? 'mb' : ( ( intval( $custom_header_info_config[$config_name] ) >= 1024 ) ? 'kb' : 'b' );
					}
					if ( ( !$submit ) && ( $config_name == 'max_file_size' ) )
					{
						if ( $new[$config_name] >= 1048576 )
						{
							$new[$config_name] = round( $new[$config_name] / 1048576 * 100 ) / 100;
						}
						else if ( $new[$config_name] >= 1024 )
						{
							$new[$config_name] = round( $new[$config_name] / 1024 * 100 ) / 100;
						}
					}
					/* Here we make some checks for the module configuration */
					

					/* Here we make some checks for the module configuration * /
					if ( $config_name == 'max_file_size' )
					{
						$new[$config_name] = ( $size == 'kb' ) ? round( $new[$config_name] * 1024 ) : ( ( $size == 'mb' ) ? round( $new[$config_name] * 1048576 ) : $new[$config_name] );
					}
				/* Here we make some checks for the module configuration */
				
				if ($this->request->is_set($config_name) && ($new[$config_name] != $config_value))
				{
					// Update config values this::set_config($key, $new_value)
					$this->set_config($config_name, $new[$config_name]);
				
					// Clear cache
					$this->cache->destroy('custom_header_info_config');
				}
			
				// Log message
				//$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_CONFIG_UPDATED');
				//trigger_error($this->user->lang['HEADER_INFO_CONF_UDPATED'] . adm_back_link($this->u_action));
			}
			
			$this->db->sql_freeresult($result);
			
			$this->cache->put('custom_header_info_config', $new);
			
			// Log message
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_CONFIG_UPDATED');
			trigger_error($this->user->lang['ACP_CONFIG_SUCCESS'] . adm_back_link($this->u_action));
		}

		//
		// General Settings
		//
		$new = $custom_header_info_config;
		
		$module_name = $new['module_name'];

		$wysiwyg_path = $new['wysiwyg_path'];
		$upload_dir = $new['banners_dir'];
		$screenshots_dir = $new['backgrounds_dir'];

		$action = $this->request->variable('action', '');
		$id_header_info = $this->request->variable('id', -1);

		if ($action && $id_header_info != -1)
		{
			$action = $this->request->variable('action', '');
			switch ($action)
			{
				case 'edit':
					$sql = 'SELECT *
						FROM ' . $this->custom_header_info_table . '
						WHERE header_info_id = ' . $id_header_info;
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);

					$header_info_type_select = $this->get_list_static('header_info_type', 
											array('language' => $this->user->lang('MULTI_LANGUAGE_BANNER'),
														'lang_html_text' => $this->user->lang('HTML_MULTI_LANGUAGE_TEXT'), 
														'simple_db_text' => $this->user->lang('SIMPLE_DB_TEXT'), 
														'simple_bg_logo' => $this->user->lang('SIMPLE_BG_LOGO')
														), 
														$row['header_info_type']);

					$header_info_direction_select	= $this->get_list_static('direction', 
											array('up' => $this->user->lang('UP'),
														'down' => $this->user->lang('DOWN')
														), 
														'up');

					//Populate info to display starts
					$info_title = array();
					$info_desc = array();

					$header_info_name = $row['header_info_name'];
					$header_info_desc = $row['header_info_desc'];
					$header_info_longdesc = $row['header_info_longdesc'];

					if ($row['header_info_type'] == 'lang_html_text')
					{
						$header_info_dir = $row['header_info_dir'];
						$header_info_font = $row['header_info_font'];
						
						// populate entries (all lang keys)
						$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : $this->language_from;
						$this->entries = $this->load_lang_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext);

						$i = 0;
						srand ((float) microtime() * 10000000);

						if (count($this->entries) == 0)
						{
							$l_keys[0] = $header_info_name;
							$l_values[0] = $header_info_desc;
							
							$l_keys[1] = $header_info_name;
							$l_values[1] = $header_info_longdesc;
							
							$j = rand(0, 1);
							
							$info_title = $l_keys[$j];
							$info_desc = $l_values[$j];
						}
						else
						{
							$i = count($this->entries);
							$j = rand(0, $i);
							//$j = 4;
							$l_keys = array_keys($this->entries);
							$l_values = array_values($this->entries);
							$info_title = $l_keys[$j];
							$info_desc = $l_values[$j];
						}
						//die(print_r($info_desc, true));
					}
					else
					{
						$header_info_font = isset($row['header_info_font']) ? $row['header_info_font'] : 'tituscbz.ttf';
						
						$l_keys[0] = $header_info_name;
						$l_values[0] = $header_info_desc;
							
						$l_keys[1] = $header_info_name;
						$l_values[1] = $header_info_longdesc;
						
						$j = rand(0, 1);
						
						$info_title = $l_keys[$j];
						$info_desc = $l_values[$j];
					}

					//Populate info to display ends
					$this->template->assign_vars(array(
						'HEADER_INFO_EDIT'					=> $row['header_info_id'],
						'HEADER_INFO_ID'						=> $row['header_info_id'],
						'HEADER_INFO_NAME'					=> $row['header_info_name'],
						'HEADER_INFO_TITLE'					=> $info_title,
						'HEADER_INFO_DESC'					=> $row['header_info_desc'],
						'HEADER_INFO_LONGDESC'			=> $row['header_info_longdesc'],
						'HEADER_INFO_RANDDESC'			=> $info_desc,
						'HEADER_INFO_USE_EXTDESC'		=> $row['header_info_use_extdesc'],
						'EXTENED_SITE_DESC'					=> $row['header_info_use_extdesc'],

						//New 0.9.0 start
						'HEADER_INFO_TITLE_COLOUR'		=> isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '',
						'HEADER_INFO_DESC_COLOUR'		=> isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '',

						//New 0.9.0 ends
						'HEADER_INFO_TYPE'					=> $row['header_info_type'],
						'HEADER_INFO_TYPE_SELECT'		=> $header_info_type_select,
						'HEADER_INFO_DIR'						=> $this->user->lang[$row['header_info_dir']],
						'HEADER_INFO_DIR_SELECT' 		=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', $row['header_info_dir']), //ext/orynider/customheadernfo/language/movies/
						'HEADER_INFO_FONT_SELECT' 		=> $this->gen_fonts_select_list('html', 'header_info_font', $header_info_font), //ext/orynider/customheadernfo/assets/fonts/
						'HEADER_INFO_DB_FONT' 			=> substr($header_info_font, 0, strrpos($header_info_font, '.')),
						'HEADER_INFO_IMAGE'					=> $row['header_info_image'],
						'THUMBNAIL_URL'   						=> generate_board_url() . '/app.php/thumbnail',

						//New 0.9.0 start
						'HEADER_INFO_RADIUS'				=> isset($row['header_info_banner_radius']) ? $row['header_info_banner_radius'] : '',
						'HEADER_INFO_PIXELS'					=> isset($row['header_info_pixels']) ? $row['header_info_pixels'] : '',
						'HEADER_INFO_TITLE_PIXELS'		=> isset($row['header_info_title_pixels']) ? $row['header_info_title_pixels'] : '',
						'HEADER_INFO_DESC_PIXELS'		=> isset($row['header_info_desc_pixels']) ? $row['header_info_desc_pixels'] : '',
						'HEADER_INFO_LEFT'					=> isset($row['header_info_left']) ? $row['header_info_left'] : '',
						'HEADER_INFO_RIGHT'					=> isset($row['header_info_right']) ? $row['header_info_right'] : '',

						//New 0.9.0 ends
						'S_HEADER_INFO_LINK_CHECKED'	=> $row['header_info_image_link'],
						'HEADER_INFO_URL'						=> $row['header_info_url'],
						'HEADER_INFO_LICENSE'				=> $row['header_info_license'],
						'HEADER_INFO_TIME'					=> $row['header_info_time'],
						'HEADER_INFO_LAST'					=> $row['header_info_last'],

						'S_FORUM_OPTIONS'					=> make_forum_select($row['forum_id'], array(), true, false, false),
						'S_HTML_MULTI_TEXT_ENABLED'	=> ($row['header_info_type'] == 'lang_html_text'),
						'S_SIMPLE_DB_TEXT_ENABLED'		=> ($row['header_info_type'] == 'simple_db_text'),
						'S_HEADER_INFO_PIN_CHECKED'	=> $row['header_info_pin'],
						'HEADER_INFO_PIC_WIDTH'			=> $row['header_info_pic_width'],
						'HEADER_INFO_PIC_HEIGHT'			=> $row['header_info_pic_height'],
						'S_HEADER_INFO_DISABLE'			=> $row['header_info_disable'], // settings_disable,
					));
					$this->db->sql_freeresult($result);
				break;
					
				case 'delete':
					if (confirm_box(true))
					{
						$sql = 'DELETE FROM
								' . $this->custom_header_info_table . '
								WHERE header_info_id = ' . $id_header_info;
						$this->db->sql_query($sql);
						redirect($this->u_action);
					}
					else
					{
						confirm_box(false, $this->user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'action'		=> $action,
								'id'	        => $id_header_info))
						);
					}
				break;
			}
		}
	}

	public function display_info()
	{
		$this->user->add_lang('posting');
	}

	/**
	 * Log Message
	 *
	 * @return message
	 * @access private
	*/
	private function log_message($log_message, $title, $user_message)
	{
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $log_message, time(), array($title));

		trigger_error($this->user->lang[$user_message] . adm_back_link($this->u_action));
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

	function manage_pages_header( $page = 1, $depth = 0 )
	{
		// Read out config values
		$pafiledb_config = $this->config_values();
		$this->tpl_name = 'acp_custom_header_pages';
		$action = $this->request->variable('action', '');
		$form_action = $this->u_action. '&amp;action='.$action;
		
		$this->user->lang_mode = $this->user->lang['ACP_MANAGE_PAGES'];

		$page_id = $this->request->is_set('page') ? $this->request->variable('page') : $page;

		//$this->user->add_lang('common');
		
		$this->template->assign_vars(array(
			'BASE'	=> $this->u_action,
		));	

		return;
	}

	/**
	 * This class is used for general pafiledb handling
	 *
	 * @param unknown_type $config_name
	 * @param unknown_type $config_value
	 */
	function set_config($config_name, $config_value)
	{
		$config = array();
		$config[$config_name] = $config_value;
		
		$sql = "UPDATE `" . $this->custom_header_info_config_table . "`
			SET `config_value` = '" . $this->db->sql_escape($config_value) . "'
			WHERE `" . $this->custom_header_info_config_table . "`.`config_name` = '" . $this->db->sql_escape($config_name) . "'";
		if (!@$this->db->sql_query($sql))
		{
			$sql = 'INSERT INTO ' . $this->custom_header_info_config_table . ' ' . $this->db->sql_build_array('INSERT', array(
				'config_name'	=> $config_name,
				'config_value'	=> $config_value));
			if (!@$this->db->sql_query($sql))
			{
				$this->message_die( GENERAL_ERROR, "Failed to update pafiledb configuration for $config_name", "", __LINE__, __FILE__, $sql );
			}
		}
		
		$config[$config_name] = $config_value;
		$this->cache->put('custom_header_info_config', $config);
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function config_values($use_cache = true)
	{
		if (($config = $this->cache->get('custom_header_info_config3')) && ($use_cache))
		{
			return $config;
		}
		else
		{
			$sql = "SELECT *
				FROM " . $this->custom_header_info_config_table;
			if ( !( $result = $this->db->sql_query($sql) ) )
			{
				$this->message_die( GENERAL_ERROR, 'Couldnt query portal configuration', '', __LINE__, __FILE__, $sql );
			}
			while ( $row = $this->db->sql_fetchrow( $result ) )
			{
				$config[$row['config_name']] = trim($row['config_value']);
			}
			$this->db->sql_freeresult($result);
			
			$this->cache->put('custom_header_info_config', $config);
			
			return($config);
		}
	}

	/**
	 * Dummy function
	 */
	function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
	{		
		//
		// Get SQL error if we are debugging. Do this as soon as possible to prevent
		// subsequent queries from overwriting the status of sql_error()
		//
		if (DEBUG && ($msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR))
		{
				
			if ( isset($sql) )
			{
				$sql_error = $this->db->sql_error($sql);
				$sql_error['message'] = $sql_error['message'] ? $sql_error['message'] : '<br /><br />SQL : ' . $sql; 
				$sql_error['code'] = $sql_error['code'] ? $sql_error['code'] : 0;
			}
			else
			{
				$sql_error = $this->db->sql_error_returned;
				$sql_error['message'] = $sql_error['message'] ? $sql_error['message'] : '<br /><br />SQL : ' . $sql; 
				$sql_error['code'] = $sql_error['code'] ? $sql_error['code'] : 0;
			}
			
			$debug_text = '';

			if ( isset($sql_error['message']) )
			{
				$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
			}

			if ( isset($sql_store) )
			{
				$debug_text .= "<br /><br />$sql_store";
			}

			if ( isset($err_line) && isset($err_file) )
			{
				$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
			}
		}
		
		switch($msg_code)
		{
			case GENERAL_MESSAGE:
				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('Information');
				}
			break;

			case CRITICAL_MESSAGE:
				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('Critical_Information');
				}
			break;

			case GENERAL_ERROR:
				if ( $msg_text == '' )
				{
					$msg_text = $this->user->lang('An_error_occured');
				}

				if ( $msg_title == '' )
				{
					$msg_title = $this->user->lang('General_Error');
				}
			break;

			case CRITICAL_ERROR:

				if ($msg_text == '')
				{
					$msg_text = $this->user->lang('A_critical_error');
				}

				if ($msg_title == '')
				{
					$msg_title = 'phpBB : <b>' . $this->user->lang('Critical_Error') . '</b>';
				}
			break;
		}
		
		//
		// Add on DEBUG info if we've enabled debug mode and this is an error. This
		// prevents debug info being output for general messages should DEBUG be
		// set TRUE by accident (preventing confusion for the end user!)
		//
		if ( DEBUG && ( $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) )
		{
			if ( $debug_text != '' )
			{
				$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b> ' . $debug_text;
			}
		}
		
		trigger_error($msg_title . ': ' . $msg_text);
	}
	
	/**
	 * Not Implemented
	 *
	 * @return unknown
	 */
	function manage_forums_header()
	{
		return false;
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
			$lang = str_replace('lang_', '', $lang);
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
				case 'panjabi':
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
		$dir = opendir($this->module_root_path . 'language/');
		while($f = readdir($dir))
		{
			if (($f == '.' || $f == '..') || !is_dir($this->module_root_path . 'language/' . $f) )
			{
				continue;
			}
			
			if ($this->language_from == '')
			{
				$this->language_from = $this->phpbb_cookie($this->ext_name . 'language_from', $f);
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

		if (!file_exists($root_path . 'mx_meta.inc') && !file_exists($root_path . 'mcp'.$php_ext))
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
		
		$lang_files = array();
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
	function phpbb_cookie($name, $value = '')
	{
		$board_config = $this->config; /* cookie_name', 'phpbb3_li1e6', 0 */
		$cookie_board_name = $name;
		$return = '';
		if ($value != '')
		{
			$return = $value;
			// Currently not working under linux machines [Ubuntu GG]
			//setcookie( $cookie_board_name, $value, (time()+21600), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			setcookie( $cookie_board_name, $value, (time() + 21600), $board_config['cookie_path']);
			
			$this->cookie[$cookie_board_name] = $value;
			
		}
		else if(isset($_COOKIE[$cookie_board_name]))
		{
			$value = $this->cookie[$cookie_board_name] = $this->request->variable($cookie_board_name, 0, false, \phpbb\request\request_interface::COOKIE);
			// Currently not working under linux machines [Ubuntu GG]
			//setcookie( $cookie_board_name, $_COOKIE[ $cookie_board_name], (time()+21600), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			setcookie($cookie_board_name, $value, (time() + 21600), $board_config['cookie_path']);
			
		}
		$this->cookie['test' . $name] = $value;		
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
		$list_ary = $this->load_lang_dirs($this->module_root_path, $this->language_from, '', $this->language_into);

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
		$option_list = '<select name="' . $name_select .'" ' . $full_list_true . '>';
		
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

	function get_lang($key)
	{
		global $lang;
		return ((!empty($key) && isset($lang[$key])) ? $lang[$key] : $key);
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

	function get_countries()
	{
		// get all countries installed
		$countries = array();
		$dir = @opendir($this->root_path . 'language');
		while ($file = @readdir($dir))
		{
			if (preg_match('#^lang_#i', $file) && !is_file($this->root_path . 'language/' . $file) && !is_link($this->root_path . 'language/' . $file))
			{
				$filename = trim(str_replace('lang_', '', $file));
				$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
				$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
				$countries[$file] = ucfirst($displayname);
			}
		}
		@closedir($dir);
		@asort($countries);

		return $countries;
	}

	function get_packs()
	{
		global $countries;

		/* MG Lang DB - BEGIN */
		$skip_files = array(('lang_bbcode.' . $this->php_ext), ('lang_faq.' . $this->php_ext), ('lang_rules.' . $this->php_ext));
		/* MG Lang DB - END */

		// get all the extensions installed
		$packs = array();
		
		@reset($countries);
		
		while (list($country_dir, $country_name) = @each($countries))
		{
			$dir = @opendir($this->root_path . 'language/' . $country_dir);
			
			while ($file = @readdir($dir))
			{
				if ( ( $file == '.' || $file == '..') || (substr(strrchr($file, '.'), 1) !== $this->php_ext) || (strpos($file, 'lang_') === false))
				{
					continue;
				}				
				
				$pattern = 'lang_u';
				if (preg_match('/' . $pattern . '/i', $file))
				//if(preg_match("/^lang_user_created.*?\." . $this->php_ext . "$/", $file))
				//if((preg_match("/^lang_user_created.*?\." . $this->php_ext . "$/", $file)) || (preg_match("/^lang_main.*?\." . $this->php_ext . "$/", $file)))
				//if((preg_match("/^lang_user_created.*?\." . $this->php_ext . "$/", $file)) || (preg_match("/^lang_admin.*?\." . $this->php_ext . "$/", $file)))
				//if(preg_match("/^lang_user_created.*?\." . $this->php_ext . "$/", $file))
				{
					/* MG Lang DB - BEGIN */
					if (!in_array($file, $skip_files))
					/* MG Lang DB - END */
					{
						$displayname = $file;
						$packs[$file] = $displayname;
					}
				}
				/* MG Lang DB - BEGIN */
				if(preg_match("/^lang_extend_.*?\." . $this->php_ext . "$/", $file))
				{
					$displayname = trim(str_replace(('.' . $this->php_ext), '', str_replace('lang_extend_', '', $file)));
					$packs[$file] = $displayname;
				}
				/* MG Lang DB - END */
			}
			@closedir($dir);
		}
		/* MG Lang DB - BEGIN */
		/*
		$packs['lang'] = '_phpBB';
		$packs['custom'] = '_custom';
		*/
		/* MG Lang DB - END */
		@asort($packs);

		return $packs;
	}

	function read_one_pack($country_dir, $pack_file, &$entries)
	{
		global $countries, $packs;

		// get filename
		$file = $this->root_path . 'language/' . $country_dir . '/' . $pack_file;
		if (($pack_file != 'lang') && ($pack_file != 'custom') && !file_exists($file))
		{
			//die('This file doesn\'t exist: ' . $file);
			$this->message_die(GENERAL_ERROR, 'This file doesn\'t exist: ' . $file, '', __LINE__, __FILE__, $packs);
		}

		// process first admin then standard keys
		for ($i = 0; $i < 2; $i++)
		{
			$lang_extend_admin = ($i == 0);

			/* MG Lang DB - BEGIN */
			// fix the filename for standard keys
			if ($pack_file == 'lang')
			{
				$file = $this->root_path . 'language/' . $country_dir . '/' . ($lang_extend_admin ? 'lang_admin.' : 'lang_main.') . $this->php_ext;
			}
			// fix the filename for custom keys
			if ($pack_file == 'custom')
			{
				$file = $this->root_path . 'language/' . $country_dir . '/' . 'lang_extend.' . $this->php_ext;
			}
			/* MG Lang DB - END */

			// process
			$lang = array();
			@include($file);
			@reset($lang);
			while (list($key_main, $data) = @each($lang))
			{
				$custom = ($pack_file == 'custom');
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
				}
			}
		}
	}

	function get_entries($modified = true)
	{
		global $config;
		global $countries, $packs;

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
				$pack_file = 'custom';
				$this->read_one_pack($country_dir, $pack_file, $entries);
			}
			/* MG Lang DB - END */

			// add the missing keys in a language
			$default_lang = 'lang_' . $config['default_lang'];
			$english_lang = 'lang_english';
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
		
	/* replacement for eregi($pattern, $string); outputs 0 or 1*/
	function trisstr($pattern = '%{$regex}%i', $string, $matches = '') 
	{      
		return preg_match('/' . $pattern . '/i', $string, $matches);
	}
		
	/* replacement for stripslashes(); */
	function removeslashes($string, $all = true) 
	{  	
		//remove also slashes inside the string
		//or remove only leading and trailing slashes		
		return ($all !== false)  ? str_replace('/', '', $string)  : trim($string, '/');
	}
	
	/* replacement for print_r(array(), true); */
	function array_to_string($val, $all = true) 
	{  	
			if(is_array($val))
			{
				foreach($val as $k => $v)
				{
					$val[$k] = $this->removeslashes($v);
				}
				return $k . ', ' . $v;
			}
			else
			{
				$val = $this->removeslashes($val);
				return $val;
			}
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

		$string = str_replace($array_find, $array_replace, $this->array_to_string($string, true));
		return $string;
	}
}
// THE END
?>