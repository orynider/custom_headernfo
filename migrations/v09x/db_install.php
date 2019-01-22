<?php
/**
 *
* @package phpBB3 Extension - Custom Header Info
* @version $Id: db_install.php,v 1.2 2008/10/26 08:36:06 orynider Exp $
* @copyright (c) 2002-2006 [Jon Ohlsson, Mohd Basri, wGEric, PHP Arena, pafileDB, CRLin, FlorinCB] MXP Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2 (GPL-2.0)
 *
 */
 
/**#@+
* @ignore
*/
namespace orynider\custom_headernfo\migrations\v09x;

use \phpbb\db\migration\container_aware_migration;
/**#@-*/
class db_install extends \phpbb\db\migration\container_aware_migration
{
	/**
	 * Assign migration file dependencies for this migration
	 *
	 * @return void
	 * @access public
	 */
	static public function depends_on()
	{
		//return array('\phpbb\db\migration\data\v31x\v314');
		return array('\phpbb\db\migration\data\v320\v320');
	}

	/**
	 * Add the custom_headernfo table schema to the database
	 *
	 * @return void
	 * @access public
	 */
	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				// --------------------------------------------------------
				// Table structure for table 'phpbb_custom_header_info'
				$this->table_prefix . 'custom_header_info'	=> array(
					'COLUMNS'	=> array(
						'header_info_id'				=> array('UINT:8', null, 'auto_increment'),
						'header_info_name'			=> array('VCHAR:255', ''),
						'header_info_desc'			=> array('MTEXT_UNI', ''),
						'header_info_longdesc'		=> array('MTEXT_UNI', ''),
						'header_info_use_extdesc'	=> array('TINT:2', 0),
						'header_info_title_colour'	=> array('VCHAR:8', '#000000'),
						'header_info_desc_colour'	=> array('VCHAR:8', '#12A3EB'),
						'header_info_dir'				=> array('VCHAR:255', ''), //langSubDir ie: 'movies'
						'header_info_font'				=> array('VCHAR:190', 'tituscbz'), 
						'header_info_type'				=> array('VCHAR:190', ''),
						'header_info_url'				=> array('MTEXT_UNI', ''),
						'header_info_image'	    	=> array('MTEXT_UNI', ''),
						'header_info_image_link'	=> array('TINT:1', 0),
						'header_info_banner_radius' =>  array('INT:8', 10),
						'header_info_title_pixels'		=>  array('INT:8', 14),
						'header_info_desc_pixels'	=>  array('INT:8', 10),
						'header_info_pixels'			=>  array('INT:8', 12),
						'header_info_left'				=> array('TINT:2', 0),
						'header_info_right'			=> array('TINT:2', 0),
						'header_info_license'			=> array('MTEXT_UNI', ''),
						'header_info_time'			=> array('UINT:8', 0),
						'header_info_last'				=> array('INT:50', 0),
						'header_info_pic_width'	    => array('INT:8', 458),
						'header_info_pic_height'	 	=> array('INT:8', 50),
						'header_info_pin'				=> array('TINT:2', 0),
						'header_info_disable'			=> array('TINT:1', 0),
						'forum_id' 						=> array('INT:8', 0),
						'user_id'							=> array('INT:8', 0),
						'bbcode_bitfield'				=> array('VCHAR:255', ''),
						'bbcode_uid'					=> array('VCHAR:8', ''),
						'bbcode_options'				=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'header_info_id',
				),
				// --------------------------------------------------------
				// Table structure for table 'phpbb_custom_header_info_config'
				$this->table_prefix . 'custom_header_info_config' => array(
					'COLUMNS' => array(
						'config_name'	=> array('VCHAR:255', ''),
						'config_value'	=> array('VCHAR_UNI', ''),
						'is_dynamic'	=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> 'config_name',
				),
			),
		);
	}

	/**
	 * Add or update data in the database
	 *
	 * @return void
	 * @access public
	 */
	public function update_data()
	{
		return array(
			
			// Add configs
			array('config.add', array('header_info_enable', 0)),
			array('config.add', array('header_info_version', '0.8.9')),
			
			// Add permissions
			array('permission.add', array('a_headernfo_use', true)),
			array('permission.add', array('a_headernfo', true)),

			// Set permission
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_headernfo_use')),
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_headernfo')),
			array('permission.permission_set', array('REGISTERED', 'a_headernfo_use', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'a_headernfo', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'a_headernfo_use', 'group')),
		
			// Insert sample pafildb data
			array('custom', array(array($this, 'insert_sample_data'))),

			// Insert sample pafildb config settings   
			array('custom', array(array(&$this, 'install_config'))),

			// Add extension group to ACP \ Extensions
			array('module.add', array(
				'acp', 
				'ACP_CAT_DOT_MODS', 
				'ACP_HEADER_INFO_TITLE',
				array(
					'module_enabled'  => 1,
					'module_display'  => 1,
					'module_langname' => 'ACP_HEADER_INFO_TITLE',
					'module_auth'     => 'ext_orynider/custom_headernfo && acl_a_headernfo',
				)
			)),
			// Add Settings link to the extension group
			array('module.add', array(
				'acp', 
				'ACP_HEADER_INFO_TITLE',
				array(
					'module_basename' => '\orynider\custom_headernfo\acp\custom_headernfo_module',
					'modes' => array('config', 
											//'forums', 
											//'pages'
					),
				),
			)),
		);
	}

	/**
	 * Drop the custom_headernfo table schema from the database
	 *
	 * @return void
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'custom_header_info',
				$this->table_prefix . 'custom_header_info_config',
			),
		);
	}

	/**
	 * Custom function query permission roles
	 *
	 * @return void
	 * @access public
	 */
	private function role_exists($role)
	{
		$sql = 'SELECT role_id
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_id = $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);

		return $role_id;
	}
	
	/**
	* Set config value. Creates missing config entry.
	* Only use this if your config value might exceed 255 characters, otherwise please use set_config
	*
	* @param string $config_name Name of config entry to add or update
	* @param mixed $config_value Value of config entry to add or update
	*/
	private function set_custom_headernfo_config($config_name, $config_value, $use_cache = true)
	{
		// Read out config values
		$custom_headernfo_config = $this->config_values();

		$sql = 'UPDATE ' . $this->table_prefix . "custom_header_info_config
			SET config_value = '" . $this->db->sql_escape($config_value) . "'
			WHERE config_name = '" . $this->db->sql_escape($config_name) . "'";
		$this->db->sql_query($sql);

		if (!$this->db->sql_affectedrows() && !isset($custom_headernfo_config[$config_name]))
		{
			$sql = 'INSERT INTO ' . $this->table_prefix . 'custom_header_info_config ' . $this->db->sql_build_array('INSERT', array(
				'config_name'	=> $config_name,
				'config_value'	=> $config_value));
			$this->db->sql_query($sql);
		}

		$this->custom_headernfo_config[$config_name] = $config_value;
	}

	/**
	* install config values. 	
	*/	
	public function install_config()
	{
		// Read out config values
		$custom_headernfo_config = $this->config_values();
		$this->custom_header_info_config_table = $this->table_prefix . 'custom_header_info_config';
		foreach (self::$configs as $key => $new_value)
		{
			// Read out old config db values
			$old_value = !isset($custom_headernfo_config[$key]) ? $custom_headernfo_config[$key] : false;
			// We keep out old config db values
			//$new_value = !isset($custom_headernfo_config[$key]) ? $custom_headernfo_config[$key] : $new_value;		
			
			if ($old_value !== false)
			{
				$sql .= " AND config_value = '" . $this->db->sql_escape($old_value) . "'";
			}
		
			if (isset(self::$is_dynamic[$config_name]))
			{
				$use_cache  = true;
			}
			else
			{
				$use_cache  = false;
			}
			// Read out config values
			if (isset($custom_headernfo_config[$key]))
			{
				$sql = 'UPDATE ' . $this->custom_header_info_config_table . "
					SET config_value = '" . $this->db->sql_escape($new_value) . "'
					WHERE config_name = '" . $this->db->sql_escape($key) . "'";
			}
			else
			{
				$sql = 'INSERT INTO ' . $this->custom_header_info_config_table . ' ' . $this->db->sql_build_array('INSERT', array(
					'config_name'	=> $key,
					'config_value'	=> $new_value,
					'is_dynamic'	=> ($use_cache) ? 0 : 1));
			}
			$this->db->sql_query($sql);
			$this->custom_headernfo_config[$key] = $custom_headernfo_config[$key] = $new_value;
		}
		return true;
	}

	/**
	* Obtain custom_headernfo config values
	*/
	public function config_values()
	{	
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'custom_header_info_config'))
		{
			$sql = 'SELECT *
				FROM ' . $this->table_prefix . 'custom_header_info_config';
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			if (!empty($row))
			{
				$custom_headernfo_config[$row['config_name']] = $row['config_value'];
				return $custom_headernfo_config;
			}
		}
		else
		{
			return array();
		}
	}
	
	static public $is_dynamic = array(
		'header_info_id',
		'header_info_dir',
	);
	
	static public $configs = array(
		
		//
		// Configs values
		//
		
		// Add configs
		'header_info_enable' => '0',  //Pos 0
		// Add positions to configuration
		'banner_position1' => '0', //Pos 1
		'banner_position2' => '0', //Pos 2
		'banner_position3' => '0', //Pos 3
		'banner_position' => '1', //Pos 4 (Default)

		'module_name' => 'Custom Header Info', // settings_dbname
		'wysiwyg_path' => 'assets/javascript/',
		'banners_dir' => 'ext/orynider/custom_headernfo/styles/prosilver/theme/images/banners/',
		'backgrounds_dir' => 'ext/orynider/custom_headernfo/styles/prosilver/theme/images/backgrounds/',
		'thumb_cache' => '1',

  		 //new items added 01.01.2019 by orynider for all ticker rows

		'row_height' =>'120',	/* Height of each ticker row in PX. Should be uniform. */
		'speed' => '800',		/* Speed of transition animation in milliseconds */
		'interval' => '4000',		/* Time between change in milliseconds */
		'show_amount' => '15',		/* Integer for how many items to query and display at once. Resizes height accordingly (OPTIONAL) */
		'mousestop' => '1',	/* If set to true, the ticker will stop on mouseover */
		'direction' => 'up',		/* Direction that list will scroll */

		'use_watermark' => '1',
		'disp_watermark_at' => '3',

		//Version
		'header_info_version'		=> '0.9.0',
	);

	/**
	 * Custom function to add sample data to the database
	 *
	 * @return void
	 * @access public
	 */
	public function insert_sample_data()
	{
		$user = $this->container->get('user');
		
		add_log('admin', 'Custom Header Info extension Install/Upgrade', 'Version 0.9.0 Alfa');
		
		// Define sample article data
		$sample_data_files = array(
			array(
				'header_info_id'				=> 1,
				'header_info_name'			=> 'Test Header Info #1',
				'header_info_desc'			=> 'Test Header Info for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0000ff',
				'header_info_dir'				=> 'movies', //ext/orynider/custom_headernfo/language/movies/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/custom_headernfo/styles/prosilver/theme/images/banners/custom_header_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'		=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '1',
				'header_info_pic_width'		=> '458',
				'header_info_pic_height'		=> '70',
				'header_info_disable'			=> 0,
				'forum_id'						=> 0,
				'user_id'							=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'					=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 2,
				'header_info_name'			=> 'Test Header Info #2',
				'header_info_desc'			=> 'Test Header Info text for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#066c9f',
				'header_info_dir'				=> 'wlcodex', //ext/orynider/custom_headernfo/language/wlcodex/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/custom_headernfo/styles/prosilver/theme/images/banners/linen.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => 0,
				'header_info_title_pixels'		=> '18',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> 0,
				'header_info_pic_width'		=> '458',
				'header_info_pic_height'		=> '70',
				'header_info_disable'			=> 0,
				'forum_id'						=> 0,
				'user_id'							=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'					=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 3,
				'header_info_name'			=> 'Demo Prototype Header Dimensions',
				'header_info_desc'			=> 'Test Header Info Prototype for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info\'s extension Demo Prototype.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#066c9f',
				'header_info_dir'				=> 'hisquotes', //ext/orynider/custom_headernfo/language/hisquotes/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/custom_headernfo/styles/prosilver/theme/images/banners/signature_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,
				'header_info_banner_radius' => '8',
				'header_info_title_pixels'		=> '18',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'				=> 1,
				'header_info_pin'				=> 1,
				'header_info_pic_width'		=> '458',
				'header_info_pic_height'		=> '70',
				'header_info_disable'			=> 0,
				'forum_id'						=> 0,
				'user_id'							=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'					=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 4,
				'header_info_name'			=> 'Test Header Info #4',
				'header_info_desc'			=> 'Test Header Info for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0c6a99',
				'header_info_dir'				=> 'politics', 
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/custom_headernfo/styles/prosilver/theme/images/banners/custom_header_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'		=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '1',
				'header_info_pic_width'		=> '458',
				'header_info_pic_height'		=> '70',
				'header_info_disable'			=> 0,
				'forum_id'						=> 0,
				'user_id'							=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'					=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
		);
		
		// Insert sample data
		$this->db->sql_multi_insert($this->table_prefix . 'custom_header_info', $sample_data_files);
	}
}
