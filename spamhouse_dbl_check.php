<?php
 
  
$TLDS = getTLDList();

$file = date("d-m-y")."-domain.html";

foreach($TLDS as $o) {
	 
	$result = getDBLStatus($o);
	updateFile($file, $result);
	 
}

 function getTLDList() {
	// Return array of IP/Domain Pair
 }

function getDBLStatus($o) {
	$domain = $o->hostname;
	$command  = "dig  +short $domain.dbl.spamhaus.org";
	//$result = dns_get_record($domain);
	//print_r($result);
	$output = exec($command);
	$result = array();
	$result['domain'] = $domain;
	$result['servername'] = $o->servername;
	if(empty($output)) {
		$result['status'] = false;
		$result['code'] = "Not Listed";
	}else {
		$result['status'] = true;
		$result['code'] = DBLCodeLookup($output);
	}
	return $result;
}


function updateFile($file, $result) {
	$html = "<style> .red{background-color: #FE642E;} .green{background-color: #01DF74;} </style>\n<table border='1' cellpadding='4' cellspacing='0' style='border-collapse: collapse;'>\n";
	if(file_exists($file)) {
		$html = file_get_contents($file);
	}
	$html = str_replace("</table>", "", $html);
	$tr = "";

	$status = $result['status'];
	$domain = $result['domain'];
	$code = $result['code'];
	$servername = $result['servername'];
	$ts = date("d-m-y H:i:s");
	if($status == false) {
		$tr = "</tr><td class='green'>$ts</td><td class='green'>$domain </td> <td class='green'>$code</td><td class='green'>$servername</td></tr>";
	}
	if($status == true) {
		$tr = "</tr><td class='red'>$ts</td><td class='red'>$domain </td> <td class='red'>$code</td><td class='red'>$servername</td></tr>";
	}
	$html .= $tr."\n";
	$html .= "\n</table>";
	file_put_contents($file, $html);

}

function DBLCodeLookup($value) {
	if("127.0.1.2" == $value) {
		return "spam domain";
	}
	if("127.0.1.3" == $value) {
		return "spammed redirector domain";
	}
	list($a,$b,$c,$d) = explode(".", $value);
	
	if($d >= 4 && $d <= 19 ) {
		return "spam domain (future use)";
	}
	if($d >= 20 && $d <= 39 ) {
		return "phish domain (future use)";
	}
	if($d >= 40 && $d <= 59 ) {
		return "malware domain (future use)";
	}
	if($d >= 60 && $d <= 79 ) {
		return "Botnet C&C domain (future use)";
	}
	if("127.0.1.255" == $value) {
		return " 	IP queries prohibited!";
	}
	 
}
?>