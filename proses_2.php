<?php
	/**
	 * filename: proces_2.php
	 * Copyright (C) 2014 Agung Andika
	 *
	 * Process input from compressed jpeg which taken by camera mobile phones (currently Android JB 4.1.x and up)
	 * Image from camera resized using library "jQuery canvasResize Plugin" by goker.cebeci. Licensed under MIT.
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
 
	$imageFileType_before = pathinfo($target_file_before, PATHINFO_EXTENSION);
	$imageFileType_after = pathinfo($target_file_after, PATHINFO_EXTENSION);
 	
 	$imageData_before = base64_decode( ltrim ($_POST['beforeVolume_base64'], 'data:image/jpeg;base64') );
 	$imageData_after = base64_decode( ltrim ($_POST['afterVolume_base64'], 'data:image/jpeg;base64') );
 
 
	// Check post is valid
	if(isset($_POST))
	{
		// check existance upload target directory
		if( !is_dir($target_dir) ) @mkdir($target_dir);
		
		// process image from base64
 		$source_before = imagecreatefromstring($imageData_before);
		$source_after = imagecreatefromstring($imageData_after);

		$rotate_before = imagerotate($source_before, 0, 0);
		$imageSave_before = imagejpeg($rotate_before, $target_file_before, 100); 

		// write final EXIF TAG - BEFORE
		$objIPTC_before = new IPTC($target_file_before);
		$objIPTC_before->setValue(IPTC_COPYRIGHT_STRING, "A copyright notice");
		$objIPTC_before->setValue(IPTC_CAPTION, "A caption descriptions for this picture [BEFORE]."); 
		$objIPTC_before->setValue(IPTC_FIXTURE_IDENTIFIER, "fixture identifier");
		$objIPTC_before->setValue(IPTC_CREDIT, "IPTC_CREDIT");
		$objIPTC_before->setValue(IPTC_ORIGINATING_PROGRAM, "originating apps");
		$objIPTC_before->setValue(IPTC_SOURCE, "IPTC_SOURCE");
		// destroy source before pic
		imagedestroy($source_before);
		echo "<img src='" . $target_file_before . "' /><br/>";

		// process image from base64
		$rotate_after = imagerotate($source_after, 0, 0);
		$imageSave_after = imagejpeg($rotate_after, $target_file_after, 100); 
		// write final EXIF TAG - AFTER
		$objIPTC_after = new IPTC($target_file_after);
		$objIPTC_after->setValue(IPTC_COPYRIGHT_STRING, "A copyright notice");
		$objIPTC_after->setValue(IPTC_CAPTION, "A caption descriptions for this picture [AFTER]."); 
		$objIPTC_after->setValue(IPTC_FIXTURE_IDENTIFIER, "fixture identifier");
		$objIPTC_after->setValue(IPTC_CREDIT, "IPTC_CREDIT");
		$objIPTC_after->setValue(IPTC_ORIGINATING_PROGRAM, "originating apps");
		$objIPTC_after->setValue(IPTC_SOURCE, "IPTC_SOURCE");
		// destroy source before pic
		imagedestroy($source_after);
		echo "<img src='" . $target_file_after . "' /><br/>";
	} 