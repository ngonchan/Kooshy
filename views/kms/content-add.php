<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Adding Content</h3>
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

			<div class="notification information png_bg">
				<a href="" class="close"><img src="/kms-asset/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					This section contains template variables for the title, meta keywords, meta description, and content area
					sections. The variable names are [[v*title]], [[v*meta_keywords]], [[v*meta_description]], and [[v*content]]
					respectfully.
				</div>
			</div>

			<?php echo form::open(Route::url('kms-action', array('action'=>'content_add'))) ?>
				<fieldset>
					<p>
						<label for="ff_title">Title</label>
						<input class="text-input large-input" type="text" id="ff_title" name="title" value="<?php echo arr::get($content, 'title') ?>" />
					</p>
				</fieldset>

				<fieldset class="column-left">
					<p>
						<label for="ff_uri">URI</label>
						<input class="text-input medium-input" type="text" id="ff_uri" name="uri" value="<?php echo arr::get($content, 'uri') ?>" />
					</p>
				</fieldset>

				<fieldset class="column-right">
					<p>
						<label for="ff_mime_type">Mime Type</label>
						<?php echo form::select('mime_type', KMS::mime_types(FALSE), arr::get($content, 'mime_type', 'text/html'), array('class'=>'medium-input', 'id' => 'ff_mime_type')) ?>
					</p>
				</fieldset>
				<div class="clear"></div>

				<fieldset>
					<p>
						<label for="ff_meta_keywords">Meta Keywords</label>
						<input class="text-input large-input" type="text" id="ff_meta_keywords" name="meta_keywords" value="<?php echo arr::get($content, 'meta_keywords') ?>" />
					</p>
					<p>
						<label for="ff_meta_description">Meta Description</label>
						<input class="text-input large-input" type="text" id="ff_meta_description" name="meta_description" value="<?php echo arr::get($content, 'meta_description') ?>" />
					</p>
					<p>
						<label>Content Area</label>
						<textarea class="text-input textarea tinymce" name="body" cols="79" rows="15"><?php echo arr::get($content, 'body') ?></textarea>
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