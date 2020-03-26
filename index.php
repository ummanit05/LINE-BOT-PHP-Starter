<?php 
	
	define('UPLOAD_DIR', 'tmp_image/');

	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);

	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

	$replyToken  = $deCode['events'][0]['replyToken'];
	$messageType = $deCode['events'][0]['message']['type'];

	//https://developers.line.biz/en/reference/messaging-api/#wh-text

	if($messageType == 'image'){

		$LINEDatas['messageId'] = $deCode['events'][0]['message']['id'];
		$results = getContent($LINEDatas);

		if($results['result'] == 'S') {
			$file = UPLOAD_DIR . uniqid() . '.png';
			$success = file_put_contents($file, $results['response']);
		}

	}    

	$messages 				 = [];
	$messages['replyToken']  = $replyToken;
	$messages['messages'][0] = getFormatTextMessage($deCode['events'][0]['message']['text']." (BOT)");

	$encodeJson = json_encode($messages);

	$LINEDatas['url']   = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "XrQZELc1P7a1hSZkNgkqtITRpec3Ac7GzXVHkykB+63RtRBt9gMrezk19XqFgqhxahu6noyTRNX+1w8z+vKNC7gSNE6HtgMsi0nbJqO6UvjjNOtjaks4SEw+Sp0hVE/iNkZxyLd3TsnWQkXhehCpfgdB04t89/1O/w1cDnyilFU=";

  	$results = sentMessage($encodeJson,$LINEDatas);

	/*Return HTTP Request 200*/
	http_response_code(200);

	function getContent($datas)
	{
	    $datasReturn = [];
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	      CURLOPT_URL =>   "https://api.line.me/v2/bot/message/".$datas['messageId']."/content",
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "GET",
	      CURLOPT_POSTFIELDS => "",
	      CURLOPT_HTTPHEADER => array(
	        "Authorization: Bearer ".$datas['token'],
	        "cache-control: no-cache"
	      ),
	    ));
	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);    if($err){
	      $datasReturn['result'] = 'E';
	      $datasReturn['message'] = $err;
	    }else{
	      $datasReturn['result'] = 'S';
	      $datasReturn['message'] = 'Success';
	      $datasReturn['response'] = $response;
	    }    return $datasReturn;
	}

	function getFormatTextMessage($text)
	{
		$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;

		return $datas;
	}

	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $datas['url'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $encodeJson,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$datas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		    $datasReturn['result'] = 'E';
		    $datasReturn['message'] = $err;
		} else {
		    if($response == "{}"){
				$datasReturn['result'] = 'S';
				$datasReturn['message'] = 'Success';
		    }else{
				$datasReturn['result'] = 'E';
				$datasReturn['message'] = $response;
		    }
		}

		return $datasReturn;
	}

?>