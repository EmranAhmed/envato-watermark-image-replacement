<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Replace Image With Watermark for Envato Products</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Image watermark with png mask for envato authors. Its also support png image masking">
    <meta name="keywords" content="envato, image, dummy image, dummy, png mask, mask, replacement">
    <meta name="author" content="Emran Ahmed - emran.bd.08@gmail.com">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<style>
		body {
			padding-top    : 50px;
			padding-bottom : 20px;
		}
	</style>
	<link rel="stylesheet" href="css/main.css">
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr-2.6.2.min.js"></script>
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-sm-offset-3 col-sm-9">

			<h1>Replace Image With Watermark</h1>

			<p>Support: .jpg, .gif, .png ( with transparent, without transparent )</p>

			<p>
				Example Images:
				<a href="watermark-dark.jpg" target="_blank">WaterMark Image</a>. 
				<a href="css-intro-bg.png" target="_blank">Replaceable Image</a>.  
				<a href="dummy-css-intro-bg.png" target="_blank">Replaced Image</a>. 
			</p>

			<div class="alert alert-warning alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<strong>Warning!</strong> Maximum File Upload Size is <?php echo ini_get( "upload_max_filesize" ) ?>
			</div>

			<?php if ( ! empty( $_FILES[ 'watermark' ] ) and ! empty( $_FILES[ 'imagezip' ] ) ) { ?>

					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-success active"
						     role="progressbar" aria-valuenow="0"
						     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
						</div>
					</div>

				<?php } ?>
		</div> <!-- .col-sm-offset-3 -->

		<div class="col-md-12">
			<form id="process-form" action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="watermark" class="col-sm-3 control-label">Water Mark Image (.jpg)</label>
					<div class="col-sm-9"><input accept="image/jpeg" type="file" class="form-control" id="watermark" name="watermark"></div>
				</div>
				<div class="form-group">
					<label for="imageszip" class="col-sm-3 control-label">Replaceable Images (.zip)</label>
					<div class="col-sm-9"><input accept="application/zip" type="file" name="imagezip" class="form-control" id="imageszip"></div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-primary btn-lg download-btn">Replace and Download
						</button>
					</div>
				</div>
			</form> <!-- #process-form -->
		</div>
	</div> <!-- .row -->
</div><!-- .container -->

<script src="js/bootstrap.min.js"></script>

<?php
	if ( ! empty( $_FILES[ 'watermark' ] ) and ! empty( $_FILES[ 'imagezip' ] ) ) {
		include_once "process.php";
	}
?>
</body>
</html>
