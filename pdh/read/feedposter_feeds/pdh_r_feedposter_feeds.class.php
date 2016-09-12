<?php
/*
* Project:		EQdkp-Plus FeedPoster Plugin
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2010
* Date:			$Date: 2013-01-29 17:35:08 +0100 (Di, 29 Jan 2013) $
* -----------------------------------------------------------------------
* @author		$Author: wallenium $
* @copyright	2006-2014 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.eu
* @package		eqdkpplus
* @version		$Rev: 12937 $
*
* $Id: pdh_r_articles.class.php 12937 2013-01-29 16:35:08Z wallenium $
*/

if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}
				
if ( !class_exists( "pdh_r_feedposter_feeds" ) ) {
	class pdh_r_feedposter_feeds extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $feedposter_feeds = null;

	public $hooks = array(
		'feedposter_feeds_update',
	);		
			
	public $presets = array(
		'feedposter_feeds_id' => array('id', array('%intFeedID%'), array()),
		'feedposter_feeds_name' => array('name', array('%intFeedID%'), array()),
		'feedposter_feeds_url' => array('url', array('%intFeedID%'), array()),
		'feedposter_feeds_categoryID' => array('categoryID', array('%intFeedID%'), array()),
		'feedposter_feeds_userID' => array('userID', array('%intFeedID%'), array()),
		'feedposter_feeds_tags' => array('tags', array('%intFeedID%'), array()),
		'feedposter_feeds_interval' => array('interval', array('%intFeedID%'), array()),
		'feedposter_feeds_lastUpdated' => array('lastUpdated', array('%intFeedID%'), array()),
		'feedposter_feeds_enabled' => array('enabled', array('%intFeedID%'), array()),
		'feedposter_feeds_allowComments' => array('allowComments', array('%intFeedID%'), array()),
		'feedposter_feeds_maxPosts' => array('maxPosts', array('%intFeedID%'), array()),
		'feedposter_feeds_maxTextLength' => array('maxTextLength', array('%intFeedID%'), array()),
		'feedposter_feeds_errorLastUpdated' => array('errorLastUpdated', array('%intFeedID%'), array()),
		'feedposter_feeds_featured' => array('featured', array('%intFeedID%'), array()),
		'feedposter_feeds_showForDays' => array('showForDays', array('%intFeedID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_feedposter_feeds_table');
			
			$this->feedposter_feeds = NULL;
	}
					
	public function init(){
			$this->feedposter_feeds	= $this->pdc->get('pdh_feedposter_feeds_table');				
					
			if($this->feedposter_feeds !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __plugin_feedposter_feeds ORDER by name DESC');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){

					$this->feedposter_feeds[(int)$drow['id']] = array(
						'id'				=> (int)$drow['id'],
						'name'				=> $drow['name'],
						'url'				=> $drow['url'],
						'categoryID'		=> (int)$drow['categoryID'],
						'userID'			=> (int)$drow['userID'],
						'tags'				=> $drow['tags'],
						'interval'			=> (int)$drow['interval'],
						'lastUpdated'		=> (int)$drow['lastUpdated'],
						'enabled'			=> (int)$drow['enabled'],
						'allowComments'		=> (int)$drow['allowComments'],
						'maxPosts'			=> (int)$drow['maxPosts'],
						'maxTextLength'		=> (int)$drow['maxTextLength'],
						'errorLastUpdated'	=> (int)$drow['errorLastUpdated'],
						'featured'			=> (int)$drow['featured'],
						'showForDays'		=> (int)$drow['showForDays'],
						'removeHtml'		=> (int)$drow['removeHtml'],
						'errorMessage'		=> $drow['errorMessage'],
					);
				}
				
				$this->pdc->put('pdh_feedposter_feeds_table', $this->feedposter_feeds, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){		
			if ($this->feedposter_feeds === null) return array();
			return array_keys($this->feedposter_feeds);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID];
			}
			return false;
		}
				
		/**
		 * Returns id for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype id
		 */
		 public function get_id($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['id'];
			}
			return false;
		}

		/**
		 * Returns name for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype name
		 */
		 public function get_name($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['name'];
			}
			return false;
		}

		/**
		 * Returns url for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype url
		 */
		 public function get_url($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['url'];
			}
			return false;
		}

		/**
		 * Returns categoryID for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype categoryID
		 */
		 public function get_categoryID($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['categoryID'];
			}
			return false;
		}

		/**
		 * Returns userID for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype userID
		 */
		 public function get_userID($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['userID'];
			}
			return false;
		}

		/**
		 * Returns tags for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype tags
		 */
		 public function get_tags($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return strlen($this->feedposter_feeds[$intFeedID]['tags']) ? unserialize($this->feedposter_feeds[$intFeedID]['tags']) : '';
			}
			return false;
		}

		/**
		 * Returns interval for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype interval
		 */
		 public function get_interval($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['interval'];
			}
			return false;
		}

		/**
		 * Returns lastUpdated for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype lastUpdated
		 */
		 public function get_lastUpdated($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['lastUpdated'];
			}
			return false;
		}

		/**
		 * Returns enabled for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype enabled
		 */
		 public function get_enabled($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['enabled'];
			}
			return false;
		}

		/**
		 * Returns allowComments for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype allowComments
		 */
		 public function get_allowComments($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['allowComments'];
			}
			return false;
		}

		/**
		 * Returns maxPosts for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype maxPosts
		 */
		 public function get_maxPosts($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['maxPosts'];
			}
			return false;
		}

		/**
		 * Returns maxTextLength for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype maxTextLength
		 */
		 public function get_maxTextLength($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['maxTextLength'];
			}
			return false;
		}

		/**
		 * Returns errorLastUpdated for $intFeedID				
		 * @param integer $intFeedID
		 * @return multitype errorLastUpdated
		 */
		 public function get_errorLastUpdated($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['errorLastUpdated'];
			}
			return false;
		}
		
		/**
		 * Returns featured for $intFeedID
		 * @param integer $intFeedID
		 * @return multitype featured
		 */
		public function get_featured($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['featured'];
			}
			return false;
		}
		
		/**
		 * Returns showForDays for $intFeedID
		 * @param integer $intFeedID
		 * @return multitype showForDays
		 */
		public function get_showForDays($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['showForDays'];
			}
			return false;
		}
		
		public function get_removeHtml($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['removeHtml'];
			}
			return false;
		}
		
		public function get_errorMessage($intFeedID){
			if (isset($this->feedposter_feeds[$intFeedID])){
				return $this->feedposter_feeds[$intFeedID]['errorMessage'];
			}
			return false;
		}

	}//end class
}//end if
?>