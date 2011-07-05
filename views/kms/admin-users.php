<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Manage Users</h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">
			<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
			<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					<?php echo KMS::Session()->path('ua.message.text') ?>
				</div>
			</div>
			<?php } ?>

			<table class="data sort">

				<thead>
					<tr>
						<th><input class="check-all" type="checkbox" /></th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Role</th>
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5">
							<?php /*
							<div class="bulk-actions align-left">
								<select name="dropdown" id="group-action">
									<option value="">Choose an action...</option>
									<option value="enable">Enable</option>
									<option value="disable">Disable</option>
								</select>
								<a class="button" href="">Apply to selected</a>
							</div>
							<div class="clear"></div>
							*/ ?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($users as $item) { ?>
					<tr>
						<td><input type="checkbox" /></td>
						<td><?php echo $item->first_name ?></td>
						<td><?php echo $item->last_name ?></td>
						<td><?php echo $item->username ?></td>
						<td><?php echo $item->email ?></td>
						<td><?php echo $item->role->find()->name ?></td>
						<td>
							<?php
							if ($item->id != KMS::Session()->get('user')->id) {
								if ( KMS::instance('privilege')->has('user_edit') ) {
									echo html::anchor(
										Route::url('kms-admin', array('action'=>'admin', 'section'=>'user-edit', 'id'=>$item->id)),
										html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/pencil.png')), '/'), array('alt'=>'Edit User'))
									);
								} ?>
								&nbsp;
								<?php
								if ( KMS::instance('privilege')->has('user_delete') ) {
									echo html::anchor(
										Route::url('kms-admin', array('action'=>'admin', 'section'=>'user-delete', 'id'=>$item->id)),
										html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/cross.png')), '/'), array('alt'=>'Remove User'))
									);
								}
							}
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->