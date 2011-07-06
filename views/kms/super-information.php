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
	<div class="content-box-header">
		<?php echo html::anchor(Route::url('kms-superadmin', array(
			'action' => 'sites', 'section' => 'add'
		)), 'Create Site', array('class'=>'button align-right')) ?>
		<h3>Site(s)</h3>
	</div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Description</th>
						<th>Domain</th>
						<th>Created</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($sites as $site) { ?>
					<tr>
						<td>
							<?php echo html::anchor(Route::url('kms-superadmin', array(
								'action' => 'sites', 'section' => 'edit', 'id' => $site->id
							)), $site->description) ?>
						</td>
						<td><?php echo $site->domain ?></td>
						<td class="nowrap"><?php echo date('F j, \'y - g:i a', $site->created) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->

	<div class="content-box-header nested"><h3>Kooshy Resources</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">

				<tfoot>
					<tr>
						<td colspan="4">
							<strong>
								You are using Kooshy v<?php echo KMS_VERSION ?><br />
								Kooshy was installed on <?php echo date('F j, Y \@ g:i a', $installed) ?>
							</strong>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<tr>
						<?php $i = 0; foreach ($counts as $tag => $count) { ?>
						<td>
							<?php echo html::anchor(Route::url('kms-superadmin', array(
								'action' => $tag
							)), ucwords($tag)) ?>
						</td>
						<td><?php echo $count ?></td>
						<?php if (++$i == 2) { ?></tr><tr><?php $i = 0; } ?>
					<?php } ?>
					</tr>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->

<div class="content-box column-right">
	<div class="content-box-header"><h3>Super Admins</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Name</th>
						<th>Last Activity</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($supers as $user) { ?>
					<tr>
						<td><?php echo $user->first_name ?> <?php echo $user->last_name ?></td>
						<td><?php echo date('F j, Y - g:i a', $user->actions->order_by('created', 'desc')->find()->created) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->

	<div class="content-box-header nested">
		<a href="http://cognitived.com/topics/blog/kooshy-cms/" class="button align-right">More News</a>
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
	<div class="content-box-header">
		<?php echo html::anchor(Route::url('kms-superadmin', array(
			'action' => 'activity'
		)), 'View All Activity', array('class'=>'button align-right')) ?>
		<h3>Latest Site Activity</h3>
	</div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
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
					<tr><td colspan="4"></td></tr>
				</tfoot>

				<tbody>
					<?php foreach ($activity as $row) { ?>
					<tr>
						<td><?php echo $row->site->description ?></td>
						<td class="nowrap"><?php echo $row->user->first_name ?> <?php echo $row->user->last_name ?></td>
						<td class="nowrap"><?php echo ucwords(inflector::humanize($row->action->name)) ?></td>
						<td><?php echo $row->details ?></td>
						<td class="nowrap"><?php echo date('M j, Y \@ g:i a', $row->created) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<div class="clear"></div>