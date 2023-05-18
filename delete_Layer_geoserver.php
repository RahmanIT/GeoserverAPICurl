
<?php
        // Open log file
        $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");

        // Initiate cURL session
        $service = "http://localhost:8080/geoserver/";
        $request = "rest/layers/test_php:Banjarbakula_AR_50K.xml"; // to add a new workspace
        $url = $service . $request;
        $ch = curl_init($url);
		
		//proses delete ke dua
		//rest/workspaces/test_php/datastores/geoportal/featuretypes/Banjarbakula_AR_50K.html

        // Optional settings for debugging
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $logfh); // logs curl messages

        //Required Delete request settings
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $passwordStr = "admin:geoserver";
        curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

        //POST data
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/xml"));	   

        //DELETE return code
        $successCode = 200;
		
        $buffer = curl_exec($ch); // Execute the curl request
	
	//==========================================================================================
	$request = "rest/workspaces/test_php/datastores/geoportal/featuretypes/Banjarbakula_AR_50K.xml"; // to add a new workspace
	$url = $service . $request;
	$ch = curl_init($url);
	
	 // Optional settings for debugging
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_STDERR, $logfh); // logs curl messages

	//Required Delete request settings
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	$passwordStr = "admin:geoserver";
	curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

	//POST data
	curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/xml"));	   

	//DELETE return code
	$successCode = 200;
	
	$buffer = curl_exec($ch); // Execute the curl request
		
		
//####################### REPORT results  ##########################################		
	// Check for errors and process results
	$info = curl_getinfo($ch);
	if ($info['http_code'] != $successCode) {
	  $msgStr = "# Unsuccessful cURL request to ";
	  $msgStr .= $url." [". $info['http_code']. "]\n";
	  echo $msgStr;
	  fwrite($logfh, $msgStr);
	} else {
	  $msgStr = "# Successful cURL request to ".$url."\n";
	  echo $msgStr;
	  fwrite($logfh, $msgStr);
	}
	fwrite($logfh, $buffer."\n");
	
	curl_close($ch); // free resources if curl handle will not be reused
	fclose($logfh);  // close logfile

?>