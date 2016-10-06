<b>Link Generator</b> <br>
<form method="post" action="linkegenerator.php">
<input type='text' name='linkval' width="200px">
<input type="submit" value="Encode"> 
</form>

<?php
$string ='';
if(isset($_POST['linkval'])) {
	$string =  $_POST['linkval'];
} else {
	exit();
}


$combinations = array();
/***************Upper case advance by 1******************/
$count = strlen($string);
for($i=0; $i<$count; $i++) {
	$ch = strCharAt($string, $i);
	$ch = strtoupper($ch);
	$newString = replaceCharAt($string, $ch, $i);
	//echo replaceCharAt($string, $ch, $i) ."<br>";
	array_push($combinations, $newString);
}
/*********************************/

/************ADAVANCED CAPS by 1*********************/
for($i=0; $i<=$count; $i++) {
	$substr = substr($string, 0, $i);
	$substr = strtoupper($substr);
	$newString = substr_replace($string, $substr, 0, $i);
	//echo $newString."<br>";
	array_push($combinations, $newString);
}
/*********************************/

/************Decreasing CAPS by 1*********************/
for($i=$count; $i>=0; $i--) {
	$length = $count-$i;
	$substr = substr($string, $i, $length);
	$substr = strtoupper($substr);
	$newString = substr_replace($string, $substr, $i, $length);
	//echo $newString."<br>";
	array_push($combinations, $newString);
}
/*********************************/

$uniqueCombination = array_unique($combinations);
//print_r($uniqueCombination);

echo "<Table>";
foreach($uniqueCombination as $str) {
	echo "<tr> <td> $str</td>  <td> </td></tr>";
}

echo "</Table>";

function replaceCharAt($string, $replace, $pos) {
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	$chars[$pos] = $replace;
	return implode($chars);
}

function strCharAt($string, $pos) {
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	return $chars[$pos];
}

function strToHTML($string) {
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	
	$newstr='';
	foreach($chars as $c) {
		$newstr.= '&#'.ord($c).';';
	}
	return $newstr;
}

?>
