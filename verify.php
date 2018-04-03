
<?php

	$access_token = 'HspCUc6oAStzfcVTfpNN7JM4FmUZtEY0Nosd0B+uFtWxJ785gx6lTjNf2zlQKxnCuB9MxEqFt8oTuHWWJkoOPko7g1lYQOXn8jb1V9ksnEetrXiMGpsioGZZ5b3mpPM2qCVyBzkgEcJtqDlr7LGkGAdB04t89/1O/w1cDnyilFU=';
	$url 		  = 'https://api.line.me/v1/oauth/verify';
	$headers 	  = array('Authorization: Bearer ' . $access_token);
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result;