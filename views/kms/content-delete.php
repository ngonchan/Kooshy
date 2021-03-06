<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Deleting Content: <?php echo $content['id'] ?></h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">

			<div class="notification attention png_bg">
				<div>
					You are about to delete "<strong><?php echo arr::get($content, 'title') ?></strong>" and this action cannot be undone! Are you sure you want to continue?
				</div>
			</div>

			<?php echo form::open(Route::url('kms-action', array('action'=>'content_delete'))) ?>
			<?php echo form::hidden('id', $content['id']) ?>
				<fieldset>
					<p>
						<input class="button" type="submit" value="Yes, Delete It!" />
						<?php echo html::anchor(Route::url('kms-admin', array('action'=>'content')), 'No, Go Back!', array('class'=>'button')) ?>
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->