<?php
	$introText = AppletInstance::getAudioSpeechPickerValue('intro');
	$notFoundText = AppletInstance::getAudioSpeechPickerValue('not-found');
	$invalidSelectionText = AppletInstance::getAudioSpeechPickerValue('invalid-selection');
	$dialingText = AppletInstance::getAudioSpeechPickerValue('dialing');
	$searchOn = AppletInstance::getValue('search-on', 'last');
	$numDigits = AppletInstance::getValue('num-digits', '4');
		$numDigits = (int)$numDigits;
		$numDigits = ($numDigits > 0 && $numDigits <= 8)? $numDigits : 4;


	$selection = false;
	if(isset($_REQUEST['look'])){
		$lookup = $_REQUEST["look"];
		$selection = isset($_REQUEST['Digits'])? $_REQUEST['Digits'] : false;
	}else{
		$lookup = isset($_REQUEST['Digits'])? $_REQUEST['Digits'] : false;
	}
	
	$response = new Response();

	if($selection !== false){
		$items = getMatchingItems($lookup, $searchOn, $lookup, $numDigits);
		if(!isset($items[$selection - 1])){
			if($notFoundText){
				$verb = AudioSpeechPickerWidget::getVerbForValue($invalidSelectionText, null);
				$response->append($verb);
			}else{			 
				$response->addSay("Invalid selection.");
			}
			$response->addRedirect($_SERVER["HTTP_REFERER"]);
		}else{
			if($notFoundText){
				$verb = AudioSpeechPickerWidget::getVerbForValue($dialingText, null);
				$response->append($verb);
			}else{			 
				$response->addSay("Connecting.");
			}
			$response->addDial($items[$selection - 1]["phone"]);
		}
	}elseif($lookup !== false){
		// Do directory lookup
		$items = getMatchingItems($lookup, $searchOn, $lookup, $numDigits);

		if(count($items) == 0){
			if($notFoundText){
				$verb = AudioSpeechPickerWidget::getVerbForValue($notFoundText, null);
				$response->append($verb);
			}else{			 
				$gather->addSay("No entries found.");
			}
			$response->addRedirect();
		}else{
			$l = strlen(count($items));
			$gather = $response->addGather(array('numDigits' => $l, 'action' => curPageURL() . '?look=' . $lookup));

			$i = 0;
			foreach($items as $it){

				// Normalize length
				$i++;
				$num = "$i";
				while(strlen($num) < $l){
					$num = "0" . $num;
				}

				$num = implode(' ', str_split($num));

				$gather->addSay("For " . $it["name"] . " press " . $num);
			}
		}
	}else{
		$gather = $response->addGather(compact('numDigits'));

		if($introText){
			$verb = AudioSpeechPickerWidget::getVerbForValue($introText, null);
			$gather->append($verb);
		}else{			 
			if($searchOn == "first"){
				$gather->addSay('Please enter the last ' . $numDigits . ' letters of your party\'s first name using your phone keypad.');
			}else{
				$gather->addSay('Please enter the last ' . $numDigits . ' letters of your party\'s last name using your phone keypad.');
			}
		}
	}
		 
	$response->Respond();

	function stringToDigits($str){
		$str = strtolower($str);
		$from = 'abcdefghijklmnopqrstuvwxyz';
		$to = '22233344455566677778889999';
		return preg_replace('/[^0-9]/', '', strtr($str, $from, $to));
	}

	function getMatchingItems($string, $searchOn, $lookup, $numDigits){
		$result = array();

		foreach(OpenVBX::getUsers() as $ob){
			$ob = (array)$ob;
			$name = $ob["values"][($searchOn == "first")? "first_name" : "last_name"];
			$fname = $ob["values"]["first_name"] . " " . $ob["values"]["last_name"];

			$config = (array)PluginData::get("users-{$ob["values"]["id"]}");
			if(!empty($config) && isset($config["phone"]) && strlen($config["phone"]) > 0 && substr(stringToDigits($name), 0, $numDigits) == substr($lookup, 0, $numDigits)){
				$result[] = array("name" => $fname, "phone" => $config["phone"]);
			}
		}

		return $result;
	}

	function curPageURL(){
		$uri = $_REQUEST["vbxsite"];
		$uri = substr($uri, strrpos($uri, "/") + 1);

		return $uri;
	}
?>