<?php
 
 include('simple_html_dom.php');
	
	$username = "";
	$password  ="";
	$url = "https://my.screenname.aol.com/_cqr/login/login.psp";
	$postparams = "sitedomain=startpage.aol.com&siteId=&lang=en&locale=us&authLev=0&siteState=OrigUrl%253Dhttp%25253A%25252F%25252Fwww.aol.com%25252F&isSiteStateEncoded=true&mcState=initialized&uitype=std&use_aam=0&offerId=&seamless=y&regPromoCode=&usrd=6146513&doSSL=&redirType=&xchk=false&tab=&lsoDP=id%3D5CC38C96-EC3B-893C-27D2-DC97A212DBCE&loginId=$username&password=$password";

	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	 curl_setopt ($ch, CURLOPT_VERBOSE, 0);
	curl_setopt ($ch, CURLOPT_HEADER, 1);
	curl_setopt ($ch, CURLINFO_HEADER_OUT,true);
	curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postparams);
	
	$data1 = curl_exec($ch); 
	curl_close ($ch); 
	 
	file_put_contents("a.htm", $data1); 

	// Go To Home page
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt($ch,CURLOPT_URL, "http://mail.aol.com/38442-111/aol-6/en-us/Suite.aspx");
	curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, '_hm.txt'); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	
	
	$out = curl_exec($ch); 
	file_put_contents("b.htm", $out);
	curl_close ($ch); 

	//////////////////

	
	// Go to spam
	$mpfileds = array();
	$mpfileds["user"] = "Cs-MxNW2S2";
	$mpfileds["folder"] = "Spam";
	$mpfileds["showUserFolders"] = "False";
	$boundary  = "-----------------------------243512860422825";
	 
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data; boundary=$boundary"));
	 curl_setopt($ch,CURLOPT_URL, "http://mail.aol.com/38442-111/aol-6/en-us/Lite/MsgList.aspx");
	 curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	 curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	 curl_setopt($ch, CURLOPT_COOKIEFILE, '_hm.txt'); 
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	 $mp =  multipart_build_query($mpfileds, $boundary);
	 
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $mp);
	 $out = curl_exec($ch); 
	 file_put_contents("c.htm", $out);
	 curl_close ($ch); 
	// SPAM RETRIVED

	
	// Parse table 
	$spamPageHTML = $out;
	$dom = str_get_html($spamPageHTML);
	 

	$msgDiv = $dom->find('div.messageListView', 0);
	$msgTbl = $msgDiv->find('table', 0);
	
	/*$emailRows = $msgTbl->find('tr');
	
	echo count($emailRows)." Spam mails";*/
	$msgCheckboxes = $msgTbl->find('input[type=checkbox]');
	echo count($msgCheckboxes)." Spam mails\n";
	
	if(count($msgCheckboxes) > 0) {
		$spamMailIds = array();
		// SPAM MAIL IDS
		foreach($msgCheckboxes as $cb) {
			array_push($spamMailIds, $cb->value);
		 
		}

		
		// Prepare not spam request
		$form = $dom->find('form[name=msgListForm]', 0);
		$fields = $form->find('input[type=hidden]');
		$postparam = "";
		foreach($fields as $hidden) {
			if($hidden->name != "msgActionRequest") {
			  $postparam .= $hidden->name."=".urlencode($hidden->value)."&";
			}
			
		}
		 $postparam .= "&msgActionRequest=Not+Spam";
	 
		
		/*$selectedIDs = "&message_select=".implode("&message_select=", $spamMailIds);
		$postparam .= $selectedIDs;
		
		$postparam .= "&message_select=".urlencode($spamMailIds[1]); */

		foreach($spamMailIds as $id) {
			if(!empty($id)) {
				$postparam .= "&message_select=".urlencode($id);
			}
		}
	 
	 
 
	// Make Not Spam 
		$ch = curl_init();
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt($ch,CURLOPT_URL, "http://mail.aol.com/38442-111/aol-6/en-us/Lite/MsgList.aspx");
	curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, '_hm.txt'); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
	
	 $out = curl_exec($ch); 
	file_put_contents("d.htm", $out);
	curl_close ($ch);
	
		$msgCheckboxes->clear();
		$form->clear();
		$fields->clear();
	}
	// CLEAR ALL MEMORY
		$dom->clear();
		$msgDiv->clear();
		$msgTbl->clear();
		

	
	// Go To Inbox

	 $mpfileds = array();
	$mpfileds["user"] = "Cs-MxNW2S2";
	$mpfileds["folder"] = "Inbox";
	$mpfileds["showUserFolders"] = "False";
	$boundary  = "-----------------------------243512860422825";
	 
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data; boundary=$boundary"));
	 curl_setopt($ch,CURLOPT_URL, "http://mail.aol.com/38442-111/aol-6/en-us/Lite/MsgList.aspx");
	 curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	 curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	 curl_setopt($ch, CURLOPT_COOKIEFILE, '_hm.txt'); 
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	 $mp =  multipart_build_query($mpfileds, $boundary);
	 
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $mp);
	 $out = curl_exec($ch); 
	 file_put_contents("i.htm", $out);
	 curl_close ($ch); 
	
	// Go To Inbox END
	$inboxHTML = $out;
	$dom = str_get_html($inboxHTML);
	$msgDiv = $dom->find('div.messageListView', 0);
	$msgTbl = $msgDiv->find('table', 0);
	
	$tableRows = $msgTbl->find('tr');
	
	$unreadEmails = array();
	foreach($tableRows as $tr) {
		if($tr->class = "row-unselected row-unread") {
			//echo $tr->find('input[type=checkbox]', 0)->value."\n";
			$uid = $tr->find('input[type=checkbox]', 0)->value;
			$uid = str_replace(":", "", $uid);
			array_push($unreadEmails, $uid);
		}
		 
	}
	

	// ReadEMail 
	$uid = $unreadEmails[1];
	$url = "http://mail.aol.com/38442-111/aol-6/en-us/Lite/MsgRead.aspx?folder=Inbox&uid=$uid&seq=0&searchIn=none&searchQuery=&start=0&sort=received&sortDir=descending";

	 $ch = curl_init();
	 curl_setopt($ch,CURLOPT_URL, "$url");
	 curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	 curl_setopt($ch, CURLOPT_COOKIEJAR, '_hm.txt'); 
	 curl_setopt($ch, CURLOPT_COOKIEFILE, '_hm.txt'); 
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	 $out = curl_exec($ch); 
	 file_put_contents("m.htm", $out);
	 curl_close ($ch); 
	echo "read $uid  finished";
	 //Done reading

	function multipart_build_query($fields, $boundary){
	  $retval = '';
	  foreach($fields as $key => $value){
		$retval .= "--$boundary\nContent-Disposition: form-data; name=\"$key\"\n\n$value\n";
	  }
	  $retval .= "--$boundary--";
	  return $retval;
	}
	


?>