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
  | pdh_w_feedposter_log
  +--------------------------------------------------------------------------*/
if (!class_exists('pdh_w_feedposter_log'))
{
	class pdh_w_feedposter_log extends pdh_w_generic{
		
		public function add($intFeedID, $strHash, $intDate){
			
			$objQuery = $this->db->prepare("INSERT INTO __plugin_feedposter_log :p")->set(array(
				'feedID' 	=> $intFeedID,
				'hash' 		=> $strHash,
				'date'		=> $intDate,
			))->execute();
		
			$this->pdh->enqueue_hook('feedposter_log_update');
			if ($objQuery) return $objQuery->insertId;
		
			return false;
		}

		public function deleteForFeed($intFeedID){
			$this->db->prepare("DELETE FROM __plugin_feedposter_log WHERE feedID=?")->execute($intFeedID);
		}
	
		public function truncate(){
			$this->db->query("TRUNCATE __plugin_feedposter_log");
			$this->pdh->enqueue_hook('feedposter_log_update');
			return true;
		}

	} //end class
} //end if class not exists

?>