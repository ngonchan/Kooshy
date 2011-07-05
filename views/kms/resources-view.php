<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Viewing <?php echo $resource['eval'] ? 'Snippet' : 'Chunk' ?> Resource: <?php echo $resource['id'] ?></h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">

			<fieldset>
				<h3>Code: <?php echo arr::get($resource, 'code') ?></h3>
				<p>&nbsp;</p>
				<h3>Description: </h3></p><?php echo arr::get($resource, 'description') ?>
				<p>&nbsp;</p>
				<h3>Body: </h3><?php echo arr::get($resource, 'body') ?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>
					<?php echo html::anchor(
						Route::url('kms-admin', array('action'=>'resources', 'section'=>($resource['eval'] ? 'snippets' : 'chunks'))),
						'Back', array('title'=>'Back', 'class'=>'button')
					) ?>
				</p>
			</fieldset>
			<div class="clear"></div><!-- End .clear -->

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->