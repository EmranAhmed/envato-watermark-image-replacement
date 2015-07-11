<?php

	//print_r( $_FILES );

	set_time_limit( 0 );

	require_once "dummy_Image_Replacement.php";


	$process_dir    = "process";
	$id             = uniqid();
	$user_dir       = $process_dir . '/' . $id;
	$extract_dir    = $process_dir . '/' . $id . '/extract';
	$gen_dir        = $process_dir . '/' . $id . '/generated';
	$watermark_name = 'watermark-' . $id;

	function getFileExtension( $file_name ) {

		$file_part = pathinfo( strtolower( $file_name ) );

		return $file_part[ 'extension' ];
	}


	function removeDir( $dir ) {
		$files = array_diff( scandir( $dir ), array( '.', '..' ) );
		foreach ( $files as $file ) {
			( is_dir( "$dir/$file" ) && ! is_link( $dir ) ) ? removeDir( "$dir/$file" ) : unlink( "$dir/$file" );
		}

		return rmdir( $dir );
	}


	if ( ! empty( $_FILES[ 'watermark' ] ) and ! empty( $_FILES[ 'imagezip' ] ) ) {

		mkdir( $user_dir );
		mkdir( $extract_dir );
		mkdir( $gen_dir );


		$watermark_image = $_FILES[ 'watermark' ];
		$watermark_ext   = getFileExtension( $_FILES[ 'watermark' ][ 'name' ] );
		$watermark_path  = $user_dir . '/' . $watermark_name . '.' . $watermark_ext;

		$image_zip           = $_FILES[ 'imagezip' ];
		$image_zip_name      = $image_zip[ 'name' ];
		$image_zip_base_name = basename( $image_zip[ 'name' ] );
		$image_zip_ext       = getFileExtension( $image_zip_name );
		$image_zip_path      = $user_dir . '/' . $image_zip_name;


		move_uploaded_file( $_FILES[ 'watermark' ][ 'tmp_name' ], $watermark_path );
		move_uploaded_file( $_FILES[ 'imagezip' ][ 'tmp_name' ], $image_zip_path );

		$zip = new ZipArchive;
		$res = $zip->open( $image_zip_path );
		if ( $res === TRUE ) {
			$zip->extractTo( $extract_dir );
			$zip->close();
		} else {
			die( "Cannot open zip file" );
		}


		$files = glob( "{$extract_dir}/*.{jpg,jpeg,gif,png}", GLOB_BRACE );


		$make_zip      = new ZipArchive;
		$make_zip_name = $user_dir . "." . $image_zip_ext;

		if ( $make_zip->open( $make_zip_name, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) !== TRUE ) {
			die( "* Sorry ZIP creation failed at this time" );
		}


		$replaced = array();
		$total    = count( $files );

		foreach ( $files as $file ) {

			$replace     = new \Dummy_Image_Replacement( $watermark_path, $file );
			$replaced[ ] = $replace->Generate( "{$gen_dir}/" );

			$progress = round( ( count( $replaced ) / $total ) * 100 );

			echo "<script> $('.progress-bar').css('width', '{$progress}%').text('{$progress}%').attr('aria-valuenow', '{$progress}'); </script>";

			ob_flush();
			flush();
		}


		//echo getcwd();

		chdir( $gen_dir );

		// echo getcwd(); die;

		foreach ( $replaced as $file ) {
			$make_zip->addFile( $file );
		}

		chdir( "../../../" );

		// echo getcwd(); die;


		$make_zip->close();


		removeDir( $user_dir );


		echo "<script> window.location.replace('makendownload.php?name={$image_zip_base_name}&id={$id}') </script>";


	}
