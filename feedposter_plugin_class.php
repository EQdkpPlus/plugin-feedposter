<?php
/*	Project:	EQdkp-Plus
 *	Package:	feedposter Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found'); exit;
}

/*+----------------------------------------------------------------------------
  | feedposter
  +--------------------------------------------------------------------------*/
class feedposter extends plugin_generic {

	public $version				= '0.2.1';
	public $build				= '';
	public $copyright			= 'GodMod';

	protected static $apiLevel	= 23;

	/**
	* Constructor
	* Initialize all informations for installing/uninstalling plugin
	*/
	public function __construct(){
		parent::__construct();

		$this->add_data(array (
			'name'				=> 'FeedPoster',
			'code'				=> 'feedposter',
			'path'				=> 'feedposter',
			'template_path'		=> 'plugins/feedposter/templates/',
			'icon'				=> 'fa fa-rss-square',
			'version'			=> $this->version,
			'author'			=> $this->copyright,
			'description'		=> $this->user->lang('feedposter_short_desc'),
			'long_description'	=> $this->user->lang('feedposter_long_desc'),
			'homepage'			=> EQDKP_PROJECT_URL,
			'manuallink'		=> false,
			'plus_version'		=> '2.1',
		));

		$this->add_dependency(array(
			'plus_version'      => '2.1'
		));

		// -- Register our permissions ------------------------
		// permissions: 'a'=admins, 'u'=user
		// ('a'/'u', Permission-Name, Enable? 'Y'/'N', Language string, array of user-group-ids that should have this permission)
		// Groups: 1 = Guests, 2 = Super-Admin, 3 = Admin, 4 = Member
		$this->add_permission('a', 'manage',		'N', $this->user->lang('manage'),				array(2,3));

		// -- PDH Modules -------------------------------------
		$this->add_pdh_read_module('feedposter_feeds');
		$this->add_pdh_write_module('feedposter_feeds');
		$this->add_pdh_read_module('feedposter_log');
		$this->add_pdh_write_module('feedposter_log');
		
		// -- Menu --------------------------------------------
		$this->add_menu('admin', $this->gen_admin_menu());
		
		$this->tpl->add_css('
			.feedposter.feedtype_rss blockquote:before {
				content: "\f09e";
				color: #000;
			}
			
			.feedposter.feedtype_rss blockquote{
				border-color: #ffa133;
				background-color: #ffc989;
				color: #000;
			}
				
			.feedposter.feedtype_twitter blockquote:before {
				content: "\f099";
				color: #55acee;
			}
				
			.feedposter.feedtype_twitter blockquote{
				border-color: #55acee;
				background-color: #D9EDF7;
				color: #000;
			}
		');
	}

	/**
	* pre_install
	* Define Installation
	*/
	public function pre_install(){
		// include SQL and default configuration data for installation
		include($this->root_path.'plugins/feedposter/includes/sql.php');

		// define installation
		for ($i = 1; $i <= count($feedposterSQL['install']); $i++)
			$this->add_sql(SQL_INSTALL, $feedposterSQL['install'][$i]);

	}
	
	public function post_install(){
		$this->timekeeper->add_cron(
			'feedposter', array(
				'extern'			=> true,
				'ajax'				=> true,
				'delay'				=> true,
				'repeat'			=> true,
				'repeat_type'		=> 'minutely',
				'repeat_interval'	=> 10,
				'active'			=> true,
				'path'				=> '/plugins/feedposter/cronjob/',
				'editable'			=> false,
				'description'		=> 'Plugin: FeedPoster',
			)
		);
	}
	
	public function pre_uninstall(){
		$this->timekeeper->del_cron('feedposter');
	}
	
	/**
	 * post_uninstall
	 * Define Post Uninstall
	 */
	public function post_uninstall(){
		// include SQL data for uninstallation
		include($this->root_path.'plugins/feedposter/includes/sql.php');
	
		for ($i = 1; $i <= count($feedposterSQL['uninstall']); $i++)
			$this->db->query($feedposterSQL['uninstall'][$i]);
		
		$this->pdc->del_prefix('feedposter');
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_admin_menu(){
		$admin_menu = array (array(
			'name'	=> $this->user->lang('feedposter'),
			'icon'	=> 'fa fa-rss-square',
			1 => array (
				'link'	=> 'plugins/feedposter/admin/manage_feeds.php'.$this->SID,
				'text'	=> $this->user->lang('fp_manage_feeds'),
				'check'	=> 'a_feedposter_manage',
				'icon'	=> 'fa-list-alt'
			),
		));
		return $admin_menu;
	}

}
?>
