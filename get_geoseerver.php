<?php
        // Open log file
        $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");

        // Initiate cURL session
        $service = "http://localhost:8080/geoserver/";
        $request = "rest/workspaces/";
        $url = $service . $request;
        $ch = curl_init($url);

        // Optional settings for debugging
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $logfh);

        //Required GET request settings
        $passwordStr = "admin:geoserver";
        curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

         //GET data
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));

        //GET return code
        $successCode = 200;

        $buffer = curl_exec($ch);
        echo $buffer;
		
		
        // Check for errors and process results
        $info = curl_getinfo($ch);
        if ($info['http_code'] != $successCode) {
          $msgStr = "# Unsuccessful cURL request to ";
          $msgStr .= $url." [". $info['http_code']. "]\n";
          fwrite($logfh, $msgStr);
        } else {
          $msgStr = "# Successful cURL request to ".$url."\n";
          fwrite($logfh, $msgStr);
		  
        }
        fwrite($logfh, $buffer."\n");

        curl_close($ch);
        fclose($logfh);

?>