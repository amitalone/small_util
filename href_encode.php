<b>Link Encoder</b> <br>
<form method="post" action="htmlencoder.php">
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


/******** Subtitute shy; in plain text***********/

$count = strlen($string);
for($i=7; $i<$count; $i++){
	$part1 = substr($string, 0, $i);
	$part2 = substr($string, $i);
	$link = "$part1&shy;$part2";
	$htmlescape = htmlspecialchars($link);
 	echo "<a href='$link' > $htmlescape </a><br>";
}

/******** Subtitute shy;shy; in plain text***********/

$count = strlen($string);
for($i=7; $i<$count; $i++){
	$part1 = substr($string, 0, $i);
	$part2 = substr($string, $i);
	$link = "$part1&shy;&shy;$part2";
	$htmlescape = htmlspecialchars($link);
 	echo "<a href='$link' > $htmlescape </a><br>";
}

/******** Subtitute &#10 in plain text***********/

$count = strlen($string);
for($i=7; $i<$count; $i++){
	$part1 = substr($string, 0, $i);
	$part2 = substr($string, $i);
	$link = "$part1&#10$part2";
	$htmlescape = htmlspecialchars($link);
 	echo "<a href='$link' > $htmlescape </a><br>";
}

/******** Combine Plan and html***********/
$count = strlen($string);
for($i=7; $i<$count; $i++){
	$part1 = substr($string, 0, $i);
	$part2 = substr($string, $i);
	$part2 = strToHTML($part2);
	$link = "$part1$part2";
	$htmlescape = htmlspecialchars($link);
 	echo "<a href='$link' > $htmlescape </a><br>";
}

$encode = strToHTML($string);
$htmlescape = htmlspecialchars($encode);

echo "<a href='$encode' > $htmlescape </a><br>";

$tokens = explode(';', $encode);

/*****Subtitute Shy; in encoded*******/
$count = count($tokens);
for($i=7; $i<$count; $i++){
 $newChars = array_push_at($tokens, '&shy', $i);
 $link = implode(';', $newChars);
 $htmlescape = htmlspecialchars($link);
 echo "<a href='$link' > $htmlescape </a><br>";
}

/*****Subtitute Shy;Shy; in encoded*******/
for($i=7; $i<$count; $i++){
 $newChars = array_push_at($tokens, '&shy;&shy', $i);
 $link = implode(';', $newChars);
 $htmlescape = htmlspecialchars($link);
 echo "<a href='$link' > $htmlescape </a><br>";
}



function strToHTML($string) {
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	
	$newstr='';
	foreach($chars as $c) {
		$newstr.= '&#'.ord($c).';';
	}
	return $newstr;
}

function array_push_at($array, $element, $pos) {
 $newArray = array();
 for($i=0; $i<$pos; $i++) {
 	$newArray[$i] =  $array[$i];
 }	
 $newArray[$pos] =  $element;

 for($i=$pos+1; $i<count($array); $i++) {
 	$newArray[$i] =  $array[$i -1];
 }
 
 return $newArray;
}

function replaceCharAt($string, $replace, $pos) {
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	$chars[$pos] = $replace;
	return implode($chars);
}
?>