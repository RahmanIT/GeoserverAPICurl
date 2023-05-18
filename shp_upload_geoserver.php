<?php
function prepUL(){
	//checks to see if there was a file uploaded from the file input named 'upload'.
		if($_FILES){
			
			if(@is_uploaded_file($_FILES['upload']['tmp_name'])){
				//if yes, then the restUpload function is run.
				$this->restUpload();
			}
		}
		
	}// end of prepUL function
	

function restUpload(){
	
	// Open log file - will be created in the same folder where this file is saved. if it already exists it will be overwritten
	    $logfh = fopen("GeoserverPHP.log", 'w') or die("can't open log file");
	    
	// Get the uploaded file from the form and do some work with names
		$data = $_FILES['upload']; // Retrieve the file specified in a form input field with <input type="file" name="upload" id="upload">
		$filename ='b'.time().$data['name']; // creating a dynamic file name by adding the letter 'b' and a time stamp to the file name.
		move_uploaded_file($_FILES['upload']['tmp_name'],$filename); // Moving the file from a temporary folder to a more permenant location on the same server 
		$fp = fopen($filename,'r'); // Open the file
		$file_parts = pathinfo($filename); // use pathinfo to get an array of file name parts 
		$file = $file_parts['filename']; // get the file name without extension - makes for a better store name
		
	// setup some variables you'll use to initiate cURL session
//Edit $service and $request to match your setup.
	    $service = "http://maps.example.com/geoserver/"; // replace with the URL to your Geoserver installation that has the REST extension installed
	    $request = "rest/workspaces/test_ws/datastores/".$file."/file.shp"; // the rest operation to create a datastore & layer from the shapefile within the uploaded zip file
		// in the above request, 'test_ws' should be replaced with the workspace you want the datastore created within. 
		// if the workspace doesn't exist, the datastore will be created in your default workspace. see the REST extension documentation on the Geoserver website to learn more.

	    $url = $service . $request; // puts the geoserver url and REST request together into one url. 
	    
	// Initiate cURL session
	    $ch = curl_init($url); 
	    
	// Set several cURL options 
		//cURL options for uploading the ZIP file and sending the REST request
		curl_setopt($ch, CURLOPT_PUT, 1); 
		curl_setopt($ch, CURLOPT_UPLOAD,true); 
		curl_setopt($ch, CURLOPT_REFERER,true);
		curl_setopt($ch, CURLOPT_INFILE,$fp);
		curl_setopt($ch, CURLOPT_INFILESIZE,filesize($filename));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/zip')); // sets the content type in the request header
		
		// Optional settings for debugging
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string
	    curl_setopt($ch, CURLOPT_VERBOSE, true);
	    curl_setopt($ch, CURLOPT_STDERR, $logfh); // logs curl messages
	    
//Edit $passwordStr to be a valid username:password combination for your Geoserver setup.
		$passwordStr = "user:password"; // replace with your username:password
	    curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);
		
	    //PUT return code - used when writing the log file. 
	    $successCode = 201;
	
	    $buffer = curl_exec($ch); // Execute the curl request
	
	    // Check for errors and process results and write them out to the log file.
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
		
}// end function
?>