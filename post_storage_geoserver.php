
<?php
        // Open log file
        $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");

        // Initiate cURL session
        $service = "http://localhost:8080/geoserver/";
        $request = "rest/workspaces/test_php/datastores/"; // to add a new workspace
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
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                          array("Content-type: application/xml"));
        //$xmlStr = '<dataStore><name>DPUPR_TEMATIK</name><type>PostGIS</type><enabled>true</enabled><workspace><name>test_php</name><atom:link xmlns:atom="http://www.w3.org/2005/Atom" rel="alternate" href="http://localhost:8080/geoserver/rest/workspaces/test_php.xml" type="application/xml"/></workspace><connectionParameters><entry key="schema">TEMATIK</entry><entry key="Evictor run periodicity">300</entry><entry key="Max open prepared statements">50</entry><entry key="encode functions">true</entry><entry key="Batch insert size">1</entry><entry key="preparedStatements">false</entry><entry key="database">DPUPR</entry><entry key="host">localhost</entry><entry key="Loose bbox">true</entry><entry key="SSL mode">DISABLE</entry><entry key="Estimated extends">true</entry><entry key="fetch size">1000</entry><entry key="Expose primary keys">false</entry><entry key="validate connections">true</entry><entry key="Support on the fly geometry simplification">true</entry><entry key="Connection timeout">20</entry><entry key="create database">false</entry><entry key="port">5432</entry><entry key="passwd">postgres</entry><entry key="min connections">1</entry><entry key="dbtype">postgis</entry><entry key="namespace">http://test_php</entry><entry key="max connections">10</entry><entry key="Evictor tests per run">3</entry><entry key="Test while idle">true</entry><entry key="user">postgres</entry><entry key="Max connection idle time">300</entry></connectionParameters><__default>false</__default><dateCreated>2022-05-24 14:05:21.710 UTC</dateCreated><featureTypes><atom:link xmlns:atom="http://www.w3.org/2005/Atom" rel="alternate" href="http://localhost:8080/geoserver/rest/workspaces/test_php/datastores/DPUPR_TEMATIK/featuretypes.xml" type="application/xml"/></featureTypes></dataStore>';
		
		$xmlStr= '<dataStore><name>trsss</name><connectionParameters><host>localhost</host><port>5432</port><database>geoportal</database><user>postgres</user><passwd>postgres</passwd><dbtype>postgis</dbtype></connectionParameters></dataStore>';
		
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlStr);

        //POST return code
        $successCode = 201;

        $buffer = curl_exec($ch); // Execute the curl request
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

        curl_close($ch); // free resources if curl handle will not be reused
        fclose($logfh);  // close logfile

?>