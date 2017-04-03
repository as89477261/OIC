<?php

class LuceneController extends ECMController {
	public function createIndexServerAction( ) {
		echo "Document Index Created";
		
		setlocale(LC_ALL, 'th_TH');
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
		$index = Zend_Search_Lucene::create('d:/usr/idx/dev');
		
		$doc = new Zend_Search_Lucene_Document();
		$docUrl = "http://localhost/ECMDev/";
		$docContent = "BenefitECM";
		
		$doc->addField(Zend_Search_Lucene_Field::Text('url', $docUrl));
		$doc->addField(Zend_Search_Lucene_Field::Text('docid', 0));		
		$doc->addField(Zend_Search_Lucene_Field::UnStored('contents',$docContent));
		
		$index->addDocument($doc);
	}
	
	public function createTestDataAction( ) {
		echo "Document Index Created";
		
		setlocale(LC_ALL, 'th_TH');
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
		$index = Zend_Search_Lucene::open('d:/usr/idx/dev');
		
		$doc = new Zend_Search_Lucene_Document();
		$docUrl = "http://localhost/BahamutZero/";
		$docContent = "Zox project";
		
		$doc->addField(Zend_Search_Lucene_Field::Text('url', $docUrl));
		$doc->addField(Zend_Search_Lucene_Field::Text('docid', 2));		
		$doc->addField(Zend_Search_Lucene_Field::UnStored('contents',$docContent));
		
		$index->addDocument($doc);
	}
	
	public function testSearchIndexAction() {
		setlocale(LC_ALL, 'th_TH');
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
		
		$index = Zend_Search_Lucene::open('d:/usr/idx/dev');
		$keyword = "Project";
		$WildcardQueryStr = "$keyword OR *{$keyword}";
		//Zend_Search_Lucene::setDefaultSearchField('url');
		$ParsedNonWildcardQueryStr = Zend_Search_Lucene_Search_QueryParser::parse($keyword);
		$ParsedWildcardQueryStr = Zend_Search_Lucene_Search_QueryParser::parse($WildcardQueryStr);
		$contentTerm  = new Zend_Search_Lucene_Index_Term("ECM", 'contents');		
		$contentQuery = new Zend_Search_Lucene_Search_Query_Term($contentTerm);
		
		$query = new Zend_Search_Lucene_Search_Query_Boolean();
		$query->addSubquery($ParsedNonWildcardQueryStr, true);
		$query->addSubquery($ParsedWildcardQueryStr, true);
		//$query->addSubquery($contentQuery, true);
 
		$hits = $index->find($query);
		$html = "";
		echo "Result<hr/>";
		//var_dump($hits);
		foreach ($hits as $hit) {   
			echo "score:".$hit->score."<br/>";    
			echo "url:".$hit->url."<br/>";    
			echo "docID:".$hit->docid."<br/>";    
			//var_dump($hit);
		}
		//d$highlightedHTML = $userQuery->highlightMatches(UTFDecode($html));
		echo "<br/>";
	}

}
