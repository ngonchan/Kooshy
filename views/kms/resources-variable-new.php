<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Creating Variable</h3>
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

			<?php echo form::open(Route::url('kms-action', array('action'=>'variable_create'))) ?>
				<fieldset>
					<p>
						<label>Name</label>
						<input class="text-input large-input" type="text" id="large-input" name="name" value="<?php echo arr::get($resource, 'name') ?>" />
					</p>
					<p>
						<label>Value</label>
						<input class="text-input large-input" type="text" id="large-input" name="value" value="<?php echo arr::get($resource, 'value') ?>" />
					</p>

					<p>
						<input class="button" type="submit" value="Submit" />
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->