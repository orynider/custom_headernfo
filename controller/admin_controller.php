<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/

namespace orynider\custom_headernfo\controller;

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

		$this->ext_name 			= $this->request->variable('ext_name', 'orynider/pafiledb');
		$this->module_root_path		= $this->ext_path = $this->ext_manager->get_extension_path($this->ext_name, true);
		$this->ext_path_web			= $this->path_helper->update_web_root_path($this->module_root_path);


		if (!class_exists('parse_message'))
		{
			include($this->root_path . 'includes/message_parser.' . $this->php_ext);
		}

		global $debug;

		// Read out config values
		//$custom_header_info_config = $this->config_values();
		$this->backend = $this->confirm_backend();

		//print_r($custom_header_info_config);
	}

	public function manage_header_info_config()
	{
		$form_action = $this->u_action . '&amp;action=add';

		$this->table = $this->custom_header_info_table;

		$this->tpl_name = 'acp_custom_header_info';
		$this->page_title = $this->user->lang('HEADER_INFO_TITLE');
		
		$form_key = 'acp_header_info';
		add_form_key($form_key);

		$sql = 'SELECT *
	            FROM ' . $this->custom_header_info_table . '
	            ORDER BY header_info_id ASC';
		$result = $this->db->sql_query($sql);
		while( $row = $this->db->sql_fetchrow($result) )
		{
			$this->template->assign_block_vars('header_info_scroll', array(
				'HEADER_INFO_ID'							=> $row['header_info_id'],
				'HEADER_INFO_NAME'					=> $row['header_info_name'],
				'HEADER_INFO_DESC'						=> $row['header_info_desc'],
				'HEADER_INFO_LONGDESC'				=> $row['header_info_longdesc'],
				'HEADER_INFO_TYPE'						=> $row['header_info_type'],
				'HEADER_INFO_DIR'						=> $row['header_info_dir'], //ext/orynider/custom_headernfo/language/movies/
				'HEADER_INFO_IMAGE'					=> $row['header_info_image'],
				'THUMBNAIL_URL'   						=> generate_board_url() . '/app.php/thumbnail',
				'S_HEADER_INFO_LINK_CHECKED'	=> $row['header_info_link'],
				'HEADER_INFO_URL'						=> $row['header_info_url'],
				'HEADER_INFO_LICENSE'					=> $row['header_info_license'],
				'HEADER_INFO_TIME'						=> $row['header_info_time'],
				'HEADER_INFO_LAST'						=> $row['header_info_last'],
				'S_HEADER_INFO_PIN_CHECKED'		=> $row['header_info_pin'],
				'S_HEADER_INFO_DISABLE'				=> $row['header_info_disable'], // settings_disable,
				'U_EDIT'										=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=edit",
				'U_DELETE'									=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=delete"
			));
		}
		$this->db->sql_freeresult($result);

		// Read out config values
		$custom_header_info_config = $this->config_values();
		
		$this->template->assign_vars(array(
			'S_HEADER_INFO_ENABLED'   => $custom_header_info_config['header_info_enable'], // settings_disable
			'S_HEADER_INFO_POSITION1'	=> $custom_header_info_config['banner_position1'],
			'S_HEADER_INFO_POSITION2'	=> $custom_header_info_config['banner_position2'],
			'S_HEADER_INFO_POSITION3'	=> $custom_header_info_config['banner_position3'],
			'SHOW_AMOUNT'				   	=> $custom_header_info_config['show_amount'],
			'S_THUMBNAIL'   					=> (@function_exists('gd_info') && (@count(@gd_info()) !== 0)), 
			'MODULE_NAME'				  => $custom_header_info_config['module_name'], // settings_dbname
			'WYSIWYG_PATH'				   => $custom_header_info_config['wysiwyg_path'],
			'BACKGROUNDS_DIR'			   => $custom_header_info_config['backgrounds_dir'],
			'BANNERS_DIR'		   				=> $custom_header_info_config['banners_dir'],
			'HEADER_INFOVERSION'			=> $custom_header_info_config['header_info_version'],
			'SITE_HOME_URL'   				=> $this->config['site_home_url'], //PORTAL_URL
			'PHPBB_URL'   						=> generate_board_url() . '/', //FORUM_URL
			'READONLY'							=> ' readonly="readonly"'
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
			$dir = $this->request->variable('header_info_dir', '', true);
			$type = $this->request->variable('header_info_type', '', true);
			$image = $this->request->variable('header_info_image', generate_board_url() . $custom_header_info_config['banners_dir'] . 'custom_header_info.png');
			$link = $this->request->variable('header_info_link', 0);
			$url = $this->request->variable('header_info_url', '');
			$license = $this->request->variable('header_info_license', 'GNU GPL-2');
			$time = $this->request->variable('header_info_time', time());
			$pin = $this->request->variable('header_info_pin', 0);
			$disable = $this->request->variable('header_info_disable', 0);
				
			if($name != '' && $url != '' && $image != '' && !$edit)
			{
				$sql_array = array(
					'header_info_name'			=> $name,
					'header_info_desc'			=> $desc,
					'header_info_longdesc'		=> $longdesc,
					'header_info_dir'				=> $dir, //ext/orynider/custom_headernfo/language/movies/
					'header_info_type'				=> $type,
					'header_info_image'			=> $image, //str_replace('prosilver' 'all', $data_files['header_info_image'])
					'header_info_image_link'	=> $link,
					'header_info_url'				=> $url,
					'header_info_license'			=> $license,
					'header_info_time'			=> time(),
					'header_info_last'				=> 0,
					'header_info_pin'				=> $pin,
					'header_info_disable'			=> $disable, // settings_disable,
					'user_id'							=> $user->data['user_id'],
					'bbcode_bitfield'				=> 'QQ==',
					'bbcode_uid'					=> '2p5lkzzx',
					'bbcode_options'				=> '',
				);

				$sql = 'INSERT INTO ' . $this->custom_header_info_table . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
				$this->db->sql_query($sql);
				trigger_error($user->lang['HEADER_INFO_ADDED'] . adm_back_link($this->u_action));
			}
			else if($name != '' && $url != '' && $image != '' && isset($edit) && !empty($edit_id))
			{
				$sql_array = array(
					'header_info_name'			=> $name,
					'header_info_desc'			=> $desc,
					'header_info_longdesc'		=> $longdesc,
					'header_info_dir'				=> $dir, //ext/orynider/custom_headernfo/language/movies/
					'header_info_type'				=> $type,
					'header_info_image'			=> $image, //str_replace('prosilver' 'all', $data_files['header_info_image'])
					'header_info_image_link'	=> $link,
					'header_info_url'				=> $url,
					'header_info_license'			=> $license,
					'header_info_time'			=> $time,
					'header_info_last'				=> time(),
					'header_info_pin'				=> $pin,
					'header_info_disable'			=> $disable, // settings_disable,
					'user_id'							=> $user->data['user_id'],
				);

				$sql = 'UPDATE ' . $this->custom_header_info_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE header_info_id = ' . $edit_id;
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
		$module_name = $new['module_name'];

		$enable_module_yes = ( $new['enable_module'] ) ? "checked=\"checked\"" : "";
		$enable_module_no = ( !$new['enable_module'] ) ? "checked=\"checked\"" : "";

		$wysiwyg_path = $new['wysiwyg_path'];
		$upload_dir = $new['upload_dir'];
		$screenshots_dir = $new['screenshots_dir'];
			
		$action = $this->request->variable('action', '');
		$id_header_info = $this->request->variable('id', 0);
		
		if ($action && $id_header_info != 0)
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
					
					$this->template->assign_vars(array(
						'HEADER_INFO_EDIT'						=> $row['header_info_id'],
						'HEADER_INFO_ID'							=> $row['header_info_id'],
						'HEADER_INFO_NAME'					=> $row['header_info_name'],
						'HEADER_INFO_DESC'						=> $row['header_info_desc'],
						'HEADER_INFO_LONGDESC'				=> $row['header_info_longdesc'],
						'HEADER_INFO_TYPE'						=> $row['header_info_type'],
						'HEADER_INFO_DIR'						=> $row['header_info_dir'], //ext/orynider/custom_headernfo/language/movies/
						'HEADER_INFO_IMAGE'					=> $row['header_info_image'],
						'THUMBNAIL_URL'   						=> generate_board_url() . '/app.php/thumbnail',
						'S_HEADER_INFO_LINK_CHECKED'	=> $row['header_info_link'],
						'HEADER_INFO_URL'						=> $row['header_info_url'],
						'HEADER_INFO_LICENSE'					=> $row['header_info_license'],
						'HEADER_INFO_TIME'						=> $row['header_info_time'],
						'HEADER_INFO_LAST'						=> $row['header_info_last'],
						'S_HEADER_INFO_PIN_CHECKED'		=> $row['header_info_pin'],
						'S_HEADER_INFO_DISABLE'				=> $row['header_info_disable'], // settings_disable,
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

		/* Define the tokens from the symbol table, just in case are not compiled in PHP5  */
		if(!defined('T_CONCAT_EQUAL'))
		{
			@define('T_CONCAT_EQUAL', 275);
			@define('T_STRING', 310);
			@define('T_OBJECT_OPERATOR', 363);
			@define('T_VARIABLE', 312);	
			@define('T_CONSTANT_ENCAPSED_STRING', 318);	
			@define('T_LNUMBER', 308);	
			@define('T_IF', 304);
			@define('T_ELSE', 306);
			@define('T_ELSEIF', 305);
			@define('T_WHITESPACE', 379);
			@define('T_FOR', 323);
			@define('T_FOREACH', 325);
			@define('T_WHILE', 321);
			@define('T_COMMENT', 374);
			@define('T_DOC_COMMENT', 375);				
		}		
		
		// Setup message parser
		$this->message_parser = new \parse_message();

		$action 		= $this->request->is_set_post('submit');
		$cat_id			= $this->request->variable('cat_id', 0);
		$form_action 	= $this->u_action. '&amp;action=add';
		$this->user->lang_mode 		= $this->user->lang['ACP_ADD'];

		// Read out config values
		$pafiledb_config = $this->functions->config_values();

		$start	= $this->request->variable('start', 0);
		$number	= $pafiledb_config['pagination_acp'];

		$this->template->assign_vars(array(
			'BASE'	=> $this->u_action,
		));

		$sort_days	= $this->request->variable('st', 0);
		$sort_key	= $this->request->variable('sk', 'file_name');
		$sort_dir	= $this->request->variable('sd', 'ASC');
		$limit_days = array(0 => $this->user->lang['ACP_ALL_DOWNLOADS'], 1 => $this->user->lang['1_DAY'], 7 => $this->user->lang['7_DAYS'], 14 => $this->user->lang['2_WEEKS'], 30 => $this->user->lang['1_MONTH'], 90 => $this->user->lang['3_MONTHS'], 180 => $this->user->lang['6_MONTHS'], 365 => $this->user->lang['1_YEAR']);

		$sort_by_text = array('t' => $this->user->lang['ACP_SORT_TITLE'], 'c' => $this->user->lang['ACP_SORT_CAT']);
		$sort_by_sql = array('t' => 'file_name', 'c' => 'cat_name');

		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
		$sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		// Total number of downloads
		$sql = 'SELECT COUNT(file_id) AS total_downloads
			FROM ' . $this->pa_files_table;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$total_downloads = $row['total_downloads'];
		$this->db->sql_freeresult($result);

		// List all downloads
		$sql = 'SELECT d.*, c.*
			FROM ' . $this->pa_files_table . ' d
			LEFT JOIN ' . $this->pa_cat_table . ' c
				ON d.file_catid = c.cat_id
			ORDER BY '. $sql_sort_order;
		$result = $this->db->sql_query_limit($sql, $number, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->message_parser->message = $row['file_desc'];
			$this->message_parser->bbcode_bitfield = $row['bbcode_bitfield'];
			$this->message_parser->bbcode_uid = $row['bbcode_uid'];
			$allow_bbcode = $allow_magic_url = $allow_smilies = true;
			$this->message_parser->format_display($allow_bbcode, $allow_magic_url, $allow_smilies);

			$this->template->assign_block_vars('downloads', array(
				'ICON_COPY'		=> '<img src="' . $this->root_path . 'adm/images/file_new.gif" alt="' . $this->user->lang['ACP_COPY_NEW'] . '" title="' . $this->user->lang['ACP_COPY_NEW'] . '" />',
				'TITLE'			=> $row['file_name'],
				'FILENAME'		=> $row['real_name'],
				'DESC'			=> $this->message_parser->message,
				'VERSION'		=> $row['file_version'],
				'DL_COST'		=> ($row['cost_per_dl'] == 0 ? $this->user->lang['ACP_COST_FREE'] : $row['cost_per_dl']),
				'SUB_DIR'		=> $row['cat_sub_dir'],
				'CATNAME'		=> $row['cat_name'],
				'U_COPY'		=> $this->u_action . '&amp;action=copy_new&amp;file_id=' .$row['file_id'],
				'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;file_id=' .$row['file_id'],
				'U_DEL'			=> $this->u_action . '&amp;action=delete&amp;file_id=' .$row['file_id'],
			));
		}
		$this->db->sql_freeresult($result);

		$base_url = $this->u_action;
		//Start pagination
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_downloads, $number, $start);

		$this->template->assign_vars(array(
			'S_DOWNLOAD_ACTION' => $this->u_action,
			'S_SELECT_SORT_DIR'	=> $s_sort_dir,
			'S_SELECT_SORT_KEY'	=> $s_sort_key,
			'TOTAL_DOWNLOADS'	=> ($total_downloads == 1) ? $this->user->lang['ACP_SINGLE_DOWNLOAD'] : sprintf($this->user->lang['ACP_MULTI_DOWNLOAD'], $total_downloads),
			'U_NEW_DOWNLOAD'	=> $this->u_action . '&amp;action=new_download',
			'L_MODE_TITLE'		=> $this->user->lang_mode,
			'U_EDIT_ACTION'		=> $this->u_action,
		));
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
	
	/**
	 * load admin module
	 *
	 * @param unknown_type $module_name send module name to load it
	 */
	function adminmodule($module_name)
	{
		if (!class_exists('pafiledb_' . $module_name) )
		{
			$this->module_name = $module_name;

			require_once( $this->module_root_path . 'acp/admin_' . $module_name . '.' . $this->php_ext );
			eval( '$this->modules[' . $module_name . '] = new pafiledb_' . $module_name . '();' );

			if ( method_exists( $this->modules[$module_name], 'init' ) )
			{
				$this->modules[$module_name]->init();
			}
			/*
			elseif ( method_exists( $this->modules[$module_name], $module_name ) )
			{
				$this->modules[$module_name]->$module_name();
			}
			*/
		}
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
				//$sql_error = array(@print_r(@$this->db->sql_error($sql)));
				$sql_error['message'] = $sql_error['message'] ? $sql_error['message'] : '<br /><br />SQL : ' . $sql; 
				$sql_error['code'] = $sql_error['code'] ? $sql_error['code'] : 0;
			}
			else
			{
				$sql_error = array(@print_r(@$this->db->sql_error_returned));
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
	
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function manage_forums_header()
	{
		return false;
	}	
}
