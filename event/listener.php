<?php
/**
*
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2018, orynider, https://mxpcms.sourceforge.net.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
*
*/

namespace orynider\custom_headernfo\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	protected $db;
	protected $user;
	protected $cache;
	protected $config;
	protected $template;
	protected $helper;
	protected $table;

	public function __construct(
	\phpbb\db\driver\driver_interface $db, 
	\phpbb\user $user, 
	\phpbb\cache\service $cache,
	\phpbb\config\config $config, 
	\phpbb\template\template $template, 
	\phpbb\controller\helper $helper, 
	$custom_header_info_table, 
	$custom_header_info_config_table, 
	\phpbb\collapsiblecategories\operator\operator $operator = null)
	{
		$this->db = $db;
		$this->cache = $cache;
		$this->config = $config;
		$this->template = $template;
		$this->helper = $helper;
		$this->custom_header_info_table = $custom_header_info_table;
		$this->custom_header_info_config_table = $custom_header_info_config_table;
		$this->operator = $operator;
		
		// Read out config values
		$this->header_info_config = $this->config_values();
		$this->template->assign_vars(array(
			//'S_HEADER_INFO_POSITION'			=> (!empty($this->header_info_config['banner_position'])) ? true : false,
			'S_HEADER_INFO_ENABLED' => (!empty($this->header_info_config['header_info_enable'])) ? true : false,
			'S_HEADER_INFO_POSITION1'	=> (!empty($this->header_info_config['banner_position1'])) ? true : false,
			'S_HEADER_INFO_POSITION2'	=> (!empty($this->header_info_config['banner_position2'])) ? true : false,
			'S_HEADER_INFO_POSITION3'	=> (!empty($this->header_info_config['banner_position3'])) ? true : false,
			'S_THUMBNAIL'   					=> (@function_exists('gd_info') && (@count(@gd_info()) !== 0)), 
			'MODULE_NAME'				  => $this->header_info_config['module_name'], // settings_dbname
			'WYSIWYG_PATH'				   => $this->header_info_config['wysiwyg_path'],
			'BACKGROUNDS_DIR'			   => $this->header_info_config['backgrounds_dir'],
			'BANNERS_DIR'		   				=> $this->header_info_config['banners_dir'],
			'HEADER_INFOVERSION'			=> $this->header_info_config['header_info_version'],
			'SITE_HOME_URL'   				=> $this->config['site_home_url'], //PORTAL_URL
			'PHPBB_URL'   						=> generate_board_url() . '/', //FORUM_URL
			'READONLY'							=> ' readonly="readonly"'
		));
	}

	static public function getSubscribedEvents ()
	{
		return array(
			'core.permissions'	=> 'add_permission',
			'core.user_setup'	=> 'load_language_on_setup',
			//'core.searchbox_before'	=> 'display_custom_header',
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
			'ext_name' => 'orynider/custom_headernfo',
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

			//To Do:
			//sqlite3
			//mysqli
			default:
				$random = 'RAND()';
			break;
		}

		$show_amount = isset($this->header_info_config['show_amount']) ? $this->header_info_config['show_amount'] : 3;

        $sql = "SELECT * FROM " . $this->custom_header_info_table . "
        ORDER BY $random";
        $result= $this->db->sql_query_limit($sql, $show_amount);

		while($row = $this->db->sql_fetchrow($result)) 
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
			));
		}
		$this->db->sql_freeresult($result);

		if ($this->operator !== null)
		{
			$fid = 'headernfo'; // string for hash
			$this->template->assign_vars(array(
				'HEADER_INFO_IS_COLLAPSIBLE'	=> true,
				'S_HEADER_INFO_HIDDEN' => in_array($fid, $this->operator->get_user_categories()),
				'U_HEADER_INFO_COLLAPSE_URL' => $this->helper->route('phpbb_collapsiblecategories_main_controller', array('forum_id' => $fid, 
				'hash' => generate_link_hash("collapsible_$fid")))
			));
		}


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
				$config[$row['config_name']] = trim( $row['config_value'] );
			}
			
			$this->db->sql_freeresult($result);
			
			$this->cache->put('custom_header_info_config', $config);
			
			return($config);
		}
	}

}
?>