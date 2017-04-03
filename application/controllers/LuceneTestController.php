<?php

/**
 * โปรแกรมทดสอบการค้นหาด้วย Lucene
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Lucene
 *
 */
class LuceneTestController extends ECMController {
	/**
	 * Index
	 *
	 */
	public function indexAction() {
		echo "Search Lucene Test";
		// Create index
		setlocale(LC_ALL, 'th_TH');
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
		$index = Zend_Search_Lucene::create('/usr/idx/test');
		
		$doc = new Zend_Search_Lucene_Document();
		// Store document URL to identify it in the search results
		$docUrl = "http://localhost/ECMDev/";
		$doc->addField(Zend_Search_Lucene_Field::Text('url', $docUrl));
		// Index document contents
		//$docContent =iconv('tis-620', 'utf-8',file_get_contents('c:/aaa.txt'));
		$docContent =file_get_contents('c:/aaa.txt');
		echo $docContent;
		//echo iconv('tis-620', 'UTF-8', $docContent);
		//echo iconv('UTF-8', 'ASCII//TRANSLIT', $docContent);
		//$doc->addField(Zend_Search_Lucene_Field::UnStored('contents', $docContent,'utf-8'));
		//$doc->addField(Zend_Search_Lucene_Field::UnStored('data', $docContent,'utf-8'));
		//$doc->addField(Zend_Search_Lucene_Field::Binary('contents',$docContent));
		$doc->addField(Zend_Search_Lucene_Field::Text('contents',$docContent));
//		$doc->addField(Zend_Search_Lucene_Field::Text('contents',$docContent,'utf-8'));
		// Add document to the index
		$index->addDocument($doc);
		
	}
	
	/**
	 * Search
	 *
	 */
	public function searchAction() {
		$index = Zend_Search_Lucene::open('/usr/idx/test');
		//$queryStr = iconv('TIS-620','UTF-8','ข้อมูล*');
		$queryStr = "BenefitECM";
		$userQuery = Zend_Search_Lucene_Search_QueryParser::parse($queryStr);
		
		//Zend_Search_Lucene_Sea
		$hits = $index->find($userQuery);
		$html = "";
		foreach ($hits as $hit) {   
			echo $hit->score;    
			echo $hit->url;    
			echo UTFDecode($hit->contents);
			$html .= $hit->contents;
			
		}
		$highlightedHTML = $userQuery->highlightMatches(UTFDecode($html));
		echo "<br/>";
		echo $highlightedHTML;
	}
}
