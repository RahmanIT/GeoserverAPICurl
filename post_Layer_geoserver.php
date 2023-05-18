<?php
        // Open log file
        $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");

        // Initiate cURL session
        $service = "http://localhost:8080/geoserver/";
        $request = "rest/workspaces/BAPPEDA/datastores/dbgeoportal_public/featuretypes"; // to add a new workspace
        $url = $service . $request;
        $ch = curl_init($url);

        // Optional settings for debugging
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $logfh); // logs curl messages

        //Required POST request settings
        curl_setopt($ch, CURLOPT_POST, True);
        $passwordStr = "admin:geoserver";
        curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

        //POST data
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/xml"));	   
        $xmlStr='<featureType><name>Banjarbakula_AR_50K</name><nativeName>Banjarbakula_AR_50K</nativeName><namespace><name>BAPPEDA</name></namespace><title>Banjarbakula AR 50K</title><abstract>A simple rectangular polygon Banjarbakula AR 50K</abstract><store class="dataStore"><name>BAPPEDA:dbgeoportal_public</name></store></featureType>';


		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlStr);
        //POST return code
        $successCode = 201;
        $buffer = curl_exec($ch); // Execute the curl request

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