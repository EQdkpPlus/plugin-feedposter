<?php
/*	Project:	EQdkp-Plus
 *	Package:	GuildRequest Plugin
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
	die('Do not access this file directly.');
}

/*+----------------------------------------------------------------------------
  | pdh_w_feedposter_feeds
  +--------------------------------------------------------------------------*/
if (!class_exists('pdh_w_feedposter_feeds'))
{
	class pdh_w_feedposter_feeds extends pdh_w_generic{

		public function enable($intFeedID){
			$objQuery = $this->db->prepare("UPDATE __plugin_feedposter_feeds :p WHERE id=?")->set(array(
				'enabled' => 1
			))->execute($intFeedID);
			
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return true;
			
			return false;
		}

		public function disable($intFeedID){
			$objQuery = $this->db->prepare("UPDATE __plugin_feedposter_feeds :p WHERE id=?")->set(array(
				'enabled' => 0
			))->execute($intFeedID);
				
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return true;
				
			return false;
		}
		
		public function add($strName, $strURL, $intCategory, $intUser, $strTags, $intInterval, $blnAllowComments, $intMaxPosts, $intMaxLength, $blnFeatured, $intShowDays){
			$schluesselwoerter = preg_split("/[\s,]+/", $strTags);
			$arrTags = array();
			foreach($schluesselwoerter as $val){
				$arrTags[] = utf8_strtolower(str_replace("-", "", $val));
			}
			
			$objQuery = $this->db->prepare("INSERT INTO __plugin_feedposter_feeds :p")->set(array(
				'name' 			=> $strName,
				'url' 			=> $strURL,
				'categoryID'	=> $intCategory,
				'userID'		=> $intUser,
				'tags'			=> serialize($arrTags),
				'`interval`'	=> $intInterval,
				'enabled'		=> 1,
				'allowComments'	=> ($blnAllowComments) ? 1 : 0,
				'maxPosts'		=> $intMaxPosts,
				'maxTextLength'	=> $intMaxLength,
				'featured'		=> ($blnFeatured) ? 1 : 0,
				'showForDays'	=> $intShowDays,
			))->execute();
		
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return $objQuery->insertId;
		
			return false;
		}
	
		public function update($intID, $strName, $strURL, $intCategory, $intUser, $strTags, $intInterval, $blnAllowComments, $intMaxPosts, $intMaxLength, $blnFeatured, $intShowDays){
			$schluesselwoerter = preg_split("/[\s,]+/", $strTags);
			$arrTags = array();
			foreach($schluesselwoerter as $val){
				$arrTags[] = utf8_strtolower(str_replace("-", "", $val));
			}
			
			$objQuery = $this->db->prepare("UPDATE __plugin_feedposter_feeds :p WHERE id=?")->set(array(
				'name' 			=> $strName,
				'url' 			=> $strURL,
				'categoryID'	=> $intCategory,
				'userID'		=> $intUser,
				'tags'			=> serialize($arrTags),
				'`interval`'	=> $intInterval,
				'allowComments'	=> ($blnAllowComments) ? 1 : 0,
				'maxPosts'		=> $intMaxPosts,
				'maxTextLength'	=> $intMaxLength,
				'featured'		=> ($blnFeatured) ? 1 : 0,
				'showForDays'	=> $intShowDays,
			))->execute($intID);
			
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return $intID;
		
			return false;
		}
	
		public function delete($intID){
			$this->db->prepare("DELETE FROM __plugin_feedposter_feeds WHERE id=?")->execute($intID);
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			return true;
		}
	
		public function truncate(){
			$this->db->query("TRUNCATE __plugin_feedposter_feeds");
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			return true;
		}
		
		public function set_error($intFeedID){
			$objQuery = $this->db->prepare("UPDATE __plugin_feedposter_feeds :p WHERE id=?")->set(array(
				'errorLastUpdated' => 1
			))->execute($intFeedID);
				
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return true;
				
			return false;
		}
		
		public function set_last_update($intFeedID, $intTime){
			$objQuery = $this->db->prepare("UPDATE __plugin_feedposter_feeds :p WHERE id=?")->set(array(
					'lastUpdated'		=> $intTime,
					'errorLastUpdated'	=> 0,
			))->execute($intFeedID);
		
			$this->pdh->enqueue_hook('feedposter_feeds_update');
			if ($objQuery) return true;
		
			return false;
		}

	} //end class
} //end if class not exists

?>