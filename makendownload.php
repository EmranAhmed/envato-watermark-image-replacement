<?php


	$image_zip_name = trim( $_GET[ 'name' ] );
	$make_zip_name  = 'process/' . trim( $_GET[ 'id' ] ) . '.zip';

	if ( ! file_exists( $make_zip_name ) ) {
		die;
	}


	header( "Pragma: public" );
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header( "Cache-Control: private", FALSE );
	header( 'Content-type: application/zip' );
	header( 'Content-Disposition: attachment; filename="replaced_' . $image_zip_name . '.zip"' );
	readfile( $make_zip_name );
	// remove zip file from temp path
	unlink( $make_zip_name );
