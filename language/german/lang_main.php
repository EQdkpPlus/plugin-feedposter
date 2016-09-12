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
	'feedposter'					=> 'FeedPoster',

	// Description
	'feedposter_short_desc'		=> 'FeedPoster',
	'feedposter_long_desc'		=> 'FeedPoster postet automatisch Artikel von RSS oder Atom-Feeds.',

	'fp_manage_feeds'			=> 'Feeds verwalten',
	'fp_plugin_not_installed'	=> 'Das FeedPoster-Plugin ist nicht installiert.',
	'fp_new_feed'				=> 'Feed hinzufügen',
	'fp_confirm_delete_feed'	=> 'Bist du sicher, dass die Feeds %s gelöscht werden sollen?',
	'fp_url'					=> 'Feed-URL',
	'fp_last_updated'			=> 'Feed zuletzt abgerufen',
	'fp_last_status'			=> 'Letzter Abrufstatus',
	'fp_enable_suc'				=> 'Der Feed %s wurde erfolgreich aktiviert',
	'fp_enable_nosuc'			=> 'Beim Aktivieren des Feeds %s ist ein Fehler aufgetreten',
	'fp_disable_suc'			=> 'Der Feed %s wurde erfolgreich deaktiviert',
	'fp_disable_nosuc'			=> 'Beim Deaktivieren des Feeds %s ist ein Fehler aufgetreten',
	'fp_delete_suc'				=> 'Die ausgewählten Feeds wurden erfolgreich gelöscht',
	'fp_delete_nosuc'			=> 'Beim Löschen der ausgewählten Feeds ist ein Fehler aufgetreten',
	'fp_repeat_inveral'			=> array(
		'600' => 'alle 10 Minuten',
		'1200' => 'alle 20 Minuten',
		'1800' =>  'alle 30 Minuten',
		'3600' => 'alle 60 Minuten',
		'7200' => 'alle 2 Stunden',
		'21600' => 'alle 6 Stunden',
		'86400' => 'alle 24 Stunden',
	),
	'fp_article_data' 			=> 'Artikel-Daten',
	'fp_interval'				=> 'Abruf-Frequenz',
	'fp_maxposts'				=> 'Anzahl der importierten Einträge',
	'fp_maxlength'				=> 'Maximale Länge der importierten Einträge',
	'fp_maxposts_help'			=> 'Trage 0 ein, um alle neuen Einträge zu importieren',
	'fp_maxlength_help'			=> 'Trage 0 ein, um den kompletten Text des Einträges zu importieren.',
	'fp_entries'				=> 'Einträge',
	'fp_chars'					=> 'Zeichen',
	'fp_source'					=> 'Quelle',
	'fp_showdays'				=> 'Anzahl der Tage, die der Artikel angezeigt werden soll',
	'fp_showdays_help'			=> 'Trage 0 ein, um den Artikel unbegrenzt anzuzeigen',
	'fp_days'					=> 'Tage',
	'fp_remove_html'			=> 'HTML entfernen',
	'fp_feed_info'				=> 'Es werden nur Feeds akzeptiert, die dem RSS oder ATOM-Format entsprechen. Fehlerhafte Feeds können zur Darstellungsproblemen auf der Seite führen. Außerdem wird die EQdkp-Plus API für die letzten Artikel unterstützt, so dass Artikel aus anderen EQdkp Plus Installation eingebunden werden können.'
);

?>
