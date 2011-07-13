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
			'action' => 'sites', 'section' => 'delete', 'id' => $site->id
		)), 'Delete Site', array('class'=>'button red align-right')) ?>
		<?php echo html::anchor(Route::url('kms-superadmin', array(
			'action' => 'sites', 'section' => 'edit', 'id' => $site->id
		)), 'Edit Site', array('class'=>'button align-right')) ?>
		<h3>Site Information</h3>
	</div>
	<div class="content-box-content">
		<div class="tab-content default-tab">
			<p class="pb0">
				<strong>Domain: </strong><?php echo $site->domain ?><br />
				<strong>Created: </strong><?php echo date('M j, Y', $site->created) ?><br />
				<strong>Description: </strong><?php echo $site->description ?><br />
				<strong>Kooshy Version: </strong>v<?php echo KMS_VERSION ?><br />
				Active Template <strong><?php echo $template ?></strong>
			</p>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->

	<div class="content-box-header nested"><h3>Content</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">

				<tbody>
					<tr>
						<td><?php echo $counts->content ?>&nbsp;&nbsp;Content Pages</td>
						<td><?php echo $counts->variables ?>&nbsp;&nbsp;Site Variables</td>
					</tr>
					<tr>
						<td><?php echo $counts->chunks ?>&nbsp;&nbsp;Available Chunks</td>
						<td><?php echo $counts->snippets ?>&nbsp;&nbsp;Available Snippets</td>
					</tr>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->

	<div class="content-box-header nested">
		<?php echo html::anchor(Route::url('kms-superadmin', array(
			'action' => 'route', 'section' => 'add', 'id' => $site->id
		)), 'Add Route', array('class'=>'button align-right')) ?>
		<h3>Routes</h3>
	</div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Name</th>
						<th>Route</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tfoot><tr><td colspan="3"></td></tr></tfoot>

				<tbody>
					<?php foreach ($site->routes->find_all() as $route) { ?>
					<tr>
						<td>
							<?php echo html::anchor(Route::url('kms-superadmin', array(
								'action' => 'route', 'section' => 'edit', 'id' => $site->id, 'subid' => $route->id
							)), $route->name) ?>
						</td>
						<td><?php echo htmlentities($route->route) ?></td>
						<td>
							<?php echo html::anchor(Route::url('kms-superadmin', array(
								'action' => 'route', 'section' => 'edit', 'id' => $site->id, 'subid' => $route->id
								)), html::image(trim(Route::url('kms-asset', array('type'=>'images', 'file'=>'icons/pencil.png')), '/'), array('alt'=>'Edit'))
							) ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->

<div class="content-box column-right">
	<div class="content-box-header"><h3>Variables</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Name</th>
						<th>Value</th>
					</tr>
				</thead>
				<tfoot><tr><td></td></tr></tfoot>

				<tbody>
					<?php foreach ($site->variables->find_all() as $variable) { ?>
					<tr>
						<td><?php echo $variable->name ?></td>
						<td><?php echo htmlentities($variable->value) ?></td>
					</tr>
					<?php } ?>
				</tbody>

			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->
<div class="clear"></div>


<div class="content-box column-left">
	<div class="content-box-header"><h3>Add/Remove Chunks</h3></div>
	<div class="content-box-content<?php if ($counts->chunks > 0) echo ' flush' ?>">
		<div class="tab-content default-tab">
			<?php if ($counts->chunks > 0) { ?>
			<table class="data">
				<thead>
					<tr>
						<th>Code / Description</th>
						<th>Available</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="2"><input class="button align-right" type="submit" value="Update" /></td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($chunks as $chunk) { ?>
					<tr>
						<td>
							<strong><?php echo $chunk->code ?></strong> :: <?php echo nl2br($chunk->description) ?>
						</td>
						<td class="nowrap">
							<?php
							echo form::label($chunk->code . '-yes', 'Yes', array('class'=>'inline'));
							echo form::radio($chunk->code, 1, $site->snippets->find($chunk->id)->loaded(), array('id'=>"{$chunk->code}-yes"));
							echo form::label($chunk->code . '-no', 'No', array('class'=>'inline'));
							echo form::radio($chunk->code, 0, !$site->snippets->find($chunk->id)->loaded(), array('id'=>"{$chunk->code}-no"));
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<p>
				It appears that you have not created any chunks for your Kooshy environment. You can create chunks in the
				chunks section of the Kooshy Admin.
			</p>
			<?php } ?>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->


<div class="content-box column-right">
	<?php echo form::open(Route::url('kms-action', array('action'=>'snippets_update'))) ?>
	<?php echo form::hidden('eval', FALSE) ?>
	<div class="content-box-header"><h3>Add/Remove Snippets</h3></div>
	<div class="content-box-content<?php if ($counts->snippets > 0) echo ' flush' ?>">
		<div class="tab-content default-tab">
			<?php if ($counts->snippets > 0) { ?>
			<table class="data">
				<thead>
					<tr>
						<th>Code / Description</th>
						<th>Available</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="2"><input class="button align-right" type="submit" value="Update" /></td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach ($snippets as $snippet) { ?>
					<tr>
						<td>
							<strong><?php echo $snippet->code ?></strong> :: <?php echo nl2br($snippet->description) ?>
						</td>
						<td class="nowrap">
							<?php
							echo form::label($snippet->code . '-yes', 'Yes', array('class'=>'inline'));
							echo form::radio($snippet->code, 1, $site->snippets->find($snippet->id)->loaded(), array('id'=>"{$snippet->code}-yes"));
							echo form::label($snippet->code . '-no', 'No', array('class'=>'inline'));
							echo form::radio($snippet->code, 0, !$site->snippets->find($snippet->id)->loaded(), array('id'=>"{$snippet->code}-no"));
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<p>
				It appears that you have not created any snippets for your Kooshy environment. You can create snippets in the
				snippets section of the Kooshy Admin.
			</p>
			<?php } ?>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
	<?php echo form::close() ?>
</div> <!-- End .content-box -->
<div class="clear"></div>


<div class="content-box">
	<div class="content-box-header"><h3>Latest Site Activity</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
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
						<td><?php echo $row->user->first_name ?> <?php echo $row->user->last_name ?></td>
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