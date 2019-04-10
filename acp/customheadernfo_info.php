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
 * Header Info ACP module info.
 */
class customheadernfo_info
{
	function module()
	{
		return array(
			'filename'		=> '\orynider\customheadernfo\acp\customheadernfo_module',
			'title'			=> 'ACP_HEADER_INFO_TITLE',
			'modes'			=> array(
				'config'		=> array('title' => 'ACP_HEADER_INFO_CONFIG', 'auth' => 'ext_orynider/customheadernfo && acl_a_headernfo', 'cat' => array('ACP_HEADER_INFO_TITLE')),
				//'forums'		=> array('title' => 'ACP_MANAGE_FORUMS', 'auth' => 'ext_orynider/customheadernfo && acl_a_headernfo', 'cat' => array('ACP_HEADER_INFO_TITLE')),
				//'pages'		=> array('title' => 'ACP_MANAGE_PAGES', 'auth' => 'ext_orynider/customheadernfo && acl_a_headernfo', 'cat' => array('ACP_HEADER_INFO_TITLE')),
			),
		);
	}
}
