<?php
/**
 *
* @package phpBB Extension - Custom Header Info
* @copyright (c) 2016 orynider - http://mxpcms.sourceforge.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2 (GPL-2.0)
 *
 */

namespace orynider\customheadernfo\acp;

/**
 * Custom Header Info ACP module.
 */
class customheadernfo_module
{
	public $u_action;
	protected $user;
	protected $template;
	protected $request;
	
	public function main ($id, $mode)
	{
		global $phpbb_container, $user, $template, $request;

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('orynider.customheadernfo.controller.admin.controller');
			
		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);
		
		// Here we set the main switches to use within the ACP. The default is 'config'.
		switch ($mode)
		{
			case 'config':
				$this->page_title = $user->lang['ACP_MANAGE_CONFIG'];
				$this->tpl_name = 'acp_custom_header_info';
				$admin_controller->manage_header_info_config();
			break;
			
		}
	}
}
