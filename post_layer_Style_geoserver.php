<?php
function change_layer_style($url_layer,$style_name) {
    $params = '<layer><defaultStyle><name>'.$style_name.'</name></defaultStyle><enabled>true</enabled></layer>';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url_layer);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_USERPWD,"admin:geoserver"); //geoserver.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Receive server response ...
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
	}
    $your_workspace = "test_php";
	$your_layer_name = "Banjarbakula_AR_50K.xml";
	
    $url_layer = "http://localhost:8080/geoserver/rest/layers/".$your_workspace.":".$your_layer_name;
    $style_name ="green";

    //--> call above function.
    change_layer_style($url_layer,$style_name);
?>