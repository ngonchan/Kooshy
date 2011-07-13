<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Editing Site: <?php echo $site['id'] ?></h3>
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
			<div class="notification attention png_bg">
				<div>
					Notice! Changing the domain name on the site you are currently logged into will result in adverse affects.
				</div>
			</div>

			<?php echo form::open(Route::url('kms-action', array('action'=>'site_edit'))) ?>
			<?php echo form::hidden('id', $site['id']) ?>
				<fieldset>
					<p>
						<label>Domain</label>
						<input class="text-input large-input" type="text" id="large-input" name="domain" value="<?php echo arr::get($site, 'domain') ?>" />
					</p>
					<p>
						<label>Description</label>
						<textarea class="text-input textarea" id="textarea" name="description" cols="79" rows="15"><?php echo arr::get($site, 'description') ?></textarea>
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