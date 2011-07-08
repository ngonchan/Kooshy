<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
	<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
	<div>
		<?php echo KMS::Session()->path('ua.message.text') ?>
	</div>
</div>
<?php } ?>

<div class="content-box">
	<div class="content-box-header">
		<?php echo html::anchor(Route::url('kms-superadmin', array(
			'action' => 'activity'
		)), 'View All Activity', array('class'=>'button align-right')) ?>
		<h3>Latest Activity in Kooshy</h3>
	</div>
	<div class="content-box-content">
		<div class="tab-content default-tab">
			<table class="data sort_long">
				<thead>
					<tr>
						<th>Site</th>
						<th>User</th>
						<th>Action</th>
						<th>Details</th>
						<th>Date</th>
					</tr>
				</thead>

				<tfoot>
					<tr><td colspan="5"><div class="pagination"></div></td></tr>
				</tfoot>

				<tbody>
					<?php foreach ($activity as $row) { ?>
					<tr>
						<td><?php echo $row->site->description ?></td>
						<td class="nowrap"><?php echo $row->user->first_name ?> <?php echo $row->user->last_name ?></td>
						<td class="nowrap"><?php echo ucwords(inflector::humanize($row->action->name)) ?></td>
						<td><?php echo $row->details ?></td>
						<td class="nowrap" sortdata="<?php echo $row->created ?>"><?php echo date('M j, Y \@ g:i a', $row->created) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<div class="clear"></div>