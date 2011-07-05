<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div id="sidebar">
	<div id="sidebar-wrapper">

		<h1 id="sidebar-title">kooshy</h1>

		<div id="profile-links">
			Welcome <?php echo html::anchor(
				Route::url('kms-admin', array('action'=>'profile')),
				$user->first_name . ' ' . $user->last_name,
				array('title'=>'View Your Profile')
			) ?><br />
		</div><!-- End #profile-links -->

		<ul id="main-nav">
			<?php echo $sidebar->menu() ?>
		</ul><!-- End #main-nav -->

	</div>
</div><!-- End #sidebar -->