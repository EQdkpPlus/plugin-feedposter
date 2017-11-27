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

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'feedposter');

$eqdkp_root_path = './../../../';
include_once($eqdkp_root_path.'common.php');


/*+----------------------------------------------------------------------------
  | FeedPosterFeeds
  +--------------------------------------------------------------------------*/
class FeedPosterFeeds extends page_generic {

	/**
	* Constructor
	*/
	public function __construct(){
		// plugin installed?
		if (!$this->pm->check('feedposter', PLUGIN_INSTALLED))
			message_die($this->user->lang('fp_plugin_not_installed'));
		
		$this->user->check_auth('a_feedposter_manage');

		$handler = array(
			'enable'	=> array('process' => 'enable', 'csrf'=>true),
			'disable'	=> array('process' => 'disable', 'csrf'=>true),
			'edit'		=> array('process' => 'edit'),
		);
		parent::__construct(false, $handler, array('feedposter_feeds', 'name'), null, 'del_ids[]');

		$this->process();
	}


	public function enable(){
		$intFeedID = $this->in->get('enable', 0);
		
		if ($intFeedID){
			$result = $this->pdh->put('feedposter_feeds', 'enable', array($intFeedID));
		}
		
		$strFeedName = $this->pdh->get('feedposter_feeds', 'name', array($intFeedID));
		
		//Handle Result
		if ($result){
			$message = array('title' => $this->user->lang('success'), 'text' => sprintf($this->user->lang('fp_enable_suc'), $strFeedName), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('error'), 'text' => sprintf($this->user->lang('fp_enable_nosuc'), $strFeedName), 'color' => 'red');
		}
		$this->display($message);
		
	} //close function

	public function disable(){
		$intFeedID = $this->in->get('disable', 0);
		
		if ($intFeedID){
			$result = $this->pdh->put('feedposter_feeds', 'disable', array($intFeedID));
		}
		
		$strFeedName = $this->pdh->get('feedposter_feeds', 'name', array($intFeedID));

		//Handle Result
		if ($result){
			$message = array('title' => $this->user->lang('success'), 'text' => sprintf($this->user->lang('fp_disable_suc'), $strFeedName), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('error'), 'text' => sprintf($this->user->lang('fp_disable_nosuc'), $strFeedName), 'color' => 'red');
		}
		$this->display($message);
	}

	public function delete(){
		$del_ids = $this->in->getArray('del_ids', 'int');

		if ($del_ids) {
			foreach($del_ids as $intFieldID){
				$this->pdh->put('feedposter_feeds', 'delete', array(intval($intFieldID)));
			}

			$message = array('title' => $this->user->lang('success'), 'text' => $this->user->lang('fp_delete_suc'), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('error'), 'text' => $this->user->lang('fp_delete_nosuc'), 'color' => 'red');
		}
		$this->display($message);
	}
	
	public function update(){
		$intFeedID = $this->in->get('feed', 0);
		$strName = $this->in->get('name');
		$strURL = $this->in->get('url');
		$intCategory = $this->in->get('category', 0);
		$intUser = $this->in->get('user_id', 0);
		$strTags = $this->in->get('tags');
		$intInterval = $this->in->get('interval', 0);
		$blnAllowComments = $this->in->get('allow_comments', 0);
		$intMaxPosts = $this->in->get('maxposts', 0);
		$intMaxLength = $this->in->get('maxlength', 0);
		$blnFeatured = $this->in->get('featured', 0);
		$intShowDays = $this->in->get('showdays', 0);
		$intRemoveHtml = $this->in->get('remove_html', 0);
		
		if($intFeedID){
			$blnResult = $this->pdh->put('feedposter_feeds', 'update', array($intFeedID, $strName, $strURL, $intCategory, $intUser, $strTags, $intInterval, $blnAllowComments, $intMaxPosts, $intMaxLength, $blnFeatured, $intShowDays, $intRemoveHtml));
		} else {
			$blnResult = $this->pdh->put('feedposter_feeds', 'add', array($strName, $strURL, $intCategory, $intUser, $strTags, $intInterval, $blnAllowComments, $intMaxPosts, $intMaxLength, $blnFeatured, $intShowDays, $intRemoveHtml));
		}
		
		if($blnResult){
			$message = array('title' => $this->user->lang('success'), 'text' => $this->user->lang('save_suc'), 'color' => 'green');
		} else {
			$message = array('title' => $this->user->lang('error'), 'text' => $this->user->lang('save_nosuc'), 'color' => 'red');	
		}
		
		$this->display($message);
	}
	
	public function edit(){
		$intFeedID = $this->in->get('edit', 0);
		
		$arrCategoryIDs = $this->pdh->sort($this->pdh->get('article_categories', 'id_list', array()), 'article_categories', 'sort_id', 'asc');
		$arrCategories = array();
		foreach($arrCategoryIDs as $caid){
			$arrCategories[$caid] = $this->pdh->get('article_categories', 'name_prefix', array($caid)).$this->pdh->get('article_categories', 'name', array($caid));
		}
		
		
		if($intFeedID){
			$arrFeedData = $this->pdh->get('feedposter_feeds', 'data', array($intFeedID));
			$strFeedName = $arrFeedData['name'];
			
			$this->tpl->assign_vars(array(
				'FEED_NAME'				=> $arrFeedData['name'],
				'FEED_URL'				=> $arrFeedData['url'],
				'FEED_CATEGORY'			=> (new hdropdown('category', array('options' => $arrCategories, 'value' => $arrFeedData['categoryID'])))->output(),
				'FEED_USER'				=> (new hdropdown('user_id', array('options' => $this->pdh->aget('user', 'name', 0, array($this->pdh->get('user', 'id_list'))), 'value' => $arrFeedData['userID'])))->output(),
				'FEED_TAGS'				=> implode(', ', $this->pdh->get('feedposter_feeds', 'tags', array($intFeedID))),
				'FEED_INTERVAL'			=> (new hdropdown('interval', array('options' => $this->user->lang('fp_repeat_inveral'), 'value' => $arrFeedData['interval'])))->output(),
				'FEED_ALLOW_COMMENTS'	=> (new hradio('allow_comments', array('value' => $arrFeedData['allowComments'])))->output(),
				'FEED_MAXPOSTS'			=> (new hspinner('maxposts', array('min' => 0, 'value' => $arrFeedData['maxPosts'])))->output(),
				'FEED_MAXLENGTH'		=> (new hspinner('maxlength', array('min' => 0, 'value' => $arrFeedData['maxTextLength'])))->output(),
				'FEED_FEATURED'			=> (new hradio('featured', array('value' => $arrFeedData['featured'])))->output(),
				'FEED_SHOWDAYS'			=> (new hspinner('showdays', array('min' => 0, 'value' => $arrFeedData['showForDays'])))->output(),
				'FEED_REMOVE_HTML'		=> (new hradio('remove_html', array('value' => $arrFeedData['removeHtml'])))->output(),
			));
		} else {
			$this->tpl->assign_vars(array(
				'FEED_NAME'				=> "",
				'FEED_CATEGORY'			=> (new hdropdown('category', array('options' => $arrCategories, 'value' => 2)))->output(),
				'FEED_USER'				=> (new hdropdown('user_id', array('options' => $this->pdh->aget('user', 'name', 0, array($this->pdh->get('user', 'id_list'))), 'value' => $this->user->id)))->output(),
				'FEED_TAGS'				=> implode(', ', $this->pdh->get('feedposter_feeds', 'tags', array($intFeedID))),
				'FEED_INTERVAL'			=> (new hdropdown('interval', array('options' => $this->user->lang('fp_repeat_inveral'), 'value' => 3600)))->output(),
				'FEED_ALLOW_COMMENTS'	=> (new hradio('allow_comments', array('value' => 1)))->output(),
				'FEED_MAXPOSTS'			=> (new hspinner('maxposts', array('min' => 0, 'value' => 0)))->output(),
				'FEED_MAXLENGTH'		=> (new hspinner('maxlength', array('min' => 0, 'value' => 0)))->output(),
				'FEED_FEATURED'			=> (new hradio('featured', array('value' => 0)))->output(),
				'FEED_SHOWDAYS'			=> (new hspinner('showdays', array('min' => 0, 'value' => 0)))->output(),
				'FEED_REMOVE_HTML'		=> (new hradio('remove_html', array('value' => 0)))->output(),
			));
		}
		
		
		$this->tpl->assign_vars(array(
			'FEEDNAME'	=> (($intFeedID) ? $strFeedName : $this->user->lang('fp_new_feed')),
			'FEED_ID'	=> $intFeedID,
		));
		
		// -- EQDKP ---------------------------------------------------------------
		$this->core->set_vars(array(
				'page_title'		=> (($intFeedID) ? $strFeedName : $this->user->lang('fp_new_feed')).' - '.$this->user->lang('fp_manage_feeds'),
				'template_path'		=> $this->pm->get_data('feedposter', 'template_path'),
				'template_file'		=> 'admin/manage_feeds_edit.html',
				'page_path'			=> [
						['title'=>$this->user->lang('menu_admin_panel'), 'url'=>$this->root_path.'admin/'.$this->SID],
						['title'=>$this->user->lang('feedposter').': '.$this->user->lang('fp_manage_feeds'), 'url'=>$this->root_path.'plugins/feedposter/admin/manage_feeds.php'.$this->SID],
						['title'=> (($intFeedID) ? $strFeedName : $this->user->lang('fp_new_feed')), 'url'=>' '],
				],
				'display'			=> true
		));
	}

	/**
	* display
	* Display the page
	*
	* @param    array  $messages   Array of Messages to output
	*/
	public function display($message=false){
		if($message){
			$this->pdh->process_hook_queue();
			$this->core->messages($message);
		}

		$this->confirm_delete($this->user->lang('fp_confirm_delete_feed'));
		$this->jquery->selectall_checkbox('selall_fields', 'del_ids[]');

		$arrFields = $this->pdh->get('feedposter_feeds', 'id_list', array());

		foreach($arrFields as $id){
			$row = $this->pdh->get('feedposter_feeds', 'data', array($id));

			$this->tpl->assign_block_vars('field_row', array(
				'ID'				=> $row['id'],
				'NAME'				=> $row['name'],
				'S_ENABLED'			=> ($row['enabled']),
				'URL'				=> $row['url'],
				'LAST_UPDATED'		=> $this->time->user_date($row['lastUpdated'], true),
				'S_ERROR'			=> ($row['errorLastUpdated']),
				'ENABLED_ICON'		=> ($row['enabled'] == 1) ? 'eqdkp-icon-online' : 'eqdkp-icon-offline',
				'ENABLE'			=> ($row['enabled'] == 1) ? 'fa fa-check-square-o icon-color-green' : 'fa fa-square-o icon-color-red',
				'L_ENABLE'			=> ($row['enabled'] == 1) ? $this->user->lang('deactivate') : $this->user->lang('activate'),
				'U_EDIT'			=> 'manage_feeds.php'.$this->SID.'&amp;edit='.$row['id'],
				'U_ENABLE'			=> 'manage_feeds.php'.$this->SID.'&amp;'.(($row['enabled'] == 1) ? 'disable' : 'enable').'='.$row['id'].'&amp;link_hash='.(($row['enabled'] == 1) ? $this->CSRFGetToken('disable') : $this->CSRFGetToken('enable')),
				'ERROR_ICON'		=> ($row['errorLastUpdated']) ? 'eqdkp-icon-offline' : 'eqdkp-icon-online',
				'ERROR_TITLE'		=> ($row['errorLastUpdated']) ? $row['errorMessage'] : '',
			));
		}

		
		// -- EQDKP ---------------------------------------------------------------
		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('fp_manage_feeds').' - '.$this->user->lang('feedposter'),
			'template_path'		=> $this->pm->get_data('feedposter', 'template_path'),
			'template_file'		=> 'admin/manage_feeds.html',
			'page_path'			=> [
					['title'=>$this->user->lang('menu_admin_panel'), 'url'=>$this->root_path.'admin/'.$this->SID],
					['title'=>$this->user->lang('feedposter').': '.$this->user->lang('fp_manage_feeds'), 'url'=>' '],
			],
			'display'			=> true,
		));
	}
}
registry::register('FeedPosterFeeds');
?>