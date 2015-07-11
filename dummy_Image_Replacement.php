<?php

	/**
	 * Dummy_Image_Replacement.php
	 *
	 * Dummy Image Replacement. Replace demo image to dummy image to make themeforest pack.
	 *
	 * @author     Emran Ahmed < emran.bd.08@gmail.com >
	 * @copyright  2014 Emran Ahmed
	 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
	 * @version    1.0.0
	 */
	class Dummy_Image_Replacement {

		private $_water_mark_img_rs;
		private $_real_img_rs;
		private $_mod_img_rs;
		private $_file_name;
		private $_image_width;
		private $_image_height;
		private $_watermark_width;
		private $_watermark_height;
		private $_image_type;
		private $_image_mime;
		private $_blank_image;

		public function __construct( $watermark_image, $input_image ) {

			$this->_water_mark_img_rs = $this->_getImageResource( $watermark_image );
			$this->_real_img_rs       = $this->_getImageResource( $input_image );
			$this->_mod_img_rs        = $this->_getImageResource( $input_image, TRUE );
			$this->_file_name         = basename( $input_image );


		}

		private function _makeFullWatermarked() {

			$img_paste_x = 0;
			while ( $img_paste_x < $this->_image_width ) {
				$img_paste_y = 0;
				while ( $img_paste_y < $this->_image_height ) {
					imagecopy( $this->_mod_img_rs, $this->_water_mark_img_rs, $img_paste_x, $img_paste_y, 0, 0, $this->_watermark_width, $this->_watermark_height );
					$img_paste_y += $this->_watermark_height;
				}
				$img_paste_x += $this->_watermark_width;
			}

		}

		private function _makeTransparentImage() {

			$this->_blank_image = imagecreatetruecolor( $this->_image_width, $this->_image_height );
			imageAlphaBlending( $this->_blank_image, TRUE );
			imageSaveAlpha( $this->_blank_image, TRUE );
			imageFill( $this->_blank_image, 0, 0, imagecolorallocatealpha( $this->_blank_image, 255, 255, 255, 127 ) );


			/**
			 * Loop every Pixel
			 */

			for ( $x = 0; $x < $this->_image_width; $x ++ ) {
				for ( $y = 0; $y < $this->_image_height; $y ++ ) {

					/**
					 * Get Color Index from real image and check the alpha state of the corresponding pixel.
					 */
					$alpha = imagecolorsforindex( $this->_real_img_rs, imagecolorat( $this->_real_img_rs, $x, $y ) );

					/**
					 * If no Alpha state just replace rgb color on fresh image
					 */
					if ( $alpha[ 'alpha' ] != 127 ) {
						$color = imagecolorsforindex( $this->_mod_img_rs, imagecolorat( $this->_mod_img_rs, $x, $y ) );
						imagesetpixel( $this->_blank_image, $x, $y, imagecolorallocatealpha( $this->_blank_image, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $color[ 'alpha' ] ) );
					}
				}
			}

		}

		public function Generate( $save_path = FALSE ) {


			$this->_image_width  = imagesx( $this->_mod_img_rs );
			$this->_image_height = imagesy( $this->_mod_img_rs );

			$this->_watermark_width  = imagesx( $this->_water_mark_img_rs );
			$this->_watermark_height = imagesy( $this->_water_mark_img_rs );

			$this->_makeFullWatermarked();

			$fn = 'image' . $this->_image_type;

			if ( $this->_image_type == 'png' ) {

				$this->_makeTransparentImage();

				if ( $save_path ) {
					$fn( $this->_blank_image, $save_path . $this->_file_name );
				} else {
					header( "Content-type: " . $this->_image_mime );
					$fn( $this->_blank_image );
				}
				imageDestroy( $this->_blank_image );


			} else {

				if ( $save_path ) {
					$fn( $this->_mod_img_rs, $save_path . $this->_file_name );
					imageDestroy( $this->_mod_img_rs );
				} else {
					header( "Content-type: " . $this->_image_mime );
					$fn( $this->_mod_img_rs );
					imageDestroy( $this->_mod_img_rs );
				}
			}

			if ( $save_path ) {
				return $this->_file_name;
			} else {
				return TRUE;
			}


		}

		private function _getImageResource( $image_file, $save = FALSE ) {

			$image_info = getImageSize( $image_file );

			if ( $save ) {
				$this->_image_mime = $image_info[ 'mime' ];
			}

			switch ( $image_info[ 'mime' ] ) {

				case 'image/gif':

					if ( $save ) {
						$this->_image_type = 'gif';
					}

					$img_rs = imageCreateFromGIF( $image_file );
					break;

				case 'image/jpeg':
					if ( $save ) {
						$this->_image_type = 'jpeg';
					}

					$img_rs = imageCreateFromJPEG( $image_file );
					break;

				case 'image/png':
					if ( $save ) {
						$this->_image_type = 'png';
					}

					$img_rs = imageCreateFromPNG( $image_file );

					imageAlphaBlending( $img_rs, TRUE );
					imageSaveAlpha( $img_rs, TRUE );
					break;
			}

			return $img_rs;
		}
	}


	/*$i = new \Dummy_Image_Replacement( 'watermark.jpg', 'bread_&_knife.gif' );
	$i->Generate('save-slider/');*/