<?php

class RSSController extends ECMController {
	public function publishedAction() {
		global $conn;
		global $config;
		
		$entries = array ();
		
		// Retrieve the 25 most popular games
		
		// Cycle through the rankings, creating an array storing
		// each, and push the array onto the $entries array
		$sqlGetPublishedItem= "select * from tbl_dms_object where f_published = '1'";
		$rsGetPublishedItem = $conn->Execute($sqlGetPublishedItem);
		foreach ($rsGetPublishedItem as $rawItem) {
			checkKeyCase($rawItem);
			
			$docMain = new DocMainEntity();
			$docMain->Load("f_doc_id = '{$rawItem['f_doc_id']}'");
			
			$pagesSQL = "select count(*) as COUNT_EXP from tbl_doc_page where f_doc_id = '{$rawItem['f_doc_id']}' and f_deleted = 0";
			$rsPageSQL = $conn->Execute($pagesSQL);
			$tmpPages = $rsPageSQL->FetchNextObject();
			
			$description = "เอกสารมีทั้งหมด {$tmpPages->COUNT_EXP} หน้า<br/>";
			
			$docPageSearch = new DocPageEntity();
			$docPages = $docPageSearch->Find("f_doc_id = '{$rawItem['f_doc_id']}' and f_deleted = 0");
			$page =1;
			foreach ($docPages as $docPage) {
				$pageURL ="http://{$_SERVER['SERVER_NAME']}/{$config['appName']}/viewer/view-loader/?docID={$rawItem['f_doc_id']}&pageID={$docPage->f_page_id}";
				$description .= "{$page}.<a href=\"{$pageURL}\"> {$docPage->f_orig_file_name}</a><br/>";
				$page++;
			}
			
			$entry = array (
			'title'=>UTFEncode("{$rawItem['f_name']}"),
			'link'=>"http://localhost/ECMDev/",
			'description'=>UTFEncode($description)
			);	
			
			array_push ( $entries, $entry );
		}
		/*
		foreach ( $rankings as $ranking ) {
			$entry = array ('title' => "{$ranking->title}
                              ({$ranking->platform})", 'link' => "http://www.gamenomad.com/games/
                               {$ranking->asin}", 'description' => "Sales Rank: #{$ranking->rank}" );
			array_push ( $entries, $entry );
		}
		*/
		
		// Create the RSS array
		$rss = array ('title' => UTFEncode('BenefitECM : เอกสารเผยแพร่'), 'link' => 'http://www.gamenomad.com/games/ranks', 'charset' => 'UTF-8', 'entries' => $entries );
		
		// Import the array
		$feed = Zend_Feed::importArray ( $rss, 'rss' );
		
		// Write the feed to a variable
		$rssFeed = $feed->saveXML ();

		$feed->send();
	}
}

