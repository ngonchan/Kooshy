<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
	<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
	<div>
		<?php echo KMS::Session()->path('ua.message.text') ?>
	</div>
</div>
<?php } ?>



<div class="content-box column-left">
	<div class="content-box-header"><h3>Site Information</h3></div>
	<div class="content-box-content">
		<div class="tab-content default-tab">
			<p class="pb0">
				<strong>Domain: </strong><?php echo $site->domain ?><br />
				<strong>Created: </strong><?php echo date('M j, Y', $site->created) ?><br />
				<strong>Description: </strong><?php echo $site->description ?>
			</p>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->

<div class="content-box column-right">
	<div class="content-box-header">
		<a href="http://cognitived.com/" class="button align-right">More News</a>
		<h3>Latest (KMS) News</h3>
	</div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<tfoot><tr><td></td></tr></tfoot>
				<tbody>
					<tr><td><a href="#">Coming Soon</a></td></tr>
					<tr><td><a href="#">Coming Soon</a></td></tr>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<div class="clear"></div>


<div class="content-box">
	<div class="content-box-header"><h3>Latest User Activity</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Action</th>
						<th>Details</th>
						<th>Date</th>
					</tr>
				</thead>

				<tfoot>
					<tr><td colspan="3"></td></tr>
				</tfoot>

				<tbody>
					<?php foreach ($activity as $row) { ?>
					<tr>
						<td><?php echo ucwords(inflector::humanize($row->action->name)) ?></td>
						<td><?php echo $row->details ?></td>
						<td><?php echo date('M j, Y \@ g:i a', $row->created) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<div class="clear"></div>