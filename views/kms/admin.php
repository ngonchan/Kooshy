<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $title ?></title>
		<?php
		echo html::style('kms-asset/css/reset.css', array('media' => 'screen')) . "\n\t\t";
		echo html::style('kms-asset/css/style.css', array('media' => 'screen')) . "\n\t\t"; // Default theme is green
		echo html::style('kms-asset/css/invalid.css', array('media' => 'screen')) . "\n\t\t"; // Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid
		echo html::style('kms-asset/css/blue.css', array('media' => 'screen')) . "\n\t\t"; // Uncomment for blue theme
		//echo html::style('kms-asset/css/red.css', array('media' => 'screen')) . "\n\t\t"; // Uncomment for red theme
		echo '<!--[if lte IE 7]>' . html::style('kms-asset/css/ie.css', array('media' => 'screen')) . "<![endif]-->\n\t\t";
		echo html::script('kms-asset/scripts/jquery-1.3.2.min.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/jquery.dataTables.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/jquery.dataTables.tfootPagination.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/facebox.js') . "\n\t\t";
		//echo html::script('kms-asset/scripts/jquery.wysiwyg.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/tinymce/jquery.tinymce.js') . "\n\t\t";
		//echo html::script('kms-asset/scripts/jquery.datePicker.js') . "\n\t\t";
		//echo html::script('kms-asset/scripts/jquery.date.js') . "\n\t\t";
		echo '<!--[if IE]>' . html::script('kms-asset/scripts/jquery.bgiframe.js') . "<![endif]-->\n\t\t";
		echo '<!--[if IE 6]>' . html::script('kms-asset/scripts/DD_belatedPNG_0.0.7a.js') . "<![endif]-->\n\t\t";
		echo html::script('kms-asset/scripts/simpla.jquery.configuration.js') . "\n\t\t";
		?>
	</head>

	<body>

		<div id="body-wrapper">

			<?php echo $sidebar ?>

			<div id="main-content">

				<noscript> <!-- Show a notification if the user has disabled javascript -->
					<div class="notification error png_bg">
						<div>
							Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a>
							your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a>
							Javascript to navigate the interface properly.
						</div>
					</div>
				</noscript>


        <!-- Page Head -->
        <h2><?php echo $site->description ?></h2>
				<p id="page-intro">&nbsp;</p>

				<?php echo $content ?>

				<div class="push"></div>

			</div> <!-- End #main-content -->

			<div id="footer">
				<small>
					&copy; Copyright <?php echo date('Y', $site->created) ?> <?php echo $site->description ?> | Powered by <a href="http://cognitived.com/">Kooshy (KMS)</a>
				</small>
			</div><!-- End #footer -->

		</div>

	</body>
</html>
