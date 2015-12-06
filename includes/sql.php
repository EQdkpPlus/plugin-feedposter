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

$guildrequestSQL = array(
	'uninstall' => array(
		1	=> 'DROP TABLE IF EXISTS `__plugin_feedposter_feeds`',
	),

	'install'   => array(
		1	=> "CREATE TABLE `__plugin_feedposter_feeds` (
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
				`url` VARCHAR(255) NOT NULL COLLATE 'utf8_bin',
				`categoryID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				`userID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				`tags` TEXT NULL COLLATE 'utf8_bin',
				`interval` INT(11) UNSIGNED NOT NULL DEFAULT '3600',
				`lastUpdated` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				`enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				`allowComments` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
				`maxPosts` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				`maxTextLength` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				`errorLastUpdated` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			DEFAULT CHARSET=utf8 COLLATE=utf8_bin;",
	)
);
?>