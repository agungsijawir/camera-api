<?php
	/**
	 * filename: proces_2.php
	 * Copyright (C) 2014 Agung Andika
	 *
	 * Process input jpeg which taken by camera mobile phones (currently Android JB 4.1.x and up) 
	 * 
	 * version 0.1 - 28 December 2014
	 * - 1st release
	 * 
	 * includes example from:
	 * - IPTC.php | Copyright (C) 2004, 2005  Martin Geisler. | Code licensed under GPL v2
	 */

	include 'IPTC.php';
	$target_dir = "uploads/";
	$file_name_before = basename($_FILES["takePictureFieldBefore"]["name"]);
	$file_name_after = basename($_FILES["takePictureFieldAfter"]["name"]);

	$target_file_before = $target_dir . $file_name_before;
	$target_file_after = $target_dir . $file_name_after;

	$uploadOk = 1;
	$imageFileType_before = pathinfo($target_file_before, PATHINFO_EXTENSION);
	$imageFileType_after = pathinfo($target_file_after, PATHINFO_EXTENSION);

	// Check if image file is a actual image or fake image
	if(isset($_POST)) {
		// check existance upload target directory
		if( !is_dir($target_dir) ) @mkdir($target_dir);

	    $check_before = getimagesize($_FILES["takePictureFieldBefore"]["tmp_name"]);
	    if($check_before !== false) 
	    { 
	        $uploadOk = 1;
	        if (file_exists($target_file_before)) {
			    unlink($target_file_before);
			}
	    } else {
	        echo "File Before is not an image.";
	        $uploadOk = 0;
	    }

	    $check_after = getimagesize($_FILES["takePictureFieldAfter"]["tmp_name"]);
	    if($check_after !== false) 
	    { 
	        $uploadOk = 1;
	        if (file_exists($target_file_after)) {
			    unlink($target_file_after);
			}
	    } else {
	        echo "File After is not an image.";
	        $uploadOk = 0;
	    }

	    // Check if $uploadOk is set to 0 by an error
		if ($uploadOk === 0) {
		    echo "Sorry, your file was not uploaded.";
		} else {
			$do_upload_before = move_uploaded_file($_FILES["takePictureFieldBefore"]["tmp_name"], $target_file_before);
			$do_upload_after = move_uploaded_file($_FILES["takePictureFieldAfter"]["tmp_name"], $target_file_after);

		    if ($do_upload_before && $do_upload_after)
		    {
		    	// write final EXIF TAG
		    	$objIPTC_before = new IPTC($target_file_before);
		    	$objIPTC_before->setValue(IPTC_COPYRIGHT_STRING, "A copyright notice");
		    	$objIPTC_before->setValue(IPTC_CAPTION, "A caption descriptions for this picture [BEFORE]."); 
		    	$objIPTC_before->setValue(IPTC_FIXTURE_IDENTIFIER, "fixture identifier");
		    	$objIPTC_before->setValue(IPTC_CREDIT, "IPTC_CREDIT");
		    	$objIPTC_before->setValue(IPTC_ORIGINATING_PROGRAM, "originating apps");
		    	$objIPTC_before->setValue(IPTC_SOURCE, "IPTC_SOURCE");

		    	$objIPTC_after = new IPTC($target_file_after);
		    	$objIPTC_after->setValue(IPTC_COPYRIGHT_STRING, "A copyright notice");
		    	$objIPTC_after->setValue(IPTC_CAPTION, "A caption descriptions for this picture [AFTER]."); 
		    	$objIPTC_after->setValue(IPTC_FIXTURE_IDENTIFIER, "fixture identifier");
		    	$objIPTC_after->setValue(IPTC_CREDIT, "IPTC_CREDIT");
		    	$objIPTC_after->setValue(IPTC_ORIGINATING_PROGRAM, "originating apps");
		    	$objIPTC_after->setValue(IPTC_SOURCE, "IPTC_SOURCE");

		        echo "The file ". basename( $file_name_before ) . " has been uploaded. <br/>";
		        echo "<img src='" . $target_file_before . "' /><br/>";

		        echo "The file ". basename( $file_name_after ). " has been uploaded. <br/>";
		        echo "<img src='" . $target_file_after . "' /><br/>";

		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
	}