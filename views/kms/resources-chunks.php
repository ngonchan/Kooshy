<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Site Chunks</h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">
			<div class="notification information png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					Chunks are static blocks of HTML code. This allows you to add alternative static content to areas of your site.
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
						<th>Code ID</th>
						<th>Description</th>
						<th>Enabled</th>
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5">
							<div class="bulk-actions align-left">
								<select name="dropdown" id="group-action">
									<option value="">Choose an action...</option>
									<option value="enable">Enable</option>
									<option value="disable">Disable</option>
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
						<td><?php echo html::anchor(Route::url('kms-admin', array('action'=>'resources', 'section'=>'view', 'id'=>$item->id)), $item->code, array('title'=>'View details for ' . $item->code)) ?></td>
						<td><?php echo Text::limit_chars( strip_tags($item->description), '80', '...') ?></td>
						<td><?php echo $item->enabled ? 'Yes' : 'No' ?></td>
						<td>
							<?php
							echo html::anchor(
								Route::url('kms-action', array('action'=>($item->enabled ? 'resource_disable' : 'resource_enable'), 'id'=>$item->id)),
								($item->enabled ? 'Disable' : 'Enable'), array('alt'=>'Delete')
							) ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->