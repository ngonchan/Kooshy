<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<?php echo html::anchor(Route::url('kms-admin', array('action'=>'resources', 'section'=>'variables', 'id'=>'new')), 'Create New', array('title'=>'Create a new Site Variable', 'class'=>'button')) ?>
		<h3>Site Variables</h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">
			<div class="notification information png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					Variables are facilitators for storing data. The current value of the variable is the data actually stored in the variable.
				</div>
			</div>
			<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
			<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					<?php echo KMS::Session()->path('ua.message.text') ?>
				</div>
			</div>
			<?php } ?>

			<table class="data">

				<thead>
					<tr>
						<th><input class="check-all" type="checkbox" /></th>
						<th>Variable Name</th>
						<th>Value</th>
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5">
							<div class="bulk-actions align-left">
								<select name="dropdown" id="group-action">
									<option value="">Choose an action...</option>
									<option value="delete">Delete</option>
								</select>
								<a class="button" href="">Apply to selected</a>
							</div>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($resources as $item) { ?>
					<tr>
						<td><input type="checkbox" /></td>
						<td><?php echo html::anchor(Route::url('kms-admin', array('action'=>'resources', 'section'=>'variables', 'id'=>$item->id)), $item->name, array('title'=>"Edit {$item->name}")) ?></td>
						<td><?php echo $item->value ?></td>
						<td>
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'resources', 'section'=>'variables', 'id'=>$item->id)),
								html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/pencil.png')), '/'), array('alt'=>'Edit'))
							) ?>
							&nbsp;
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'resources', 'section'=>'delete-variable', 'id'=>$item->id)),
								html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/cross.png')), '/'), array('alt'=>'Delete'))
							) ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->