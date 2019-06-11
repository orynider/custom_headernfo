<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/

namespace orynider\customheadernfo\controller;
use orynider\customheadernfo\core\customheadernfo;

class admin_controller extends \orynider\customheadernfo\core\admin_controller
{
	public function manage_header_info_config()
	{
		$form_action = $this->u_action . '&amp;action=add';

		$this->tpl_name = 'acp_custom_header_info';
		$this->page_title = $this->language->lang('HEADER_INFO_TITLE');

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
											array('language' => $this->language->lang('MULTI_LANGUAGE_BANNER'),
													'lang_html_text' => $this->language->lang('HTML_MULTI_LANGUAGE_TEXT'), 
													'simple_db_text' => $this->language->lang('SIMPLE_DB_TEXT'), 
													'simple_bg_logo' => $this->language->lang('SIMPLE_BG_LOGO'),
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
				$this->language_into = is_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext) ? $this->language_into : $this->language_from;
				$this->entries = $this->load_lang_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext);
				
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
			
			//Populate info to display ends  strating with name or description of the header info in ACP Preview
			$this->template->assign_block_vars('header_info_scroll', array(
				'HEADER_INFO_ID'							=> $row['header_info_id'],
				'HEADER_INFO_EDIT'						=> $row['header_info_id'] -1,
				'HEADER_INFO_NAME'						=> $row['header_info_name'],
				'HEADER_INFO_TITLE'						=> $info_title,
				'HEADER_INFO_DESC'						=> $row['header_info_desc'],
				'HEADER_INFO_LONGDESC'				=> $row['header_info_longdesc'],
				'HEADER_INFO_USE_EXTDESC'			=> $row['header_info_use_extdesc'],
				'EXTENED_SITE_DESC'						=> $row['header_info_use_extdesc'],
				'HEADER_INFO_RANDDESC'				=> $info_desc,
				'HEADER_INFO_TYPE_SELECT'			=> $header_info_type_select,
				'HEADER_INFO_DIR'							=> $this->language->lang($row['header_info_dir']),
				'HEADER_INFO_TYPE'						=> $row['header_info_type'],
				'HEADER_INFO_DIR_SELECT' 			=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', $row['header_info_dir']), 
				'HEADER_INFO_FONT_SELECT' 		=> $this->gen_fonts_select_list('html', 'header_info_font', $row['header_info_font']), 
				'HEADER_INFO_DB_FONT' 				=> substr($header_info_font, 0, strrpos($header_info_font, '.')),
				'HEADER_INFO_IMAGE'						=> $row['header_info_image'],
				'THUMBNAIL_URL'   							=> generate_board_url() . '/app.' . $this->php_ext . '/thumbnail',
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
				'S_HEADER_INFO_DISABLE'			=> $row['header_info_disable'], 
				'U_EDIT'										=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=edit",
				'U_DELETE'									=> $this->u_action . "&amp;id=" . $row['header_info_id'] . "&amp;action=delete"
			));
		}
		$this->db->sql_freeresult($result);
		
		$header_info_type_select = $this->get_list_static('header_info_type', 
			array('language' => $this->language->lang('MULTI_LANGUAGE_BANNER'),
					'lang_html_text' => $this->language->lang('HTML_MULTI_LANGUAGE_TEXT'), 
					'simple_db_text' => $this->language->lang('SIMPLE_DB_TEXT'), 
					'simple_bg_logo' => $this->language->lang('SIMPLE_BG_LOGO')
					), 
				'language');
		$header_info_direction_select	= $this->get_list_static('header_info_direction', 
			array('up' => $this->language->lang('UP'),
					'down' => $this->language->lang('DOWN')
					), 
				$this->config['header_info_direction']);

		$this->template->assign_vars(array(
			'S_HEADER_INFO_ENABLED'   		=> $this->config['header_info_enable'], 
			'S_HEADER_INFO_POSITION1'		=> $this->config['header_info_banner_position1'],
			'S_HEADER_INFO_POSITION2'		=> $this->config['header_info_banner_position2'],
			'S_HEADER_INFO_POSITION3'		=> $this->config['header_info_banner_position3'],
			'S_HEADER_INFO_POSITION4'		=> $this->config['header_info_banner_position'],
			'HEADER_INFO_TYPE_SELECT'		=> $header_info_type_select,
			'HEADER_INFO_DIR_SELECT' 		=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', 'politics'), /* ext/orynider/customheadernfo/language/movies/ */
			'HEADER_INFO_FONT_SELECT'		=> $this->gen_fonts_select_list('html', 'header_info_font', ''), /* ext/orynider/customheadernfo/assets/fonts/ */
			'HEADER_INFO_IMAGE'					=> generate_board_url() . '/' . $this->config['header_info_banners_dir'] . 'custom_header_bg.png',

			'ROW_HEIGHT'								=> $this->config['header_info_row_height'],		/* Height of each ticker row in PX. Should be uniform. */
			'SPEED'											=> $this->config['header_info_speed'],		/* Speed of transition animation in milliseconds */
			'INTERVAL'									=> $this->config['header_info_interval'],		/* Time between change in milliseconds */
			'SHOW_AMOUNT'						=> $this->config['header_info_show_amount'],		/* Integer for how many items to query and display at once. Resizes height accordingly (OPTIONAL) */
			'S_MOUSESTOP_ENABLED'			=> $this->config['header_info_mousestop'],		/* If set to true, the ticker will stop on mouseover */
			'DIRECTION_SELECT'						=> $header_info_direction_select,		/* Direction that list will scroll */

			/*--------------------
			* WaterMark Section
			* From admin_album_clown_SP.php
			* Credits: clown@pimprig.com, Volodymyr (CLowN) Skoryk
			*--------------------*/
			'S_WATERMARK_ENABLED' => $this->config['header_info_use_watermark'],
			'WATERMARK_ENABLED' => ($this->config['header_info_use_watermark'] == 1) ? 'checked="checked"' : '',
			'WATERMARK_DISABLED' => ($this->config['header_info_use_watermark'] == 0) ? 'checked="checked"' : '',

			'WATERMAR_PLACEMENT_AT' => $this->config['header_info_disp_watermark_at'],
			'WATERMAR_PLACEMENT_0' => ($this->config['header_info_disp_watermark_at'] == 0) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_1' => ($this->config['header_info_disp_watermark_at'] == 1) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_2' => ($this->config['header_info_disp_watermark_at'] == 2) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_3' => ($this->config['header_info_disp_watermark_at'] == 3) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_4' => ($this->config['header_info_disp_watermark_at'] == 4) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_5' => ($this->config['header_info_disp_watermark_at'] == 5) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_6' => ($this->config['header_info_disp_watermark_at'] == 6) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_7' => ($this->config['header_info_disp_watermark_at'] == 7) ? 'checked="checked"' : '',
			'WATERMAR_PLACEMENT_8' => ($this->config['header_info_disp_watermark_at'] == 8) ? 'checked="checked"' : '',

			'S_FORUM_OPTIONS'					=> make_forum_select(1, array(), true, false, false),
			'S_THUMBNAIL'   							=> (@function_exists('gd_info') && (@count(@gd_info()) !== 0)), 
			'S_THUMB_CACHE_ENABLED'		=> $this->config['header_info_thumb_cache'],
			'HEADER_INFO_PIC_WIDTH'			=> $this->request->variable('header_info_pic_width', 458),
			'HEADER_INFO_PIC_HEIGHT'			=> $this->request->variable('header_info_pic_height', 50),
			'MODULE_NAME'							=> $this->config['header_info_module_name'], 
			'WYSIWYG_PATH'							=> $this->config['header_info_wysiwyg_path'],
			'BACKGROUNDS_DIR'					=> $this->config['header_info_backgrounds_dir'],
			'BANNERS_DIR'		   						=> $this->config['header_info_banners_dir'],
			'HEADER_INFOVERSION'				=> $this->config['header_info_version'],
			'SITE_HOME_URL'   						=> $this->config['header_info_board_url'], /* CMS or SITE URL */
			'PHPBB_URL'   								=> generate_board_url() . '/', /* Forum URL */
		));

		$submit = ($this->request->is_set_post('submit')) ? true : false;
		$enable_submit = $this->request->is_set_post('enable_submit');
		$size = ( $this->request->is_set('max_file_size') ) ? $this->request->variable('max_file_size', @ini_get('upload_max_filesizefilesize(')) : '';

		$edit = ($this->request->is_set_post('edit')) ? true : false;
		$edit_id = $this->request->variable('edit', 0);

		if ($submit && !check_form_key($form_key) && !$edit)
		{
			trigger_error($this->language->lang('FORM_INVALID'));
		}

		if ($enable_submit && !check_form_key($form_key))
		{
			trigger_error($this->language->lang('FORM_INVALID'));
		}

		if ($submit)
		{
			$image = $this->request->variable('header_info_image', generate_board_url() . $this->config['header_info_banners_dir'] . 'custom_header_bg.png');
			$thumb_cache = $this->request->variable('header_info_thumb_cache', 0);
			$src_path = str_replace(generate_board_url() . '/', $this->root_path, $image);
			
			$pic_size = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? @GetImageSize($src_path) : array(0 => 458, 1 => 50);
			$pic_width = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? $pic_size[0] : $this->request->variable('header_info_pic_width', 458);
			$pic_height = (@function_exists('gd_info') && (@count(@gd_info()) !== 0)) ? $pic_size[1] : $this->request->variable('header_info_pic_height', 50);

			$is_name = $this->request->is_set_post('header_info_name') ? true : false;
			$is_url = $this->request->is_set_post('header_info_url') ? true : false;
			$is_image = $this->request->is_set_post('header_info_image') ? true : false;
			$is_font= $this->request->is_set_post('header_info_font') ? true : false;
			
			if (isset($is_name) && isset($is_url) && isset($is_image) && !$edit)
			{
				$sql_array = array(
					'header_info_name'			=> $this->request->variable('header_info_name', '', true),
					'header_info_desc'				=> $this->request->variable('header_info_desc', '', true),
					'header_info_longdesc'			=> $this->request->variable('header_info_longdesc', '', true),
					'header_info_use_extdesc'	=> $this->request->variable('header_info_use_extdesc', '', true),
					'header_info_title_colour'		=> $this->request->variable('header_info_title_colour', '#000000', true),
					'header_info_desc_colour'		=> $this->request->variable('header_info_desc_colour', '#12A3EB', true),
					'header_info_dir'					=> $this->request->variable('header_info_dir', 'politics', true), /* i.e. ext/orynider/customheadernfo/language/movies/ */
					'header_info_type'					=> $this->request->variable('header_info_type', 'language', true),
					'header_info_font'					=> $this->request->variable('header_info_font', '', true),
					'header_info_image'				=> $image, /* We can replace 'prosilver' with 'all': str_replace('prosilver' 'all', $image) */
					'header_info_image_link'		=> $this->request->variable('header_info_image_link', 0),
					'header_info_banner_radius' 	=> $this->request->variable('header_info_banner_radius', 0),
					'header_info_pixels'				=> $this->request->variable('header_info_pixels', 12),
					'header_info_title_pixels'		=> $this->request->variable('header_title_info_pixels', 18),
					'header_info_desc_pixels'		=> $this->request->variable('header_desc_info_pixels', 10),
					'header_info_left'					=> $this->request->variable('header_info_left', 0),
					'header_info_right'				=> $this->request->variable('header_info_right', 0),
					'header_info_url'					=> $this->request->variable('header_info_url', ''),
					'header_info_license'				=> $this->request->variable('header_info_license', 'GNU GPL-2'),
					'header_info_time'				=> time(),
					'header_info_last'					=> 0,
					'header_info_pin'					=> $this->request->variable('header_info_pin', 0),
					'header_info_pic_width'			=> $pic_width,
					'header_info_pic_height'		=> $pic_height,
					'header_info_disable'				=> $this->request->variable('header_info_disable', 0), /* settings_disable */
					'forum_id'								=> 0,
					'user_id'								=> $this->user->data['user_id'],
					'bbcode_bitfield'					=> 'QQ==',
					'bbcode_uid'							=> '2p5lkzzx',
					'bbcode_options'					=> '',
				);

				$sql = 'INSERT INTO ' . $this->custom_header_info_table . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
				$this->db->sql_query($sql);
				trigger_error($this->language->lang('HEADER_INFO_ADDED') . adm_back_link($this->u_action));
			}
			else if (isset($is_name) && isset($is_url) && isset($is_image) && isset($is_font) && isset($edit) && ($edit_id !== 0))
			{
				$sql_array = array(
					'header_info_name'			=> $this->request->variable('header_info_name', '', true),
					'header_info_desc'				=> $this->request->variable('header_info_desc', '', true),
					'header_info_longdesc'			=> $this->request->variable('header_info_longdesc', '', true),
					'header_info_use_extdesc'	=> $this->request->variable('header_info_use_extdesc', '', true),
					'header_info_title_colour'		=> $this->request->variable('header_info_title_colour', '#000000', true),
					'header_info_desc_colour'		=> $this->request->variable('header_info_desc_colour', '#12A3EB', true),
					'header_info_dir'					=> $this->request->variable('header_info_dir', 'politics', true), 
					'header_info_type'					=> $this->request->variable('header_info_type', '', true),
					'header_info_font'					=> $this->request->variable('header_info_font', '', true),
					'header_info_image'				=> $image, /* We can replace 'prosilver' with 'all': str_replace('prosilver' 'all', $image) */ 
					'header_info_image_link'		=> $this->request->variable('header_info_image_link', 0),
					'header_info_banner_radius' => $this->request->variable('header_info_banner_radius', 0),
					'header_info_pixels'				=> $this->request->variable('header_info_pixels', 12),
					'header_info_title_pixels'		=> $this->request->variable('header_title_info_pixels', 18),
					'header_info_desc_pixels'		=> $this->request->variable('header_desc_info_pixels', 10),
					'header_info_left'					=> $this->request->variable('header_info_left', 0),
					'header_info_right'				=> $this->request->variable('header_info_right', 0),
					'header_info_url'				=> $this->request->variable('header_info_url', ''),
					'header_info_license'			=> $this->request->variable('header_info_license', 'GNU GPL-2'),
					'header_info_time'				=> $this->request->variable('header_info_time', time()),
					'header_info_last'				=> time(),
					'header_info_pin'				=> $this->request->variable('header_info_pin', 0),
					'header_info_pic_width'	=> $pic_width,
					'header_info_pic_height'	=> $pic_height,
					'header_info_disable'		=> $this->request->variable('header_info_disable', 0),
					'forum_id'							=> 0,
					'user_id'								=> $this->user->data['user_id'],
				);

				$sql = 'UPDATE ' . $this->custom_header_info_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE header_info_id = ' . $edit_id;
				$this->db->sql_query($sql);
				trigger_error($this->language->lang('HEADER_INFO_UDPATED') . adm_back_link($this->u_action));
			}
			else
			{
				trigger_error($this->language->lang('HEADER_INFO_ERROR') . ' '. $this->language->lang('ERROR')  . $this->language->lang('COLON') . ' ' . $is_name . $is_url . $is_image . $is_font . $edit . $edit_id . adm_back_link($this->u_action . '&amp;action=add'), E_USER_WARNING);
			}
		}

		if ($enable_submit)
		{
			// Update Configuration 
			foreach ($this->config as $config_name => $config_value)
			{
				if (strpos($config_name, 'header_info') === 0)
				{
					// Values for config
					$new[$config_name] = ($this->request->is_set($config_name)) ? $this->request->variable($config_name, $config_value) : $config_value;					
					
					/* Here we make some checks for the module configuration */			
					if ($this->request->is_set($config_name) && ($new[$config_name] != $config_value))
					{
						//This if is set does update or sets the config entry 
						$this->config->set_atomic($config_name, false, $new[$config_name], true);
					}		
					
					if (!($new))
					{
						trigger_error($this->language->lang('COULDNT_GET') . ' ' . $this->ext_name . ' ' . $this->language->lang('CONFIG'), E_USER_ERROR);
					}
				}					
			}		
			
			// Log message
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_CONFIG_UPDATED');
			trigger_error($this->language->lang('ACP_CONFIG_SUCCESS') . adm_back_link($this->u_action));
		}

		//
		// General Settings
		//
		$new = $this->config;
		
		$module_name = $new['header_info_module_name'];

		$wysiwyg_path = $new['header_info_wysiwyg_path'];
		$upload_dir = $new['header_info_banners_dir'];
		$screenshots_dir = $new['header_info_backgrounds_dir'];

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
						array('language' => $this->language->lang('MULTI_LANGUAGE_BANNER'),
							'lang_html_text' => $this->language->lang('HTML_MULTI_LANGUAGE_TEXT'), 
							'simple_db_text' => $this->language->lang('SIMPLE_DB_TEXT'), 
							'simple_bg_logo' => $this->language->lang('SIMPLE_BG_LOGO')
							), 
							$row['header_info_type']
						);
					
					$header_info_direction_select = $this->get_list_static('header_info_direction', 
						array('up' => $this->language->lang('UP'),
							'down' => $this->language->lang('DOWN')
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
						$this->language_into = is_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext) ? $this->language_into : $this->language_from;
						$this->entries = $this->load_lang_file($this->module_root_path . customheadernfo::MULTILANG_DIR . $this->language_into . '/' . $header_info_dir . 'common.' . $this->php_ext);

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
							$l_keys = array_keys($this->entries);
							$l_values = array_values($this->entries);
							$info_title = $l_keys[$j];
							$info_desc = $l_values[$j];
						}
						
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

					//Populate info to display ends when edit header info item to database
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
						'HEADER_INFO_DIR'						=> $this->language->lang($row['header_info_dir']),
						'HEADER_INFO_DIR_SELECT' 		=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', $row['header_info_dir']), /* for ex. ext/orynider/customheadernfo/language/movies/ */
						'HEADER_INFO_FONT_SELECT' 	=> $this->gen_fonts_select_list('html', 'header_info_font', $header_info_font), /* for ex. ext/orynider/customheadernfo/assets/fonts/ */
						'HEADER_INFO_DB_FONT' 			=> substr($header_info_font, 0, strrpos($header_info_font, '.')),
						'HEADER_INFO_IMAGE'					=> $row['header_info_image'],
						'THUMBNAIL_URL'   						=> generate_board_url() . '/app.'.$this->php_ext.'/thumbnail',

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
						'S_HEADER_INFO_DISABLE'			=> $row['header_info_disable'], 
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
						confirm_box(false, $this->language->lang('CONFIRM_OPERATION'), build_hidden_fields(array(
								'action'		=> $action,
								'id'	        => $id_header_info))
						);
					}
				break;
			}
		}
		else
		{				
			//Query DB to populate info to display when we add a new banner not from 'in construction' id=1
			$sql = 'SELECT *
						FROM ' . $this->custom_header_info_table;
			$result = $this->db->sql_query($sql);
			$rows = $this->db->sql_fetchrowset($result);
			// Define sample article data from db_install
			$row_disable = array(
			array(
				'header_info_id'				=> 0,
				'header_info_name'			=> 'Board Disabled',
				'header_info_desc'			=> 'Board Disabled Info for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'This is the Board Disabled Logo for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0c6a99',
				'header_info_dir'				=> 'politics', 
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'simple_bg_logo',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/under_construction.gif', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'	=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '0',
				'header_info_pic_width'		=> '458',
				'header_info_pic_height'	=> '193',
				'header_info_disable'			=> 0,
				'forum_id'							=> 1,
				'user_id'							=> $this->user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			) );
			
			//Merge disable with db rows
			$count = count($rows);
			$i = ($count == 0) ? 0 : $count -1;
			$rows = ($count == 0) ? $row_disable : $rows;
			
			$new_id = isset($rows[$i]['header_info_id']) ? $rows[$i]['header_info_id'] +1 : $count + 1;
			$header_info_font = isset($rows[$i]['header_info_font']) ? $rows[$i]['header_info_font'] : 'tituscbz.ttf';
			
			//Populate info to display ends when there is no action
			$this->template->assign_vars(array(
				'HEADER_INFO_ID'						=> $new_id,
				'HEADER_INFO_NAME'					=> $this->language->lang('HEADER_INFO_NAME') . ' #' . $new_id,
				'HEADER_INFO_DESC'					=> $this->language->lang('HEADER_INFO_DESC') . ' #' . $new_id,
				'HEADER_INFO_LONGDESC'			=> $this->language->lang('HEADER_INFO_LONGDESC') . ' #' . $new_id,
				'HEADER_INFO_USE_EXTDESC'		=> $this->language->lang('HEADER_INFO_USE_EXTDESC') . ' #' . $new_id,
				'EXTENED_SITE_DESC'					=> 0,
				
				//New 0.9.0 start
				'HEADER_INFO_TITLE_COLOUR'		=>'#12A3EB',
				'HEADER_INFO_DESC_COLOUR'		=> '#000000',

				//New 0.9.0 ends
				'HEADER_INFO_TYPE'					=> $rows[$i]['header_info_type'],

				'HEADER_INFO_DIR'						=> $this->language->lang($rows[$i]['header_info_dir']),
				'HEADER_INFO_DIR_SELECT' 		=> $this->gen_lang_dirs_select_list('html', 'header_info_dir', $rows[$i]['header_info_dir']), 
				'HEADER_INFO_FONT_SELECT' 	=> $this->gen_fonts_select_list('html', 'header_info_font', $header_info_font), 
				'HEADER_INFO_DB_FONT' 			=> substr($header_info_font, 0, strrpos($header_info_font, '.')),
				'HEADER_INFO_IMAGE'					=> $rows[$i]['header_info_image'],
				'THUMBNAIL_URL'   						=> generate_board_url() . '/app.'.$this->php_ext.'/thumbnail',

				//New 0.9.0 start
				'HEADER_INFO_RADIUS'				=> isset($rows[$i]['header_info_banner_radius']) ? $rows[$i]['header_info_banner_radius'] : 7,
				'HEADER_INFO_PIXELS'					=> isset($rows[$i]['header_info_pixels']) ? $rows[$i]['header_info_pixels'] : 12,
				'HEADER_INFO_TITLE_PIXELS'		=> isset($rows[$i]['header_info_title_pixels']) ? $rows[$i]['header_info_title_pixels'] : 10,
				'HEADER_INFO_DESC_PIXELS'		=> isset($rows[$i]['header_info_desc_pixels']) ? $rows[$i]['header_info_desc_pixels'] : 10,
				'HEADER_INFO_LEFT'					=> isset($rows[$i]['header_info_left']) ? $rows[$i]['header_info_left'] : 0,
				'HEADER_INFO_RIGHT'					=> isset($rows[$i]['header_info_right']) ? $rows[$i]['header_info_right'] : 0,

				//New 0.9.0 ends
				'S_HEADER_INFO_LINK_CHECKED'	=>  isset($rows[$i]['header_info_image_link']) ? $rows[$i]['header_info_image_link'] : $rows[1]['header_info_image_link'],
				'HEADER_INFO_URL'						=>  isset($rows[$i]['header_info_url']) ? $rows[$i]['header_info_url'] : $rows[1]['header_info_url'],
				'HEADER_INFO_LICENSE'				=>  isset($rows[$i]['header_info_license']) ? $rows[$i]['header_info_license'] : $rows[1]['header_info_license'],
				'HEADER_INFO_TIME'					=>  isset($rows[$i]['header_info_time']) ? $rows[$i]['header_info_time'] : $rows[1]['header_info_time'],
			
			));
		}
	}
}