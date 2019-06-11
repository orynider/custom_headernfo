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
namespace orynider\customheadernfo\migrations\v09x;

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
		return array('\phpbb\db\migration\data\v320\v320');
	}

	/**
	 * Add the customheadernfo table schema to the database
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
						'header_info_name'		=> array('VCHAR:255', ''),
						'header_info_desc'			=> array('MTEXT_UNI', ''),
						'header_info_longdesc'	=> array('MTEXT_UNI', ''),
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
						'header_info_title_pixels'	=>  array('INT:8', 14),
						'header_info_desc_pixels'	=>  array('INT:8', 10),
						'header_info_pixels'		=>  array('INT:8', 12),
						'header_info_left'			=> array('TINT:2', 0),
						'header_info_right'			=> array('TINT:2', 0),
						'header_info_license'		=> array('MTEXT_UNI', ''),
						'header_info_time'			=> array('UINT:8', 0),
						'header_info_last'			=> array('INT:50', 0),
						'header_info_pic_width'	=> array('INT:8', 458),
						'header_info_pic_height'	=> array('INT:8', 50),
						'header_info_pin'			=> array('TINT:2', 0),
						'header_info_disable'	=> array('TINT:1', 0),
						'forum_id' 						=> array('INT:8', 0),
						'user_id'							=> array('INT:8', 0),
						'bbcode_bitfield'			=> array('VCHAR:255', ''),
						'bbcode_uid'					=> array('VCHAR:8', ''),
						'bbcode_options'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'header_info_id',
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
			array('config.add', array('header_info_enable', '0')),
			array('config.add', array('header_info_version', '1.0.0')),
			
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
					'module_auth'     => 'ext_orynider/customheadernfo && acl_a_headernfo',
				)
			)),
			// Add Settings link to the extension group
			array(
				'module.add', 
				array(
					'acp', 
					'ACP_HEADER_INFO_TITLE',
					array(
						'module_basename' => '\orynider\customheadernfo\acp\customheadernfo_module',
						'modes' => array('config', 
					),
				),
			)),
		);
	}

	/**
	 * Drop the customheadernfo table schema from the database
	 *
	 * @return void
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'custom_header_info',
			),
		);
	}
	
	/**
	* Reverts data by returning a list of instructions to be executed
	*
	* @return array Array of data instructions that will be performed on revert
	* 	NOTE: calls to tools (such as config.add) are automatically reverted when
	* 		possible, so you should not attempt to revert those, this is mostly for
	* 		otherwise unrevertable calls (custom functions for example)
	*/
	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'clean_config_data'))),
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
	* install config values.
	*
	* @param  String $key       The configuration option's name
	* @param  bool   $use_cache Whether this variable should be cached or if it
	*                           changes too frequently to be efficiently cached
	* @return true
	*/
	public function install_config()
	{
		// Read out config values
		foreach (self::$configs as $key => $new_value)
		{
			// Read out old config db values
			$old_value = isset($this->config[$key]) ? $this->config[$key] : false;
			
			if (isset(self::$is_dynamic[$key]))
			{
				$use_cache  = true;
			}
			else
			{
				$use_cache  = false;
			}			
			//This if is set does update or sets the config entry 
			$this->config->set_atomic($key, $old_value, $new_value, $use_cache);
		}
		return true;
	}
	
	/**
	*
	* Cleans the customheadernfo configuration options
	*
	* @param  String $key       The configuration option's name
	* @param  bool   $use_cache Whether this variable should be cached or if it
	*                           changes too frequently to be efficiently cached
	* @return true
	*/
	public function clean_config_data()
	{
		// Read out config values
		foreach (self::$configs as $key => $value)
		{	
			$sql = "";			
			
			//This also unset's $this->config[$key]
			$this->config->delete($key);
			
			// Read out config values and check if the entry is deleted and unset
			if (isset($this->config[$key]))
			{		
				// If we reach here then we make sure entries are removed from database
				$sql = 'DELETE FROM ' . CONFIG_TABLE . "
					WHERE config_name = '" . $this->db->sql_escape($key) . "'";
				$this->db->sql_query($sql);
			}
		}
		return true;
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
		'header_info_banner_position1' => '0', //Pos 1
		'header_info_banner_position2' => '0', //Pos 2
		'header_info_banner_position3' => '0', //Pos 3
		'header_info_banner_position' => '1', //Pos 4 (Default)

		'header_info_module_name' => 'Custom Header Info', // settings_dbname
		'header_info_wysiwyg_path' => 'assets/javascript/',
		'header_info_banners_dir' => 'ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/',
		'header_info_backgrounds_dir' => 'ext/orynider/customheadernfo/styles/prosilver/theme/images/backgrounds/',
		'header_info_thumb_cache' => '1',

  		 //new items added 01.01.2019 by orynider for all ticker rows

		'header_info_row_height' =>'120',	/* Height of each ticker row in PX. Should be uniform. */
		'header_info_speed' => '800',		/* Speed of transition animation in milliseconds */
		'header_info_interval' => '4000',		/* Time between change in milliseconds */
		'header_info_show_amount' => '15',		/* Integer for how many items to query and display at once. Resizes height accordingly (OPTIONAL) */
		'header_info_mousestop' => '1',	/* If set to true, the ticker will stop on mouseover */
		'header_info_direction' => 'up',		/* Direction that list will scroll */

		'header_info_use_watermark' => '1',
		'header_info_disp_watermark_at' => '3',

		//Version
		'header_info_version'		=> '1.0.0',
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
		
		global $phpbb_log;
		$phpbb_log->add($user->data['user_id'], $user->data['user_ip'], time(), 'admin', 'Custom Header Info extension Install/Upgrade', 'Version 1.0.0');
		
		// Define sample article data
		$sample_data_files = array(
			array(
				'header_info_id'					=> 1,
				'header_info_name'			=> 'Board Disabled',
				'header_info_desc'				=> 'Board Disabled Info for the Custom Header Info extension.',
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
				'header_info_right'				=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'				=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '0',
				'header_info_pic_width'	=> '458',
				'header_info_pic_height'	=> '193',
				'header_info_disable'		=> 0,
				'forum_id'							=> 1,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'					=> 2,
				'header_info_name'			=> 'Test Header Info #1',
				'header_info_desc'				=> 'Test Header Info for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc' => 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0000ff',
				'header_info_dir'				=> 'movies', //ext/orynider/customheadernfo/language/movies/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/custom_header_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'	=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'				=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'				=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '1',
				'header_info_pic_width'	=> '458',
				'header_info_pic_height'	=> '70',
				'header_info_disable'		=> 0,
				'forum_id'							=> 0,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 3,
				'header_info_name'		=> 'Test Header Info #2',
				'header_info_desc'			=> 'Test Header Info text for the Custom Header Info extension.',
				'header_info_longdesc'	=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc' => 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#066c9f',
				'header_info_dir'				=> 'wlcodex', //ext/orynider/customheadernfo/language/wlcodex/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/linen.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => 0,
				'header_info_title_pixels'	=> '18',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'			=> 0,
				'header_info_url'			=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'		=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'			=> 0,
				'header_info_pin'			=> 0,
				'header_info_pic_width'	=> '458',
				'header_info_pic_height'	=> '70',
				'header_info_disable'		=> 0,
				'forum_id'							=> 0,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 4,
				'header_info_name'		=> 'Demo Prototype Header Dimensions',
				'header_info_desc'			=> 'Test Header Info Prototype for the Custom Header Info extension.',
				'header_info_longdesc'	=> 'Sample text description: Test text for the Custom Header Info\'s extension Demo Prototype.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#066c9f',
				'header_info_dir'				=> 'hisquotes', //ext/orynider/customheadernfo/language/hisquotes/
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/signature_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,
				'header_info_banner_radius' => '8',
				'header_info_title_pixels'	=> '18',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'		=> '10',
				'header_info_left'			=> 0,
				'header_info_right'			=> 0,
				'header_info_url'			=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'		=> 'GNU GPL-2',
				'header_info_time'			=> time(),
				'header_info_last'			=> 1,
				'header_info_pin'			=> 1,
				'header_info_pic_width'	=> '500',
				'header_info_pic_height'	=> '50',
				'header_info_disable'		=> 0,
				'forum_id'							=> 0,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'				=> 5,
				'header_info_name'		=> 'Test Header Info #4',
				'header_info_desc'			=> 'Test Header Info for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Test text for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0c6a99',
				'header_info_dir'				=> 'politics', 
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/custom_header_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'	=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'				=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'				=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '1',
				'header_info_pic_width'	=> '458',
				'header_info_pic_height'	=> '70',
				'header_info_disable'		=> 0,
				'forum_id'							=> 0,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
			array(
				'header_info_id'					=> 6,
				'header_info_name'			=> 'Header Info Politics Test #5',
				'header_info_desc'				=> 'Header Info Politics HTML Text Test for the Custom Header Info extension.',
				'header_info_longdesc'		=> 'Sample text description: Header Info Politics HTML Test text for the Custom Header Info extension.',
				'header_info_use_extdesc'	=> 0,
				'header_info_title_colour'	=> '#000000',
				'header_info_desc_colour'	=> '#0c6a99',
				'header_info_dir'				=> 'politics', 
				'header_info_font'				=>  'tituscbz.ttf',
				'header_info_type'				=> 'language',
				'header_info_image'			=> generate_board_url() . '/ext/orynider/customheadernfo/styles/prosilver/theme/images/banners/custom_header_bg.png', //str_replace('prosilver' 'all', $data_files['header_info_image'])
				'header_info_image_link'	=> 0,	
				'header_info_banner_radius' => '10',
				'header_info_title_pixels'	=> '12',
				'header_info_desc_pixels'	=> '10',
				'header_info_pixels'			=> '10',
				'header_info_left'				=> 0,
				'header_info_right'				=> 0,
				'header_info_url'				=> 'http://mxpcms.sourceforge.net/',
				'header_info_license'			=> 'GNU GPL-2',
				'header_info_time'				=> time(),
				'header_info_last'				=> 0,
				'header_info_pin'				=> '1',
				'header_info_pic_width'	=> '458',
				'header_info_pic_height'	=> '70',
				'header_info_disable'		=> 0,
				'forum_id'							=> 0,
				'user_id'								=> $user->data['user_id'],
				'bbcode_bitfield'				=> 'QQ==',
				'bbcode_uid'						=> '2p5lkzzx',
				'bbcode_options'				=> '',
			),
		);
		
		// Insert sample data
		$this->db->sql_multi_insert($this->table_prefix . 'custom_header_info', $sample_data_files);
	}
}
