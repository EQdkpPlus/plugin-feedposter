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
	header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
	'feedposter'				=> 'FeedPoster',

	// Description
	'feedposter_short_desc'		=> 'FeedPoster',
	'feedposter_long_desc'		=> 'FeedPoster posts automatically entries from RSS or Atom-Feeds.',

	'fp_manage_feeds'			=> 'Manage Feeds',
	'fp_plugin_not_installd'	=> 'The FeedPoster-Plugin is not installd.',
	'fp_new_feed'				=> 'Add Feed',
	'fp_confirm_delete_feed'	=> 'Are your sure that you want to delete the following feeds? %s',
	'fp_url'					=> 'Feed-URL',
	'fp_last_updated'			=> 'Last fetch of Feed',
	'fp_last_status'			=> 'Status of last fetch',
	'fp_enable_suc'				=> 'Feed %s successfully enabled',
	'fp_enable_nosuc'			=> 'During enabling of Feed %s, an error occured',
	'fp_disable_suc'			=> 'Feed %s successfully disabled',
	'fp_disable_nosuc'			=> 'During disabling of Feed %s, an error occured',
	'fp_delete_suc'				=> 'The selected feeds have been deleted successfully.',
	'fp_delete_nosuc'			=> 'During deletion of the selected feeds, an error occured.',
	'fp_repeat_inveral'			=> array(
		'600' => 'all 10 minutes',
		'1200' => 'all 20 minutes',
		'1800' =>  'all 30 minutes',
		'3600' => 'all 60 minutes',
		'7200' => 'all 2 hours',
		'21600' => 'all 6 hours',
		'86400' => 'all 24 hours',
	),
	'fp_article_data' 			=> 'Article-Data',
	'fp_interval'				=> 'Frequency of fetching feed',
	'fp_maxposts'				=> 'Number of importing entries',
	'fp_maxlength'				=> 'Maximum length of importing entries',
	'fp_maxposts_help'			=> 'Insert 0 to import all entries',
	'fp_maxlength_help'			=> 'Insert 0 to import the whole text of the entry',
	'fp_entries'				=> 'Entries',
	'fp_chars'					=> 'Chars',
	'fp_source'					=> 'Source',
	'fp_showdays'				=> 'Number of Days the article should be shown',
	'fp_showdays_help'			=> 'Insert 0 to always show the article',
	'fp_days'					=> 'Days',
	'fp_remove_html'			=> 'Remove HTML',
	'fp_feed_info'				=> 'Only feeds with a valid RSS or ATOM-Format will be accepted. Feeds with a not valid format can break the display of your page. Also, the EQdkp Plus API for retrieving the latest Articles from other EQdkp Plus installations is supported.'
);

?>
