<?php
 
 
 

$ipList = getIPList();

$file = date("d-m-y")."-IP.html";

foreach($ipList as $o) {
	//echo " $tld \n";
	$result = getZENstatus($o);
	updateFile($file, $result);

}

 function getIPList() {
	// Return array of IP/Domain Pair
 }

function getZENstatus($o) {
	$ip = $o->ipaddress;
	list($a, $b, $c, $d) = explode(".", $ip);
	$reverseIP = "$d.$c.$b.$a";
	$command  = "dig +short $reverseIP.zen.spamhaus.org";
	$output = exec($command);
	$result = array();
	$result['ip'] = $ip;
	$result['servername'] = $o->servername;
	if(empty($output)) {
		$result['status'] = false;
		$result['code'] = "Not Listed";
	}else {
		$result['status'] = true;
		$result['code'] = ZENCodeLookup($output);
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
	$ip = $result['ip'];
	$code = $result['code'];
	$servername = $result['servername'];
	$ts = date("d-m-y H:i:s");
	if($status == false) {
		$tr = "</tr><td class='green'>$ts</td><td class='green'>$ip </td> <td class='green'>$code</td><td class='green'>$servername</td></tr>";
	}
	if($status == true) {
		$tr = "</tr><td class='red'>$ts</td><td class='red'>$ip </td> <td class='red'>$code</td><td class='red'>$servername</td></tr>";
	}
	$html .= $tr."\n";
	$html .= "\n</table>";
	file_put_contents($file, $html);

}

function ZENCodeLookup($value) {
	if("127.0.0.2" == $value) {
		return "SBL Direct UBE sources, spam operations & spam services";
	}
	if("127.0.0.3" == $value) {
		return "CBS Direct snowshoe spam sources detected via automation";
	}
	list($a,$b,$c,$d) = explode(".", $value);
	
	if($d >= 4 && $d <= 7 ) {
		return " XBL CBL (3rd party exploits such as proxies, trojans, etc.)";
	}
	if($d >= 10 && $d <= 11 ) {
		return "PBL End-user Non-MTA IP addresses set by ISP outbound mail policy";
	}
}
?>
