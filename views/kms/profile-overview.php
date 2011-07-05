<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>User Profile</h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab">
			<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
			<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					<?php echo KMS::Session()->path('ua.message.text') ?>
				</div>
			</div>
			<?php } ?>

			<div class="content-box profile-image" profile-image"><img src="http://www.gravatar.com/avatar/<?php echo md5($profile->email) ?>?size=80" /></div>
			<p>
				<strong><?php echo $profile->first_name ?> <?php echo $profile->last_name ?></strong><br />
				<strong>Username: </strong><?php echo $profile->username ?><br />
				<strong>Email: </strong><?php echo $profile->email ?><br />
				<?php echo html::anchor(Route::url('kms-admin', array('action'=>'profile', 'section'=>'edit')), 'Edit Profile', array('title'=>'Edit Your Profile')) ?>
			</p>
			<div class="clear"></div>

			<h2>Role(s)</h2>
			<table class="data">
				<thead>
					<tr>
						<th>Site</th>
						<th>Domain</th>
						<th>Role</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($roles as $role) { ?>
					<tr>
						<td><?php echo html::anchor("http://{$role->site->domain}/", $role->site->description, array('title' => 'Visit ' . $role->site->description, 'target' => '_blank')) ?></td>
						<td><?php echo html::anchor("http://{$role->site->domain}/", $role->site->domain, array('title' => 'Visit ' . $role->site->description, 'target' => '_blank')) ?></td>
						<td><?php echo $role->role->name . ' (' . $role->role->description ?>)</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<h2>Site Access</h2>
			<table class="data sort">
				<thead>
					<tr>
						<th>Action</th>
						<th>Description</th>
						<th>Type</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="3">
							<div class="pagination">
								<?php /*
								<a href="" title="First Page" class="dt-first">&laquo; First</a>
								<a href="" title="Previous Page" class="dt-prev">&laquo; Previous</a>
								<a href="" class="number" title="1">1</a>
								<a href="" class="number" title="2">2</a>
								<a href="" class="number current" title="3">3</a>
								<a href="" class="number" title="4">4</a>
								<a href="" title="Next Page" class="dt-next">Next &raquo;</a>
								<a href="" title="Last Page" class="dt-last">Last &raquo;</a>
								*/ ?>
							</div> <!-- End .pagination -->
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($site_access as $type => $access) { ?>
					<tr>
						<td><?php echo $access->name ?></td>
						<td><?php echo $access->description ?></td>
						<td><?php echo $access->type ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->