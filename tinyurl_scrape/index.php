<?php
require 'simple_html_dom.php';


$domainlist = array(
 "abcd.com"
);

foreach($domainlist as $domain) {
	 getTiny($domain);
}

function generatePassword($length=9,$level=2)
{

	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));

	$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
	$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$password  = "";
	$counter   = 0;

	while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

		// All character must be different
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
			$counter++;
		}
	}

	return $password;
}


	

function getTiny($domain) {
	$alias = generatePassword();
	$tget = "http://tinyurl.com/create.php?source=indexpage&url=$domain&submit=Make+TinyURL%21&alias=$alias";
	$html = chGet($tget);
	
	$html = str_get_html($html);
	$suc = $html->find('div[id=success]', 0);
	if($suc) {
		
		$txt = $suc->parent();
		$txt = $txt->find('b', 0)->plaintext;
		echo  "Domain: $domain , TINY &nbsp;&nbsp; $txt <br>"; 
	}

}

function chGet($url) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "$url");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response =curl_exec($ch); 
		return $response;
	}
	
	function getCurlObject() {
		$ch = curl_init();
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36';
		curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		return $ch;	
	}

?>