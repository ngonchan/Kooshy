<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Site Content</h3>
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
						<th>Title</th>
						<th>URI</th>
						<th>Mime Type</th>
						<th>Actions</th>
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

							<!--
							<div class="pagination">
								<a href="" title="First Page">&laquo; First</a><a href="" title="Previous Page">&laquo; Previous</a>
								<a href="" class="number" title="1">1</a>
								<a href="" class="number" title="2">2</a>
								<a href="" class="number current" title="3">3</a>
								<a href="" class="number" title="4">4</a>
								<a href="" title="Next Page">Next &raquo;</a><a href="" title="Last Page">Last &raquo;</a>
							</div><!-- End .pagination -->
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($content as $item) { ?>
					<tr>
						<td><input type="checkbox" /></td>
						<td><?php echo html::anchor(Route::url('kms-admin', array('action'=>'content', 'section'=>'edit', 'id'=>$item->id)), $item->title, array('title'=>'Edit: ' . $item->title)) ?></td>
						<td><?php echo $item->uri ?></td>
						<td><?php echo $item->mime_type ?></td>
						<td>
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'content', 'section'=>'edit', 'id'=>$item->id)),
								html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/pencil.png')), '/'), array('alt'=>'Edit'))
							) ?>
							&nbsp;
							<?php
							echo html::anchor(
								Route::url('kms-admin', array('action'=>'content', 'section'=>'delete', 'id'=>$item->id)),
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