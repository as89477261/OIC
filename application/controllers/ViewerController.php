<?php
/**
 * โปรแกรมแสดงเอกสารแนบ
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package controller
 * @category Document
 *
 */
class ViewerController extends ECMController {
	/**
	 * Initializer
	 *
	 */
	public function init() {
		$this->setupECMActionController ();
		$this->setECMViewModule ( 'default' );
	}
	/**
	 * action /default โปรแกรมแสดง Picture View
	 *
	 */
	function default2Action() {
		$docID = $_REQUEST ['docID'];
		$pageID = $_REQUEST ['pageID'];
		if($pageID == 0){
			$pageID = 1;
		}
		$this->ECMView->assign ( 'docID', $docID );
		$this->ECMView->assign ( 'pageID', $pageID);
		
		$dmsUtil = new DMSUtil ( );
		$totalPages = $dmsUtil->getPageCount ( $docID );
		$thumbPage = $dmsUtil->getThumbnailPage ( $pageID );
		
		$this->ECMView->assign ( 'thumbPage', $thumbPage );
		$this->ECMView->assign ( 'totalPage', $totalPages );
		
		echo $this->ECMView->render ( 'defaultViewer.phtml' );
		//echo $this->ECMView->render ( 'default2Viewer.phtml' );
	}
	/**
	 * action /thumbnail โปรแกรมแสดง Thumbnail
	 *
	 */
	function thumbnailAction() {
		global $config;
		global $conn;
		
		header ( "Pragma: private" );
		header ( "Pragma: no-cache" );
		header ( "Pragma: no-store" );
		header ( "Expires: -1" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: no-cache" );
		header ( "Cache-Control: no-store" );
		$docID = $_GET ['docID'];
		
		$dmsUtil = new DMSUtil ( );
		
		$currentPage = $_GET ['thumbPage'];
		
		$maxPage = $dmsUtil->getThumbnailPageCount ( $docID );
		
		$sqlGetPage = "select * from tbl_doc_page where f_doc_id = '{$docID}' order by f_doc_id asc";
		$startRow = ($config ['thumbnailPerPage'] * ($currentPage - 1));
		$rsGetPage = $conn->SelectLimit ( $sqlGetPage, $config ['thumbnailPerPage'], $startRow );
		$pages = Array ();
		foreach ( $rsGetPage as $page ) {
			checkKeyCase ( $page );
			$pages [] = Array ('pageno' => $page ['f_page_no'], 'pageid' => $page ['f_page_id'], 'type' => $page ['f_mime_type'], 'filename' => $page ['f_orig_file_name'], 'size' => $page ['f_file_size'] );
		}
		
		$firstpage = 1;
		$lastpage = $dmsUtil->getThumbnailPageCount ( $docID );
		
		if ($currentPage == 1) {
			$prevpage = $currentPage;
		} else {
			$prevpage = $currentPage - 1;
		}
		
		if ($currentPage < $lastpage) {
			$nextpage = $currentPage + 1;
		} else {
			$nextpage = $lastpage;
		}
		
		$this->ECMView->assign ( 'firstPage', $firstpage );
		$this->ECMView->assign ( 'prevPage', $prevpage );
		$this->ECMView->assign ( 'nextPage', $nextpage );
		$this->ECMView->assign ( 'lastPage', $lastpage );
		$this->ECMView->assign ( 'docID', $docID );
		$this->ECMView->assign ( 'currentPage', $currentPage );
		$this->ECMView->assign ( 'maxPage', $maxPage );
		$this->ECMView->assign ( 'pages', $pages );
		
		echo $this->ECMView->render ( 'thumbnail.phtml' );
	}
	
	/**
	 * action /view โปรแกรมแสดงเอกสาร
	 *
	 */
	function viewAction() {
		global $config;
		global $util;
		header ( "Pragma: private" );
		header ( "Pragma: no-cache" );
		header ( "Pragma: no-store" );
		header ( "Expires: -1" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: no-cache" );
		header ( "Cache-Control: no-store" );
		$docMain = new DocMainEntity ( );
		$docID = $_GET ['docID'];
		$pageID = $_GET ['pageID'];
		$dmsUtil = new DMSUtil();
		
		$firstpage = 1;
		$lastpage = $dmsUtil->getPageCount($docID );
		$currentPage = $pageID;
		if ($currentPage == 1) {
			$prevpage = $currentPage;
		} else {
			$prevpage = $currentPage - 1;
		}
		
		if ($currentPage < $lastpage) {
			$nextpage = $currentPage + 1;
		} else {
			$nextpage = $lastpage;
		}
		
		$this->ECMView->assign ( 'firstPage', $firstpage );
		$this->ECMView->assign ( 'prevPage', $prevpage );
		$this->ECMView->assign ( 'nextPage', $nextpage );
		$this->ECMView->assign ( 'lastPage', $lastpage );
		$this->ECMView->assign ( 'docID', $docID );
		$this->ECMView->assign ( 'pageNo', $pageID );
		$this->ECMView->assign ( 'totalPage', $lastpage );
		
		if ($config ['clusterMode']) {
			$server = $config ['clusterName'];
		} else {
			$server = $_SERVER ['SERVER_NAME'];
		}
		
		if (! $docMain->Load ( "f_doc_id = '$docID'" )) {
			$this->ECMView->assign ( 'title', 'Invalid Link for View Attachments;' );
		} else {
			$this->ECMView->assign ( 'title', "View Attachment of [{$docMain->f_title}]" );
			$page = new DocPageEntity ( );
			
			if (! $page->Load ( "f_doc_id = '{$docID}' and f_page_id = '{$pageID}'" )) {
				$content = "";
			} else {
				$content = "Storage ID : {$page->f_st_id}<br/>";
				$content .= "Mimetype : {$page->f_mime_type}<br/>";
			}
		}
		
		if ($page->f_moved_to_storage == 1) {
			$storage = new StorageEntity ( );
			$storage->Load ( "f_st_id = '{$page->f_st_id}'" );
			$dav = new DAVStorage ( );
			if (trim ( $storage->f_st_server ) != '') {
				if (in_array ( strtolower ( $page->f_extension ), array ('tiff', 'tif', 'jpg', 'jpeg', 'bmp', 'png', 'gif' ) )) {
					$callProgram = "pv2.exe";
					$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path, $storage->f_st_port );
					$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
					$filename = uniqid ( 'file' );
					$fp = fopen ( "d:/ECMDev/public/viewtemp/491/" . $filename . "." . $page->f_extension, "w+" );
					fwrite ( $fp, $contents );
					fclose ( $fp );
					$paramDMS = urlencode('http://' . $server . '/' . $config ['appName'] . '/viewer/view-loader/?docID=' . $docID . '&pageID=' . $pageID . '^0|0^http://' . $server . '/' . $config ['appName'] . '/viewer/save-annotation/?docID=' . $docID . '&pageID=' . $pageID );
					$template = '<img src="http://' . $server . '/cgi-bin/CGIParam.exe?w=200&h=1&p=' . $callProgram . '|'.$paramDMS.'" /><br/>
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="95%" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="http://127.0.0.1/aaa.swf" />
						<param name="loop" value="false" />
						<param name="menu" value="false" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#eeeeee" /> 
						<embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="540" height="120" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" />
					</object>';
					/*
					$template = '<img src="http://' . $server . '/cgi-bin/CGIParam.exe?w=200&h=1&p=' . $callProgram . '|http://' . $server . '/' . $config ['appName'] . '/viewer/view-loader?docID=' . $docID . '&pageID=' . $pageID . '^0|0" /><br/>
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="100%" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="http://127.0.0.1/aaa.swf" />
						<param name="loop" value="false" />
						<param name="menu" value="false" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#eeeeee" /> 
						<!--<embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="540" height="120" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" />-->
					</object>';*/
					//echo $template;
					$this->ECMView->assign ( 'template', $template );
				} else {
					
					$filename = "http://{$server}/{$config['appName']}/viewer/view-loader/?docID={$docID}&pageID={$pageID}";
					
					switch (strtoupper ( $page->f_extension )) {
						case 'PDF' :
							$htmlpreview = "<object id=\"Adobe Acrobat Control for ActiveX\" width=100% height=100% ";
							$htmlpreview .= "classid=\"CLSID:CA8A9780-280D-11CF-A24D-444553540000\"> ";
							$htmlpreview .= "<param name='src' value='$filename'>";
							$htmlpreview .= "<param name='type' value='application/pdf'>";
							$htmlpreview .= "</object>";
							
							break;
						case 'WMV' :
						case 'WAV' :
						case 'WMA' :
						case 'MP3' :
						case 'AVI' :
							$htmlpreview = '<OBJECT classid=clsid:6bf52a52-394a-11d3-b153-00c04f79faa6 height=95% id=objMediaPlayer1 width=99% viewastext>';
							$htmlpreview .= '<param name="URL" value="' . $filename . '" valuetype="ref">';
							$htmlpreview .= '<param name="rate" value="1">';
							$htmlpreview .= '<param name="balance" value="0">';
							$htmlpreview .= '<param name="currentPosition" value="0">';
							$htmlpreview .= '<param name="defaultFrame" value>';
							$htmlpreview .= '<param name="playCount" value="1">';
							$htmlpreview .= '<param name="autoStart" value="-1">';
							$htmlpreview .= '<param name="currentMarker" value="0">';
							$htmlpreview .= '<param name="invokeURLs" value="-1">';
							$htmlpreview .= '<param name="baseURL" value="' . $filename . '" valuetype="ref">';
							$htmlpreview .= '<param name="volume" value="75">';
							$htmlpreview .= '<param name="mute" value="0">';
							$htmlpreview .= '<param name="uiMode" value="full">';
							$htmlpreview .= '<param name="enabled" value="-1">';
							$htmlpreview .= '<param name="enableContextMenu" value="0">';
							$htmlpreview .= '<param name="fullScreen" value="0">';
							$htmlpreview .= '<param name="SAMIStyle" value>';
							$htmlpreview .= '<param name="SAMILang" value>';
							$htmlpreview .= '<param name="SAMIFilename" value>';
							$htmlpreview .= '<param name="captioningID" value></td>';
							$htmlpreview .= '<embed name="movie1" src="' . $filename . '" width="99%" height="95%" bgcolor="ffffff" autoplay="true" cache="true" enablejavascript="true" controller="true">';
							$htmlpreview .= '</OBJECT>';
							break;
						//case 'XLS' :
						//case 'DOC' :
						//case 'PPT' :
						//case 'VSD' :
						//case 'HTM' :
						//case 'HTML' :
						//case 'MPP' :
						default :
							$htmlpreview = "<iframe src='$filename' width=100% height=100%></iframe>";
							break;
					}
					$this->ECMView->assign ( 'template', $htmlpreview );
					//$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path );
				//$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
				//$util->force_download ( $contents, $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
				}
			
			} else {
				die ( 'Server is busy,Please try again' );
			}
		}
		
		echo $this->ECMView->render ( 'viewImage.phtml' );
	}
	
	function movePageAction() {
		global $conn;
		$docID = $_REQUEST ['docID'];
		$pageID = $_REQUEST ['pageID'];
		$page = $_REQUEST['changeto'];
		
		$tmpID = 99999999;
		
		$sqlUpdate1 = "update tbl_doc_page set f_page_id = '{$tmpID}' , f_page_no = '{$tmpID}' where f_doc_id = '{$docID}' and f_page_id = '{$pageID}' and f_page_no='{$pageID}'";
		$sqlUpdate2 = "update tbl_doc_page set f_page_id = '{$pageID}' , f_page_no = '{$pageID}' where f_doc_id = '{$docID}' and f_page_id = '{$page}' and f_page_no='{$page}'";
		$sqlUpdate3 = "update tbl_doc_page set f_page_id = '{$page}' , f_page_no = '{$page}' where f_doc_id = '{$docID}' and f_page_id = '{$tmpID}' and f_page_no='{$tmpID}'";
		
		$conn->Execute($sqlUpdate1);
		$conn->Execute($sqlUpdate2);
		$conn->Execute($sqlUpdate3);
		
		$this->ECMView->assign ( 'docID', $_GET ['docID'] );
		$this->ECMView->assign ( 'pageID', $_GET ['pageID'] );
		
		$dmsUtil = new DMSUtil ( );
		$totalPages = $dmsUtil->getPageCount ( $docID );
		$thumbPage = $dmsUtil->getThumbnailPage ( $pageID );
		
		$this->ECMView->assign ( 'thumbPage', $thumbPage );
		$this->ECMView->assign ( 'totalPage', $totalPages );
		
		echo $this->ECMView->render ( 'defaultViewer.phtml' );
	}
	/**
	 * action /default2 โปรแกรมแสดงเอกสารแนบเก่า
	 *
	 */
	function defaultAction() {
		global $config;
		global $util;
		
		$docMain = new DocMainEntity ( );
		$docID = $_GET ['docID'];
		$pageID = $_GET ['pageID'];
		if ($config ['clusterMode']) {
			$server = $config ['clusterName'];
		} else {
			$server = $_SERVER ['SERVER_NAME'];
		}
		
		if (! $docMain->Load ( "f_doc_id = '$docID'" )) {
			//$title = "Invalid Link for View Attachments";
			$this->ECMView->assign ( 'title', 'Invalid Link for View Attachments;' );
		} else {
			$this->ECMView->assign ( 'title', "View Attachment of [{$docMain->f_title}]" );
			$page = new DocPageEntity ( );
			
			if (! $page->Load ( "f_doc_id = '{$docID}' and f_page_id = '{$pageID}'" )) {
				$content = "";
			} else {
				$content = "Storage ID : {$page->f_st_id}<br/>";
				$content .= "Mimetype : {$page->f_mime_type}<br/>";
			}
			//$filecontents = file_get_contents()
		}
		
		//echo $filePath;
		if ($page->f_moved_to_storage == 1) {
			//die("Load Storage");
			include_once 'DAVStorage.php';
			include_once 'Storage.Entity.php';
			$storage = new StorageEntity ( );
			$storage->Load ( "f_st_id = '{$page->f_st_id}'" );
			$dav = new DAVStorage ( );
			if (trim ( $storage->f_st_server ) != '') {
				/*if (in_array ( strtolower ( $page->f_extension ), array ('tiff', 'tif', 'jpg', 'jpeg', 'bmp', 'png', 'gif' ) )) {
					$callProgram = "pv2.exe";
					$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path, $storage->f_st_port );
					$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
					$filename = uniqid ( 'file' );
					$fp = fopen ( "d:/ECMDev/public/viewtemp/491/" . $filename . "." . $page->f_extension, "w+" );
					fwrite ( $fp, $contents );
					fclose ( $fp );
					$template = '<script type="text/javascript" src="/' . $config ['appName'] . '/js/functions.js"></script><body style="margin: 0px;" onload="resizeToFullscreen();"><img src="http://' . $server . '/cgi-bin/CGIParam.exe?w=600&h=1&p=' . $callProgram . '|http://' . $server . '/' . $config ['appName'] . '/viewer/view-loader/?docID=' . $docID . '&pageID=' . $pageID . '^0|0^http://' . $server . '/' . $config ['appName'] . '/viewer/save-annotation/?docID=' . $docID . '&pageID=' . $pageID . '" /><br/>
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="100%" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="http://127.0.0.1/aaa.swf" />
						<param name="loop" value="false" />
						<param name="menu" value="false" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#eeeeee" /> 
						<!--<embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="540" height="120" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" />-->
					</object></body>';
					echo $template;
					//$dav->connect($storage->f_st_server,$storage->f_st_uid,$storage->f_st_pwd,$storage->f_st_path);
				//$contents = $dav->load("DF","{$page->f_sys_file_name}.{$page->f_extension}");
				//$util->force_download($contents,$page->f_orig_file_name,$page->f_mime_type,$page->f_file_size);   
				} else {*/
					$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path );
					if(ereg('/',$page->f_sys_file_name)) {
						$contents = $dav->load ( ".", "{$page->f_sys_file_name}.{$page->f_extension}" );
						//die('x1');
					} else {
						$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
						//die('x2');
					}
					//$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
					$util->force_download ( $contents, $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
				//}
			
			} else {
				die ( 'Server is busy,Please try again' );
			}
		} else {
			$filePath = $config ['storageTempPath'] . "/{$docID}/{$page->f_sys_file_name}.{$page->f_extension}";
			die ( $page->f_extension );
			if (in_array ( strtolower ( $page->f_extension ), array ('tiff', 'tif', 'jpg', 'jpeg', 'bmp', 'png', 'gif' ) )) {
				if (! in_array ( $page->f_extension, array ('tif', 'tiff' ) )) {
					$callProgram = "pv.exe";
				} else {
					$callProgram = "pv2.exe";
				}
				$template = '<img src="http://' . $config ['db'] ['control'] ['host'] . '/cgi-bin/CGIParam.exe?w=200&h=1&p=' . $callProgram . '|http://' . $config ['db'] ['control'] ['host'] . '/ECMDev/viewer/view-loader?docID=' . $docID . '&pageID=' . $pageID . '^0|0^' . $postAnnURL . '" /><br/>
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="100%" align="middle">
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="movie" value="http://127.0.0.1/aaa.swf" />
					<param name="loop" value="false" />
					<param name="menu" value="false" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#eeeeee" /> 
					<!--<embed src="http://127.0.0.1/aaa.swf" loop="false" menu="false" quality="high" bgcolor="#eeeeee" width="540" height="120" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="https://www.macromedia.com/go/getflashplayer" />-->
				</object>';
				echo $template;
			} else {
				if (file_exists ( $filePath )) {
					$util->force_download ( file_get_contents ( $filePath ), $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
				} else {
					echo "Page does not exists";
				}
			}
		
		}
	
	}
	/**
	 * action /annotation-loader ทำการ Load annotation
	 *
	 */
	function annotationLoaderAction() {
		global $util;
		if (array_key_exists ( 'sub', $_REQUEST )) {
			$subpage = $_REQUEST ['sub'];
		} else {
			$subpage = 1;
		}
		if (file_exists ( "d:/annotation2_{$subpage}.txt" )) {
			$contents = file_get_contents ( "d:/annotation2_{$subpage}.txt" );
			$util->force_download ( $contents, "sys_ann.ann", "text/plain", filesize ( "d:/annotation2_{$subpage}.txt" ) );
		} else {
			$contents = file_get_contents ( "d:/test.ann" );
			$util->force_download ( $contents, "sys_ann.ann", "text/plain", filesize ( "d:/test.ann" ) );
		}
	
	}
	
	/**
	 * action /save-annotation ทำการ Save annotation
	 *
	 */
	function saveAnnotationAction() {
		//$subpage = $_GET['sub'];
		if (array_key_exists ( 'sub', $_REQUEST )) {
			$subpage = $_REQUEST ['sub'];
		} else {
			$subpage = 1;
		}
		//$fp2 = fopen("d:/ann.log","a+");
		//fwrite()
		$fp = fopen ( "d:/annotation2_{$subpage}.txt", "w+" );
		//fwrite($fp,serialize($_POST));
		//fwrite($fp,serialize($_FILES));
		fwrite ( $fp, file_get_contents ( $_FILES ['annfile'] ['tmp_name'] ) );
		fclose ( $fp );
	}
	
	/**
	 * action /view-loader ทำการ Load เอกสารแนบ
	 *
	 */
	function viewLoaderAction() {
		global $config;
		global $util;
		
		$docMain = new DocMainEntity ( );
		$docID = $_GET ['docID'];
		$pageID = $_GET ['pageID'];
		
		if (! $docMain->Load ( "f_doc_id = '$docID'" )) {
			//$title = "Invalid Link for View Attachments";
			$this->ECMView->assign ( 'title', 'Invalid Link for View Attachments;' );
		} else {
			$this->ECMView->assign ( 'title', "View Attachment of [{$docMain->f_title}]" );
			$page = new DocPageEntity ( );
			
			if (! $page->Load ( "f_doc_id = '{$docID}' and f_page_id = '{$pageID}'" )) {
				$content = "";
			} else {
				$content = "Storage ID : {$page->f_st_id}<br/>";
				$content .= "Mimetype : {$page->f_mime_type}<br/>";
			}
			//$filecontents = file_get_contents()
		}
		
		//echo $filePath;
		if ($page->f_moved_to_storage == 1) {
			//die("Load Storage");
			//echo "case 1";
			//die($content);
			include_once 'DAVStorage.php';
			include_once 'Storage.Entity.php';
			$storage = new StorageEntity ( );
			$storage->Load ( "f_st_id = '{$page->f_st_id}'" );
			$dav = new DAVStorage ( );
			//die('DF/'.$page->f_sys_file_name.".".$page->f_extension);
			
			if (trim ( $storage->f_st_server ) != '') {
				$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path );
				
				if(ereg('/',$page->f_sys_file_name)) {
					$contents = $dav->load ( ".", "{$page->f_sys_file_name}.{$page->f_extension}" );
					//die('x1');
				} else {
					$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
					//die('x2');
				}
				$util->force_download ( $contents, $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
			} else {
				die ( 'Server is busy,Please try again' );
			}
		} else {
			//echo "case 2";
			//die($content);
			$filePath = $config ['storageTempPath'] . "/{$docID}/{$page->f_sys_file_name}.{$page->f_extension}";
			
			if (file_exists ( $filePath )) {
				$util->force_download ( file_get_contents ( $filePath ), $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
			} else {
				echo "Page does not exists";
			}
		}
	}
	
	/**
	 * โปรแกรมแสดงเอกสารแนบของคำสั่ง/ประกาศ
	 *
	 */
	function announceAction() {
		global $config;
		global $util;
		
		//include_once 'Announce.Entity.php';
		//include_once 'AnnouncePage.Entity.php';
		$docMain = new AnnounceEntity ( );
		$docID = $_GET ['docID'];
		$pageID = $_GET ['pageID'];
		
		if (! $docMain->Load ( "f_announce_id = '$docID'" )) {
			//$title = "Invalid Link for View Attachments";
			$this->ECMView->assign ( 'title', 'Invalid Link for View Attachments;' );
		} else {
			$this->ECMView->assign ( 'title', "View Attachment of [{$docMain->f_title}]" );
			$page = new AnnouncePageEntity ( );
			
			if (! $page->Load ( "f_announce_id = '{$docID}' and f_page_id = '{$pageID}'" )) {
				$content = "";
			} else {
				$content = "Storage ID : {$page->f_st_id}<br/>";
				$content .= "Mimetype : {$page->f_mime_type}<br/>";
			}
			//$filecontents = file_get_contents()
		}
		
		//die();
		

		//echo $filePath;
		if ($page->f_moved_to_storage == 1) {
			//die("Load Storage");
			include_once 'DAVStorage.php';
			include_once 'Storage.Entity.php';
			$storage = new StorageEntity ( );
			$storage->Load ( "f_st_id = '{$page->f_st_id}'" );
			$dav = new DAVStorage ( );
			if (trim ( $storage->f_st_server ) != '') {
				$dav->connect ( $storage->f_st_server, $storage->f_st_uid, $storage->f_st_pwd, $storage->f_st_path );
				$contents = $dav->load ( "DF", "{$page->f_sys_file_name}.{$page->f_extension}" );
				$util->force_download ( $contents, $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
			} else {
				die ( 'Server is busy,Please try again' );
			}
		} else {
			$filePath = $config ['storageTempPath'] . "/{$docID}/{$page->f_sys_file_name}.{$page->f_extension}";
			if (file_exists ( $filePath )) {
				$util->force_download ( file_get_contents ( $filePath ), $page->f_orig_file_name, $page->f_mime_type, $page->f_file_size );
			} else {
				echo "Page does not exists";
			}
		}
	
	}
	
	/**
	 * โปรแกรมอสดงเอกสารแนบของ Temp
	 *
	 */
	function tempAction() {
		global $config;
		global $util;
		
		//include_once 'DocPageTemp.Entity.php';
		
		$docID = $_GET ['docID'];
		$pageID = $_GET ['pageID'];
		
		$userTempPath = $config ['tempPath'] . "{$_SESSION['accID']}";
		//echo $userTempPath;
		

		global $conn;
		$conn->debug = true;
		$DocPage = new DocPageTempEntity ( );
		if (! $DocPage->Load ( "f_doc_id = '{$docID}' and f_page_id = '{$pageID}'" )) {
			
		//echo "Page does not exists";
		//die();
		} else {
			$tmpFilePath = "{$userTempPath}/create/{$DocPage->f_sys_file_name}.{$DocPage->f_extension}";
			//echo $tmpFilePath;
			//die();
			if (file_exists ( $tmpFilePath )) {
				$util->force_download ( file_get_contents ( $tmpFilePath ), $DocPage->f_orig_file_name, $DocPage->f_mime_type, $DocPage->f_file_size );
			} else {
				echo "Page does not exists";
			}
		}
	}
}
