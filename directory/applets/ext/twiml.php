<?php
	$introText = AppletInstance::getAudioSpeechPickerValue('intro');
	$notFoundText = AppletInstance::getAudioSpeechPickerValue('not-found');
	$dialingText = AppletInstance::getAudioSpeechPickerValue('dialing');

	$lookup = isset($_REQUEST['Digits'])? $_REQUEST['Digits'] : false;
	
	$response = new Response();

	if($lookup !== false){
		$extensions = (array)PluginData::get("extensions");
		if(!isset($extensions["e-" . $lookup])){
			if($notFoundText){
				$verb = AudioSpeechPickerWidget::getVerbForValue($notFoundText, null);
				$response->append($verb);
			}else{		 
				$response->addSay("Extension not in use.");
				$response->addRedirect();
			}
		}else{
			if($notFoundText){
				$verb = AudioSpeechPickerWidget::getVerbForValue($dialingText, null);
				$response->append($verb);
			}else{			 
				$response->addSay("Connecting.");
			}
			$response->addDial($extensions["e-" . $lookup]);
		}
	}else{
		$gather = $response->addGather(array("finishOnKey" => "#"));

		if($introText){
			$verb = AudioSpeechPickerWidget::getVerbForValue($introText, null);
			$gather->append($verb);
		}else{			 
			$gather->addSay("Enter your party's extension followed by pound.");
		}
	}
		 
	$response->Respond();
?>