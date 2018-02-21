<?php
	$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";   
   //***********************************************************************************************************************
   // V1.0 : Script qui mixte deux états actionneur et capteur
   //*************************************** API eedomus ******************************************************************
   
   // recuperation des infos depuis la requete
  
    $periphId = getArg('eedomus_controller_module_id');
    $valeur = getArg("value");
	$display = getArg("display");
    $capteur_api = getArg("capteur_api");
	$actionneur_api = getArg("actionneur_api");
	$capteur_name = getArg("capteur");
	$actionneur_name = getArg("actionneur");
	
	$param_ok = true;
	if ($capteur_name == 'plugin.parameters.CAPTEUR' || $capteur_name == '' || $actionneur_name == 'plugin.parameters.ACTIONNEUR' || $actionneur_name == '' || $capteur_api == 'plugin.parameters.capteur_api' || $capteur_api == '' || $actionneur_api == 'plugin.parameters.action_api' || $actionneur_api == '') {
		$param_ok = false;
	}
	
		
	//Polling
	if ($valeur == "poll") {
		if ($param_ok) {
		//capteur
		$request_text = "";
		$request = getValue($capteur_api, true);
		$request_text = $request['value_text'];
		$request_value = $request['value'];
		if ($request_text == "") {
			$request_text = $request_value;
		}
		$capteur_txt = $capteur_name.": ".$request_text;
		
		//Actionneur
		$request_text = "";
		$request = getValue($actionneur_api, true);
		$request_text = $request['value_text'];
		$request_value = $request['value'];
		if ($request_text == "") {
			$request_text = $request_value;
		}
		$actionneur_txt = $actionneur_name.": ".$request_text;
		
		// Affichage
		if ($display == 0) {
			$restit = $actionneur_txt." / ".$capteur_txt;
		}
		if ($display == 1) {
			$restit = $capteur_txt;
		}
		if ($display == 2) {
			$restit = $capteur_txt." / ".$actionneur_txt;
		}
		} else {
			$restit = "Etat mixte en attente...";
		}
		$xml .= "<ETATMIXTE><DISPLAY>".$restit."</DISPLAY></ETATMIXTE>";
		sdk_header('text/xml');
		echo $xml;
	}
	
	//Action
    else if (is_numeric($valeur) && $param_ok) {
		setValue($actionneur_api, $valeur, $verify_value_list = false, $update_only = false);
		die();
    }
?>