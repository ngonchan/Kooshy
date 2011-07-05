<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<?php echo html::anchor(Route::url('kms-admin', array('action'=>'lists', 'section'=>'list-insert', 'id'=>$site_list->id)), 'Insert a Row', array('title'=>'Insert a Row', 'class'=>'button')) ?>
		<h3><?php echo $site_list->name ?> List</h3>
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

			<table class="data">

				<thead>
					<tr>
						<th><input class="check-all" type="checkbox" /></th>
						<?php foreach ($columns as $column => $info) { ?>
						<th><?php echo $column ?></th>
						<?php } ?>
						<th>actions</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5">
							<div class="bulk-actions align-left">
								<select name="dropdown" id="group-action">
									<option value="option1">Choose an action...</option>
									<option value="option3">Delete</option>
								</select>
								<a class="button" href="">Apply to selected</a>
							</div>
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($list as $item) { ?>
					<tr>
						<td><input type="checkbox" /></td>
						<?php foreach ($item->as_array() as $value) { ?>
						<td><?php echo text::limit_chars($value, 50, '...') ?></td>
						<?php } ?>
						<td>
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'lists', 'section'=>'list-edit', 'id'=>$site_list->id, 'subid'=>$item->id)),
								html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/pencil.png')), '/'), array('alt'=>'Edit Row'))
							) ?>
							&nbsp;
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'lists', 'section'=>'list-remove', 'id'=>$site_list->id, 'subid'=>$item->id)),
								html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/cross.png')), '/'), array('alt'=>'Remove Row'))
							) ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->