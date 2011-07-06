<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<ul class="shortcut-buttons-set">

	<li>
		<?php echo html::anchor(
			Route::url('kms-admin', array('action'=>'content', 'section'=>'overview')),
			'<span><img src="/kms-asset/images/icons/paper_content_pencil_48.png" alt="icon" /><br />View Content</span>',
			array('title' => 'View Content', 'class' => 'shortcut-button')
			) ?>
	</li>

	<li>
		<?php echo html::anchor(
			Route::url('kms-admin', array('action'=>'resources', 'section'=>'variables')),
			'<span><img src="/kms-asset/images/icons/paper_content_pencil_48.png" alt="icon" /><br />View Variables</span>',
			array('title' => 'View Variables', 'class' => 'shortcut-button')
			) ?>
	</li>

	<li>
		<?php echo html::anchor(
			Route::url('kms-admin', array('action'=>'resources', 'section'=>'chunks')),
			'<span><img src="/kms-asset/images/icons/paper_content_pencil_48.png" alt="icon" /><br />View Chunks</span>',
			array('title' => 'View Chunks', 'class' => 'shortcut-button')
			) ?>
	</li>

	<li>
		<?php echo html::anchor(
			Route::url('kms-admin', array('action'=>'resources', 'section'=>'snippets')),
			'<span><img src="/kms-asset/images/icons/paper_content_pencil_48.png" alt="icon" /><br />View Snippets</span>',
			array('title' => 'View Snippets', 'class' => 'shortcut-button')
			) ?>
	</li>

	<li><a class="shortcut-button" href="http://cognitived.com/kms/contact/" target="_blank"><span>
				<img src="/kms-asset/images/icons/comment_48.png" alt="icon" /><br />
				Contact Support
			</span></a></li>

</ul><!-- End .shortcut-buttons-set -->

<div class="clear"></div>

<?php if (KMS::Session()->path('ua.message.text') !== NULL) { ?>
<div class="notification <?php echo KMS::Session()->path('ua.message.class') ?> png_bg">
	<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
	<div>
		<?php echo KMS::Session()->path('ua.message.text') ?>
	</div>
</div>
<?php } ?>



<div class="content-box column-left">
	<div class="content-box-header"><h3>Right Now</h3></div>
	<div class="content-box-content flush">
		<div class="tab-content default-tab">
			<table class="data">
				<thead>
					<tr>
						<th>Content</th>
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="2">
							<?php echo html::anchor(
								Route::url('kms-admin', array('action'=>'templates', 'section'=>'overview')),
								'Change Template',
								array('title' => 'Change Template', 'class' => 'button align-right')
							) ?>
							Active Template <strong><?php echo $template ?></strong><br />
							You are using <strong>Kooshy v<?php echo KMS_VERSION ?></strong>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<tr>
						<td><?php echo $counts->content ?>&nbsp;&nbsp;<?php echo html::anchor(
							Route::url('kms-admin', array('action'=>'content', 'section'=>'overview')),
							'Content Pages', array('title' => 'Content Pages')
							) ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo $counts->variables ?>&nbsp;&nbsp;<?php echo html::anchor(
							Route::url('kms-admin', array('action'=>'resources', 'section'=>'variables')),
							'Site Variables', array('title' => 'Site Variables')
							) ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo $counts->chunks ?>&nbsp;&nbsp;<?php echo html::anchor(
							Route::url('kms-admin', array('action'=>'resources', 'section'=>'chunks')),
							'Active Chunks', array('title' => 'Active Chunks')
							) ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo $counts->snippets ?>&nbsp;&nbsp;<?php echo html::anchor(
							Route::url('kms-admin', array('action'=>'resources', 'section'=>'snippets')),
							'Active Snippets', array('title' => 'Active Snippets')
							) ?></td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div> <!-- End .tab-content -->
	</div> <!-- End .content-box-content -->
</div> <!-- End .content-box -->

<div class="content-box column-right">
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