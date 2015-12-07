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
				
if ( !class_exists( "pdh_r_feedposter_log" ) ) {
	class pdh_r_feedposter_log extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $feedposter_log = null;
	public $feed_mapping = null;

	public $hooks = array(
		'feedposter_log_update',
	);		
			
	public $presets = array(
		'feedposter_log_id' => array('id', array('%intLogID%'), array()),
		'feedposter_log_feedID' => array('feedID', array('%intLogID%'), array()),
		'feedposter_log_hash' => array('hash', array('%intLogID%'), array()),
		'feedposter_log_date' => array('date', array('%intLogID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_feedposter_log_table');
			$this->pdc->del('pdh_feedposter_log_mapping_table');
			$this->feedposter_log = NULL;
	}
					
	public function init(){
			$this->feedposter_log	= $this->pdc->get('pdh_feedposter_log_table');				
			$this->feed_mapping		= $this->pdc->get('pdh_feedposter_log_mapping_table');	
			
			if($this->feedposter_log !== NULL && $this->feed_mapping !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __plugin_feedposter_log');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->feedposter_log[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'feedID'		=> (int)$drow['feedID'],
						'hash'			=> $drow['hash'],
						'date'			=> (int)$drow['date'],
					);
					
					$this->feed_mapping[(int)$drow['feedID']] = (int)$drow['id'];
				}
				
				$this->pdc->put('pdh_feedposter_log_table', $this->feedposter_log, null);
				$this->pdc->put('pdh_feedposter_log_mapping_table', $this->feed_mapping, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->feedposter_log === null) return array();
			return array_keys($this->feedposter_log);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($intLogID){
			if (isset($this->feedposter_log[$intLogID])){
				return $this->feedposter_log[$intLogID];
			}
			return false;
		}
				
		/**
		 * Returns id for $intLogID				
		 * @param integer $intLogID
		 * @return multitype id
		 */
		 public function get_id($intLogID){
			if (isset($this->feedposter_log[$intLogID])){
				return $this->feedposter_log[$intLogID]['id'];
			}
			return false;
		}

		/**
		 * Returns feedID for $intLogID				
		 * @param integer $intLogID
		 * @return multitype feedID
		 */
		 public function get_feedID($intLogID){
			if (isset($this->feedposter_log[$intLogID])){
				return $this->feedposter_log[$intLogID]['feedID'];
			}
			return false;
		}

		/**
		 * Returns hash for $intLogID				
		 * @param integer $intLogID
		 * @return multitype hash
		 */
		 public function get_hash($intLogID){
			if (isset($this->feedposter_log[$intLogID])){
				return $this->feedposter_log[$intLogID]['hash'];
			}
			return false;
		}

		/**
		 * Returns date for $intLogID				
		 * @param integer $intLogID
		 * @return multitype date
		 */
		 public function get_date($intLogID){
			if (isset($this->feedposter_log[$intLogID])){
				return $this->feedposter_log[$intLogID]['date'];
			}
			return false;
		}
		
		public function get_hash_list($intFeedID){
			$out = array();
			if(isset($this->feed_mapping[$intFeedID]) && is_array($this->feed_mapping[$intFeedID])){
				foreach($this->feed_mapping[$intFeedID] as $intLogID){
					$out[] = $this->get_hash($intLogID);
				}
			}
			return $out;
		}

	}//end class
}//end if
?>