<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2018, orynider, https://mxpcms.sourceforge.net.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/

namespace orynider\customheadernfo\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	/** @var \phpbb\db\driver\driver_interface  */
	protected $db; 
	/** @var \phpbb\user */ 
	protected $user;
	/** @var \phpbb\cache\service */ 
	protected $cache;
	/** @var \phpbb\config\config */ 
	protected $config;
	/** @var \phpbb\template\template */	
	protected $template; 
	/** @var \phpbb\controller\helper  */ 
	protected $helper;
	/** @var	\phpbb\request\request  */ 
	protected $request;
	/** @var	\phpbb\pagination  */ 
	protected $pagination;
	/** @var	\phpbb\extension\manager  */ 
	protected $ext_manager;
	/** @var	\phpbb\path_helper  */ 
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
	
	/** @var \phpbb\language\language $language */
	protected $language;	
	
	/** @var	\phpbb\collapsiblecategories\operator\operator  */ 
	protected $operator;
	
	/** @array language_list */	
	protected $language_list = array();
	
	/** @var dir_select_from */	
	protected $dir_select_from;
	
	/** @var dir_select_into */	
	protected $dir_select_into;
	
	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface 						$db
	* @param \phpbb\user 														$user
	* @param \phpbb\cache\service 											$cache
	* @param \phpbb\config\config 											$config
	* @param \phpbb\template\template 									$template
	* @param \phpbb\controller\helper 										$helper
	* @param \phpbb\request\request				 						$request
	* @param \phpbb\pagination 												$pagination
	* @param \phpbb\extension\manager 									$ext_manager
	* @param \phpbb\path_helper 											$path_helper
	* @param string 																	$php_ext
	* @param string 																	$root_path
	* @param string 																	$custom_header_info
	* @param \phpbb\language\language									$language
	* @param \phpbb\collapsiblecategories\operator\operator	$operator
	*
	*/	
	public function __construct(
	\phpbb\db\driver\driver_interface $db, 
	\phpbb\user $user, 
	\phpbb\cache\service $cache,
	\phpbb\config\config $config, 
	\phpbb\template\template $template, 
	\phpbb\controller\helper $helper,
	\phpbb\request\request $request,
	\phpbb\pagination $pagination,
	\phpbb\extension\manager $ext_manager,
	\phpbb\path_helper $path_helper,
	$php_ext, $root_path,
	$custom_header_info_table, 
	\phpbb\language\language $language, 
	\phpbb\collapsiblecategories\operator\operator $operator = null)
	{
		$this->db = $db;
		$this->user = $user;
		$this->cache = $cache;
		$this->config = $config;
		$this->template = $template;
		$this->helper = $helper;
		$this->request = $request;
		$this->pagination = $pagination;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->php_ext = $php_ext;
		$this->root_path = $root_path;
		$this->custom_header_info_table = $custom_header_info_table;
		$this->language = $language;
		$this->operator = $operator;
		
		$this->ext_name 		= $this->request->variable('ext_name', 'orynider/customheadernfo');
		$this->module_root_path	= $this->ext_manager->get_extension_path($this->ext_name, true);
		
		$this->user->add_lang_ext($this->ext_name, 'common');
			
		/* Check watever languages for thumbnail text are set and are uploaded or translated */
		$this->language_from = (isset($this->config['default_lang']) && (is_dir($this->module_root_path . 'language/' . $this->config['default_lang']) . '/')) ? $this->config['default_lang'] : 'en';
		$this->language_into	= (isset($user->lang['USER_LANG']) && (is_dir($this->module_root_path . 'language/' . $user->lang['USER_LANG']) . '/')) ? $user->lang['USER_LANG'] : $this->language_from;
		if (!is_dir($this->module_root_path . 'language/' . $this->language_from . '/'))
		{
			//Default language from uk english in case Resource id is #0 
			$this->language_from = (is_dir($this->module_root_path . 'language/en/')) ?  'en' : $this->language_into;
		}
		
		$this->template->assign_vars(array(
			'S_HEADER_INFO_ENABLED' 	=> (!empty($this->config['header_info_enable'])) ? true : false,
			'S_HEADER_INFO_POSITION1'	=> (!empty($this->config['header_info_banner_position1'])) ? true : false,
			'S_HEADER_INFO_POSITION2'	=> (!empty($this->config['header_info_banner_position2'])) ? true : false,
			'S_HEADER_INFO_POSITION3'	=> (!empty($this->config['header_info_banner_position3'])) ? true : false,
			'S_HEADER_INFO_POSITION4'	=> (!empty($this->config['header_info_banner_position'])) ? true : false,
			'S_THUMBNAIL'   					=> (@function_exists('gd_info') && (@count(@gd_info()) !== 0)), 
			'MODULE_NAME'					=> $this->config['header_info_module_name'], 
			'WYSIWYG_PATH'					=> $this->config['header_info_wysiwyg_path'],
			'BACKGROUNDS_DIR'			=> $this->config['header_info_backgrounds_dir'],
			'BANNERS_DIR'		   				=> $this->config['header_info_banners_dir'],
			'HEADER_INFOVERSION'		=> $this->config['header_info_version'],
			'ROW_HEIGHT'						=> $this->config['board_disable'] ? 193 : $this->config['header_info_row_height'],	/* Height of each ticker row in PX. Should be uniform. */
			'SPEED'									=> $this->config['header_info_speed'],	/* Speed of transition animation in milliseconds */
			'INTERVAL'							=> $this->config['header_info_interval'],		/* Time between change in milliseconds */
			'MAX_ITEMS'						=> $this->config['header_info_show_amount'],	/* Integer for how many items to query and display at once. Resizes height accordingly (OPTIONAL) */
			'MOUSESTOP'						=> $this->config['header_info_mousestop'],	/* If set to true, the ticker will stop on mouseover */
			'DIRECTION'							=> $this->config['header_info_direction'],	/* Direction that list will scroll */
			'SITE_HOME_URL'   				=> $this->config['board_url'], /* CMS or FORUM URL */
			'PHPBB_URL'   						=> generate_board_url() . '/', /* Forum URL */
		));
	}

	static public function getSubscribedEvents ()
	{
		return array(
			'core.permissions'	=> 'add_permission',
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'display_custom_header',
		);
	}

	/**
	* Add permissions acp Custom Header Info
	*/
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_headernfo_use'] = array('lang' => 'ACL_A_HEADER_INFO', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	* Display Custom Header Info Extension by orynider
	*
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'orynider/customheadernfo',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function navbar_header_after($event)
	{
		$this->template->assign_vars(array(
			'DEFAULT_LANG'	=> (isset($this->config['default_lang'])) ? $this->config['default_lang'] : 'ro',
			'USER_LANG'	=> (isset($this->user['user_lang'])) ? $this->user['user_lang'] : 'en',
        ));
    }

	public function display_custom_header($event)
	{
		//Custom DB Background
		$sql_layer = $this->db->get_sql_layer();
		switch ($sql_layer)
		{
				case 'postgres':
					$random = 'RANDOM()';
				break;

				case 'mssql':
				case 'mssql_odbc':
					$random = 'NEWID()';
				break;

				//This inludes: sqlite3,  mysqli, etc.
				default:
					$random = 'RAND()';
				break;
		}
		
		//First Banner is reserved for Board Disable state, so if else we exclude it from query
		$sql_where = ($this->config['board_disable']) ? ' ORDER BY ' . $random : ' WHERE header_info_id <> 1 ORDER BY ' . $random;	
		
		//max_items
		$show_amount = isset($this->config['header_info_show_amount']) ? $this->config['header_info_show_amount'] : 3;
		
		$sql = "SELECT * FROM " . $this->custom_header_info_table . $sql_where;
		$result = $this->db->sql_query_limit($sql, $show_amount);
		
		$row_disable = array(
			'header_info_id'				=> 1,
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
		) ;
				
		while ($row = $this->db->sql_fetchrow($result)) 
		{
			//Populate info to display starts
			$info_title = array();
			$info_desc = array();
			
			//Merge disable with db rows
			$row = (count($row) == 0) ? $row_disable : $row; 
		
			$header_info_name = $row['header_info_name'];
			$header_info_desc = $row['header_info_desc'];
			$header_info_longdesc = $row['header_info_longdesc'];
			
			if ($row['header_info_type'] == 'lang_html_text')
			{
				$header_info_dir = $row['header_info_dir'];
				$header_info_font = $row['header_info_font'];
				
				// populate entries (all lang keys)
				$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : $this->language_from;
				$this->language_into = is_file($this->module_root_path . 'language/' . $this->language_into . '/' . $header_info_dir . '/common.' . $this->php_ext) ? $this->language_into : 'en';
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
			
			$header_corners 		= '0px 0px 0px 0px';
			// Populate corners
			if ($row['header_info_image'])
			{
				$header_corners 	= ($row['header_info_left']) ? $row['header_info_pixels'] . 'px 0px 0px ' . $row['header_info_pixels'] . 'px' : $header_corners;
	 			$header_corners 	= ($row['header_info_right']) ? '0px ' . $row['header_info_pixels'] . 'px ' . $row['header_info_pixels'] . 'px 0px' : $header_corners;
				$header_corners 	= ($row['header_info_left'] && $row['header_info_right']) ? $row['header_info_pixels'] . 'px ' . $row['header_info_pixels'] . 'px ' . $row['header_info_pixels'] . 'px ' . $row['header_info_pixels'] . 'px' : $header_corners;
			}
			
			//Populate info to display ends
			$this->template->assign_block_vars('header_info_scroll', array(
				'HEADER_INFO_ID'							=> $row['header_info_id'],
				'HEADER_INFO_NAME'						=> $row['header_info_name'],
				'HEADER_INFO_TITLE'						=> $info_title,
				'HEADER_INFO_DESC'						=> $row['header_info_desc'],
				'HEADER_INFO_LONGDESC'				=> $row['header_info_longdesc'],
				'HEADER_INFO_RANDDESC'				=> ($row['header_info_use_extdesc'] == 1) ? $this->config['site_desc'] : $info_desc,
				'HEADER_INFO_SITE_DESC'				=> $this->config['site_desc'],
				//New 0.9.0 start
				'HEADER_INFO_TITLE_COLOUR'	=> isset($row['header_info_title_colour']) ? $row['header_info_title_colour'] : '',
				'HEADER_INFO_TITLE_COLOUR_1'	=> isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 1) : '',
				'HEADER_INFO_TITLE_COLOUR_2'	=> isset($row['header_info_title_colour']) ? $this->get_gradient_colour($row['header_info_title_colour'], 2) : '',
				'HEADER_INFO_DESC_COLOUR'	=> isset($row['header_info_desc_colour']) ? $row['header_info_desc_colour'] : '',
				'HEADER_INFO_DESC_COLOUR_1'	=> isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 1) : '',
				'HEADER_INFO_DESC_COLOUR_2'	=> isset($row['header_info_desc_colour']) ? $this->get_gradient_colour($row['header_info_desc_colour'], 2) : '',
				//New 0.9.0 ends
				'HEADER_INFO_TYPE'					=> $row['header_info_type'],
				'HEADER_INFO_DIR'						=> $row['header_info_dir'], 
				'HEADER_INFO_DB_FONT' 			=> substr($row['header_info_font'], 0, strrpos($row['header_info_font'], '.')), 
				'HEADER_INFO_IMAGE'					=> $row['header_info_image'],
				'THUMBNAIL_URL'   						=> ($this->config['board_disable'] && ($row['header_info_id']  == 1)) ? $row_disable['header_info_image'] : generate_board_url() . '/app.' . $this->php_ext . '/thumbnail',
				'LOGO_URL'   								=> generate_board_url() . '/app.' . $this->php_ext . '/thumbnail',
				//New 0.9.0 start
				'HEADER_INFO_RADIUS'				=> isset($row['header_info_banner_radius']) ? $row['header_info_banner_radius'] : '',
				'HEADER_INFO_PIXELS'					=> isset($row['header_info_pixels']) ? $row['header_info_pixels'] : '',
				'HEADER_INFO_LEFT'					=> isset($row['header_info_left']) ? $row['header_info_left'] : '',
				'HEADER_INFO_RIGHT'					=> isset($row['header_info_right']) ? $row['header_info_right'] : '',
				'HEADER_INFO_CORNERS'			=> $header_corners,
				'HEADER_INFO_WIDTH'				=> isset($row['header_info_width']) ? $row['header_info_width'] : $row['header_info_pic_width'],
				'HEADER_INFO_HEIGHT'				=> ($this->config['board_disable'] && ($row['header_info_id'] == 0 || $row['header_info_id']  == 1)) ? 193 : (isset($row['header_info_height']) ? $row['header_info_height'] : $row['header_info_pic_height']),
				//New 0.9.0 ends
				'S_HEADER_INFO_LINK_CHECKED'		=> $row['header_info_image_link'],
				'HEADER_INFO_URL'							=> $row['header_info_url'],
				'HEADER_INFO_LICENSE'					=> $row['header_info_license'],
				'HEADER_INFO_TIME'						=> $row['header_info_time'],
				'HEADER_INFO_LAST'						=> $row['header_info_last'],
				'HEADER_INFO_PIC_WIDTH'				=> $row['header_info_pic_width'],
				'HEADER_INFO_PIC_HEIGHT'				=> ($this->config['board_disable'] && ($row['header_info_id'] == 0 || $row['header_info_id']  == 1)) ? 193 : $row['header_info_pic_height'],
				'S_HTML_MULTI_TEXT_ENABLED'		=> ($row['header_info_type'] == 'lang_html_text'),
				'S_SIMPLE_DB_TEXT_ENABLED'			=> ($row['header_info_type'] == 'simple_db_text'),
				'S_SIMPLE_DB_LOGO_ENABLED'		=> ($row['header_info_type'] == 'simple_bg_logo'),
				'S_HEADER_INFO_PIN_CHECKED'		=> $row['header_info_pin'],
				'S_USE_SITE_DESC'							=> $row['header_info_use_extdesc'],
				'S_HEADER_INFO_DISABLE'				=> $row['header_info_disable'], 
			));
		}
		$this->db->sql_freeresult($result);

		if ($this->operator !== null)
		{
			$fid = 'header_info'; /* string for hash */
			$this->template->assign_vars(array(
				'HEADER_INFO_IS_COLLAPSIBLE'	=> true,
				'S_HEADER_INFO_HIDDEN' => in_array($fid, $this->operator->get_user_categories()),
				'U_HEADER_INFO_COLLAPSE_URL' => $this->helper->route('phpbb_collapsiblecategories_main_controller', array('forum_id' => $fid, 
				'hash' => generate_link_hash("collapsible_$fid")))
			));
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

	function load_lang_file($filename)
	{
		if (!is_file($filename))
		{
			return array();
		}
		include($filename);
		return $lang;
	}
}