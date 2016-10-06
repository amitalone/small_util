#!/usr/bin/php
<?php

$DATA_FILE = '';
$OUTPUT_FILE ='';

$SERVICE_URL ='http://send-email.org/tools/sell/verify.php?email=';



readFileData($DATA_FILE, $SERVICE_URL, $OUTPUT_FILE);
function readFileData($fileName, $SERVICE_URL, $outFile)
  {
  	$file = fopen($fileName, 'r');
  	$outFile = fopen($outFile, 'a');
  	if($file)
  	{
  		while(!feof($file))
  		{
  			$line = fgets($file);
  			if(!empty($line))
			{
				list($sbtId, $email, $vendorId, $domainType) = explode("|", $line);
				$contents = file_get_contents($SERVICE_URL.$email );

				//echo $domainType."\r\n";

				$valid = "N";
				if("EDT_COMCAST_NET" == $domainType)
				{
				  $res = strstr($contents, "Try again later");
				  if($res)
				  {
					exit();
				  }
				  $res = strstr($contents, "recipient ok");
				  if($res)
				  {
				   $valid = "Y";
				  }else {
				  	$valid = "N";
				  }
				  logInfo("$email -> $valid");
				  $line = str_replace("\r\n", "", $line);
				  fputs($outFile, "\r\n$line$valid");
				}

				if("EDT_VERIZON" == $domainType)
				{
					//$res = strstr($contents, "unknown or illegal alias");
					$pos1 = strpos($contents, "RCPT TO:");
					if($pos1)
  					{
  						$res = substr($contents, $pos1, 200);
  						$check1 = strstr($res, "250 2.1.5");
    					$check2 = strstr($res, "OK");
    					if($check1 && $check2)
    					{
    					  $valid = "Y";
    					}
  					}

				    logInfo("$email -> $valid");
				   $line = str_replace("\r\n", "", $line);
				  fputs($outFile, "\r\n$line$valid");

				}

				if("EDT_HOTMAIL" == $domainType || "EDT_LIVE" == $domainType || "EDT_MSN" == $domainType || "EDT_HOTMAIL_CO_UK" == $domainType)
				{
					$pos1 = strpos($contents, "RCPT TO:");
					if($pos1)
  					{
  						$res = substr($contents, $pos1, 200);
  						$check1 = strstr($res, "250");
  						$check2 = strstr($res, "$email");
    					if($check1 && $check2)
    					{
    					  $valid = "Y";
    					}
  					}
					logInfo("$email -> $valid");
					$line = str_replace("\r\n", "", $line);
					fputs($outFile, "\r\n$line$valid");
				}

				if("EDT_GMAIL" == $domainType)
								{
					$res = strstr($contents, "The email account that you tried to reach does not exist");
					if($res)
					{
					  $valid = "N";
					} else {
					   $valid = "Y";
					}
					logInfo("$email -> $valid");
					$line = str_replace("\r\n", "", $line);
					fputs($outFile, "\r\n$line$valid");
				}

				if("EDT_YAHOO" == $domainType)
				{

					$res = strstr($contents, "temporarily deferred");
					if(!$res)
					{
					  $res = strstr($contents, "delivery error");

						if($res)
						{
						  $valid = "N";
						} else {
						  $valid = "Y";
						}
					  logInfo("$email -> $valid");
					  $line = str_replace("\r\n", "", $line);
					 fputs($outFile, "\r\n$line$valid");
					}

				}

			}
  		}
  	}
  	fclose($outFile);
	printf("\nVALIDATION FIISHED\n");
  }

function logInfo($message)
  {
  	$logInfoging = true;
  	if($logInfoging = true)
  	{
  		$stamp = date("Y-m-d H:i:s");
  		printf("\n\033[36m$stamp $message\033[0m \n");
  	}
  }
?>
