<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Creating Item in the <?php echo $site_list->name ?> List</h3>
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

			<?php echo form::open(Route::url('kms-action', array('action'=>'list_view_add'))) ?>
			<?php echo form::hidden('site_list', $site_list->id) ?>
				<fieldset>
					<?php foreach ($columns as $id => $col) { if ($id == 'id') continue; ?>
					<p>
						<label><?php echo $col['column_name'] ?></label>
						<?php echo KMS::input($col, $list->$id); ?>
					</p>
					<?php } ?>
					<p>
						<input class="button" type="submit" value="Add" />
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->