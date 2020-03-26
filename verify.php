
<?php

	$access_token = 'XrQZELc1P7a1hSZkNgkqtITRpec3Ac7GzXVHkykB+63RtRBt9gMrezk19XqFgqhxahu6noyTRNX+1w8z+vKNC7gSNE6HtgMsi0nbJqO6UvjjNOtjaks4SEw+Sp0hVE/iNkZxyLd3TsnWQkXhehCpfgdB04t89/1O/w1cDnyilFU=';
	
	$url 		  = 'https://api.line.me/v1/oauth/verify';
	$headers 	  = array('Authorization: Bearer ' . $access_token);
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result;