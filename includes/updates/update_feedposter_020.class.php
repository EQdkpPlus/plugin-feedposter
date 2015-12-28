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
	header('HTTP/1.0 404 Not Found');exit;
}

include_once(registry::get_const('root_path').'maintenance/includes/sql_update_task.class.php');

if (!class_exists('update_feedposter_020')){
	class update_feedposter_020 extends sql_update_task{

		public $author		= 'GodMod';
		public $version		= '0.2.0';    // new version
		public $name		= 'FeedPoster 0.2.0 Update';
		public $type		= 'plugin_update';
		public $plugin_path	= 'feedposter'; // important!

		/**
		* Constructor
		*/
		public function __construct(){
			parent::__construct();

			// init language
			$this->langs = array(
				'english' => array(
					'update_feedposter_020'		=> 'FeedPoster 0.2.0 Update Package',
					1 => 'Alter FeedPoster Table',
					2 => 'Alter FeedPoster Table'
				),
				'german' => array(
					'update_feedposter_020'		=> 'FeedPoster 0.2.0 Update Paket',
						1 => 'Erweitere FeedPoster Tabelle',
						2 => 'Erweitere FeedPoster Tabelle'
				),
			);

			
			$this->sqls = array(
					1 => 'ALTER TABLE `__plugin_feedposter_feeds` ADD COLUMN `featured` TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\';',
					2 => 'ALTER TABLE `__plugin_feedposter_feeds` ADD COLUMN `showForDays` INT(11) UNSIGNED NOT NULL DEFAULT \'0\';',
			);
		}
		
	}
}
?>
