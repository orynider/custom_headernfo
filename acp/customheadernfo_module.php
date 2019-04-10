<?php
/**
 *
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
 *
 */

namespace orynider\custom_headernfo\acp;

/**
 * Custom Header Info ACP module.
 */
class custom_headernfo_module
{
	public $u_action;
	protected $action;
	protected $table;
	protected $config;
	protected $db;
	protected $user;
	protected $template;
	protected $request;
	
	public function main ($id, $mode)
	{
		global $config, $db, $phpbb_container, $user, $template, $request, $table_prefix;

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('orynider.custom_headernfo.controller.admin.controller');
		
		// Requests
		$action = $request->variable('action', '');
		if ($request->is_set_post('add'))
		{
			$action = 'add';
		}
		
		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);
		
		// Here we set the main switches to use within the ACP
		switch ($mode)
		{
			case 'config':
				$this->page_title = $user->lang['ACP_MANAGE_CONFIG'];
				$this->tpl_name = 'acp_custom_header_info';
				$admin_controller->manage_header_info_config();
			break;
			
			case 'forums':
				$this->page_title = $user->lang['ACP_MANAGE_FORUMS'];
				$this->tpl_name = 'acp_custom_header_forums';
				$admin_controller->manage_forums_header();
			break;
			
			case 'pages':
				$this->page_title = $user->lang['ACP_MANAGE_PAGES'];
				$this->tpl_name = 'acp_custom_header_pages';
				$admin_controller->manage_pages_header();
			break;
		}
		

	}
}
