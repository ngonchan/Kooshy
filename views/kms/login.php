<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Login | Kooshy (KMS)</title>
		<?php
		echo html::style('kms-asset/css/reset.css', array('media' => 'screen')) . "\n\t\t";
		echo html::style('kms-asset/css/style.css', array('media' => 'screen')) . "\n\t\t"; // DEFAULT COLOR IS GREEN
		echo html::style('kms-asset/css/blue.css', array('media' => 'screen')) . "\n\t\t"; // UNCOMMENT FOR BLUE
		//echo html::style('kms-asset/css/red.css', array('media' => 'screen')) . "\n\t\t"; // UNCOMMENT FOR RED
		echo '<!--[if lte IE 7]>' . html::style('kms-asset/css/ie.css', array('media'=>'screen')) . '<![endif]-->' . "\n\t\t";
		echo html::script('kms-asset/scripts/jquery-1.3.2.min.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/tinymce/jquery.tinymce.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/simpla.jquery.configuration.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/facebox.js') . "\n\t\t";
		echo html::script('kms-asset/scripts/jquery.wysiwyg.js') . "\n\t\t";
		echo '<!--[if IE 6]>' . html::script('kms-asset/scripts/DD_belatedPNG_0.0.7a.js') . '<![endif]-->' . "\n\t\t";
		echo '<!--[if IE 6]><script type="text/javascript">DD_belatedPNG.fix(\'.png_bg, img, li\');</script><![endif]-->' . "\n";
		?>
	</head>

	<body id="login">

		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
				<h1>Kooshy (KMS)</h1>
			</div> <!-- End #logn-top -->

			<div id="login-content">
				<div class="notification <?php echo KMS::Session()->path('ua.message.class', 'information') ?> png_bg">
					<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
					<div>
						<?php echo KMS::Session()->path('ua.message.text', 'Please login by using the form below.') ?>
					</div>
				</div>
				<?php echo form::open(Route::url('kms-action', array('action'=>'login'))) ?>
					<p>
						<?php echo form::label('ff_username', 'Username') ?>
						<?php echo form::input('username', arr::path($ua, 'fields.username'), array('id'=>'ff_username', 'class'=>'text-input')) ?>
					</p>
					<div class="clear"></div>
					<p>
						<?php echo form::label('ff_password', 'Password') ?>
						<?php echo form::password('password', NULL, array('id'=>'ff_password', 'class'=>'text-input')) ?>
					</p>
					<div class="clear"></div>
					<!--
					<p id="remember-password">
						<?php echo form::checkbox('remember', 1, (bool) arr::path($ua, 'fields.remember', 1), array('id'=>'ff_remember')) ?>
						<?php echo form::label('ff_remember', 'Remember me') ?>
					</p>
					<div class="clear"></div>
					-->
					<p>
						<?php echo form::submit('login', 'Sign In', array('class'=>'button')) ?>
					</p>
				<?php echo form::close() ?>
			</div> <!-- End #login-content -->

		</div> <!-- End #login-wrapper -->
  </body>
</html>
