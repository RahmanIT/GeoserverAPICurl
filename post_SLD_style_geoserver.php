<?php
 $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");
 
         // Initiate cURL session
        $service = "http://localhost:8080/geoserver/";
        $request = "rest/styles"; // to add a new workspace
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
        $xmlStr='<style><name>ungu</name><filename>ungu.sld</filename></style>';


		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlStr);
        //POST return code
        $successCode = 201;
        $buffer = curl_exec($ch); // Execute the curl request
 

    $data = '<StyledLayerDescriptor version="1.0.0" xmlns="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc"
  xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.opengis.net/sld http://schemas.opengis.net/sld/1.0.0/StyledLayerDescriptor.xsd">
  <NamedLayer>
    <Name>ungu</Name>
    <UserStyle>
      <Name>ungu</Name>
      <Title>ungu polygon</Title>
      <Abstract>ungu fill with black outline</Abstract>
      <FeatureTypeStyle>
        <Rule>
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill">#453386</CssParameter>
            </Fill>
            <Stroke />
          </PolygonSymbolizer>
        </Rule>
      </FeatureTypeStyle>
    </UserStyle>
  </NamedLayer>
</StyledLayerDescriptor>';

    $contentType = 'application/vnd.ogc.sld+xml';
    $ch = curl_init();
	$url = 'http://localhost:8080/geoserver/rest/styles/ungu.sld';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, "admin:geoserver");

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array("Content-Type: $contentType",
        'Content-Length: '.strlen(stripslashes($data)))
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 $buffer = curl_exec($ch);

   $successCode='201';

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