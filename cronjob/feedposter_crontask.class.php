<?php
/*	Project:	EQdkp-Plus
 *	Package:	Awards Plugin
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

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

/*+----------------------------------------------------------------------------
  | feedposter_crontask
  +--------------------------------------------------------------------------*/
if ( !class_exists( "feedposter_crontask" ) ) {
	class feedposter_crontask extends crontask {
		public function __construct(){  }


		public function run(){
			register('pm');
			
			$arrFeeds = $this->pdh->get('feedposter_feeds', 'id_list', array());

			foreach($arrFeeds as $intFeedID){
				$arrData = $this->pdh->get('feedposter_feeds', 'data', array($intFeedID));
				if(!$arrData['enabled']) continue;
				
				//Check Interval Time
				if(($arrData['lastUpdated'] + $arrData['interval']) > $this->time->time ) continue;
				
				$strContent = register('urlfetcher')->fetch($arrData['url']);

				if($strContent){
					try{
						$document = new \DOMDocument('1.0', 'UTF-8');
						$document->preserveWhiteSpace = false;
						
						$document->loadXML($strContent);
						
						$xpath = new \DOMXPath($document);
							
						$namespace = $document->documentElement->getAttribute('xmlns');
						$xpath->registerNamespace('ns', $namespace);
						
						$rootNode = $xpath->query('/*')->item(0);
						
						if ($rootNode === null) {
							//Update Error
							$this->pdh->put('feedposter_feeds', 'set_error', array($intFeedID));
							$this->pdh->process_hook_queue();
							continue;
						}
						
						if ($rootNode->nodeName == 'feed') {
							$data = $this->readAtomFeed($xpath, $arrData);
							$strFeedType = 'rss';
						} else if ($rootNode->nodeName == 'rss') {
							$data = $this->readRssFeed($xpath, $arrData);
							$strFeedType = 'rss';
						} else if ($rootNode->nodeName == 'response') {	
							$data = $this->readEQdkpFeed($xpath, $arrData);
							$strFeedType = 'eqdkp';
						} else {
							//Update Error
							$this->pdh->put('feedposter_feeds', 'set_error', array($intFeedID));
							$this->pdh->process_hook_queue();
							continue;
						}
						
						if (empty($data)) continue;

						//Build Hash-Array of Feed
						$arrHashList = $this->pdh->get('feedposter_log', 'hash_list', array($intFeedID));
						
						$intLastUpdate = $arrData['lastUpdated'];
						
						foreach($data as $key => $val){
							if($val['time'] > $intLastUpdate && !in_array($val['hash'], $arrHashList)){
								$strTitle = $val['title'];
								$strText = $val['description'];
								$strText = nl2br($strText);
								
								if($arrData['maxTextLength']){
									if(strlen($strText) > $arrData['maxTextLength']){
										$strText = cut_text($strText, $arrData['maxTextLength'], true);
									}
								}
								if($strFeedType == 'eqdkp'){
									$strText = '<div class="feedposter feedid_'.$intFeedID.' feedtype_'.$strFeedType.' feedsource_category_'.$val['category_id'].'">'.$strText.'</div>';
								} else {
									$strText = preg_replace("'<style[^>]*>.*</style>'siU",'',$strText);
									$strText = '<div class="feedposter feedid_'.$intFeedID.' feedtype_'.$strFeedType.'"><blockquote>'.strip_tags($strText, '<img>').'</blockquote>'.$this->user->lang('fp_source').': <a href="'.sanitize(strip_tags($val['link'])).'">'.sanitize(strip_tags($val['link'])).'</a></div>';
								}
								$strPreviewimage = "";
								$strAlias = $strTitle;
								$intPublished = 1;
								$intFeatured = 0;
								$intCategory = $arrData['categoryID'];
								$intUserID = $arrData['userID'];
								$intComments = $arrData['allowComments'];
								$intVotes = 0;
								$intHideHeader = 0;
								$arrTags = unserialize($arrData['tags']);
								$intDate = $val['time'];
								$strShowFrom = $strShowTo = "";
								
								$blnResult = $this->pdh->put('articles', 'add', array($strTitle, $strText, $arrTags, $strPreviewimage, $strAlias, $intPublished, $intFeatured, $intCategory, $intUserID, $intComments, $intVotes,$intDate, $strShowFrom, $strShowTo, $intHideHeader));
								
								//Write Log
								$this->pdh->put('feedposter_log', 'add', array($intFeedID, $val['hash'], $this->time->time));
								
								//Prevent double input
								$arrHashList[] = $val['hash'];
							}
							
							
						}
						
						//Set Last Updated
						$this->pdh->put('feedposter_feeds', 'set_last_update', array($intFeedID, $this->time->time));
						
					} catch(Exception $e){
						//Update Error
						$this->pdh->put('feedposter_feeds', 'set_error', array($intFeedID));
						$this->pdh->process_hook_queue();
					}

					$this->pdh->process_hook_queue();
				}
			}
			
			
		}
		
		protected function readAtomFeed($xpath, $arrFeedData) {		
			// get items
			$items = $xpath->query('//ns:entry');
			$data = array();
			$i = 0;
			foreach ($items as $item) {
				$strAlternateLink = "";
				$childNodes = $xpath->query('child::*', $item);
				$itemData = array();
				foreach ($childNodes as $childNode) {	
					//  && $childNode->getAttribute('rel') == 'alternate'
					if($childNode->nodeName == 'link'){						
						$strAlternateLink = $childNode->getAttribute('href');
					} else {
						$itemData[$childNode->nodeName] = $childNode->nodeValue;
					}
				}
					
				// validate item data
				if (empty($itemData['title']) || empty($itemData['id']) || (empty($itemData['content']) && empty($itemData['summary']))) {
					continue;
				}
					
				$hash = sha1($itemData['id']);
				if (isset($itemData['published'])) {
					$time = strtotime($itemData['published']);
					if ($time > $this->time->time) continue;
				} else {
					$time = $this->time->time;
				}
				if (!empty($itemData['textContent'])) {
					$description = $itemData['textContent'];
				}elseif (!empty($itemData['content'])) {
					$description = $itemData['content'];
				} else {
					$description = $itemData['summary'];
				}
				
				// get data
				$data[$hash] = array(
						'title'			=> $itemData['title'],
						'link'			=> (strlen($strAlternateLink)) ? $strAlternateLink : $itemData['id'],
						'description'	=> $description,
						'time'			=> $time,
						'hash'			=> $hash,
				);
					
				// check max results
				$i++;
				if ($arrFeedData['maxPosts'] && $i == $arrFeedData['maxPosts']) {
					break;
				}
			}
		
			return $data;
		}
		
		protected function readRssFeed($xpath, $arrFeedData) {
			// get items
			$items = $xpath->query('//channel/item');
			$data = array();
			$i = 0;
			foreach ($items as $item) {
				$childNodes = $xpath->query('child::*', $item);
				$itemData = array();
				foreach ($childNodes as $childNode) {
					if ($childNode->nodeName != 'category') {
						$itemData[$childNode->nodeName] = $childNode->nodeValue;
					}
				}
					
				// validate item data
				if (empty($itemData['title']) || empty($itemData['link']) || (empty($itemData['description']) && empty($itemData['content:encoded']))) {
					continue;
				}
				if (!empty($itemData['guid'])) {
					$hash = sha1($itemData['guid']);
				}
				else {
					$hash = sha1($itemData['link']);
				}
				if (isset($itemData['pubDate'])) {
					$time = strtotime($itemData['pubDate']);
					if ($time > $this->time->time) continue;
				}
				else {
					$time = $this->time->time;
				}
				if (!empty($itemData['content:encoded'])) {
					$description = $itemData['content:encoded'];
				}
				else {
					$description = $itemData['description'];
				}
					
				// get data
				$data[$hash] = array(
					'title' 		=> $itemData['title'],
					'link' 			=> $itemData['link'],
					'description'	=> $description,
					'time'			=> $time,
					'hash'			=> $hash,
				);
					
				// check max results
				$i++;
				if ($arrFeedData['maxPosts'] && $i == $arrFeedData['maxPosts']) {
					break;
				}
			}
		
			return $data;
		}
		
		protected function readEQdkpFeed($xpath, $arrFeedData){
			// get items
			$items = $xpath->query('//entries/entry');
			$data = array();
			$i = 0;

			foreach ($items as $item) {
				$childNodes = $xpath->query('child::*', $item);
				$itemData = array();
				foreach ($childNodes as $childNode) {
					$itemData[$childNode->nodeName] = $childNode->nodeValue;
				}
					
				// validate item data
				if (empty($itemData['title']) || empty($itemData['link']) || empty($itemData['text'])) {
					continue;
				}
				$hash = sha1($itemData['id']);
				
				$time = $itemData['date_timestamp'];
				
				$description = $itemData['text'];

					
				// get data
				$data[$hash] = array(
						'title' 		=> $itemData['title'],
						'link' 			=> $itemData['link'],
						'description'	=> $description,
						'time'			=> $time,
						'hash'			=> $hash,
						'category'		=> $itemData['category_id'],
				);
					
				// check max results
				$i++;
				if ($arrFeedData['maxPosts'] && $i == $arrFeedData['maxPosts']) {
					break;
				}
			}
			
			return $data;
		}
	}
}
?>