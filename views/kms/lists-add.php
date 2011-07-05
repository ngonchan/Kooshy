<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Creating a New List</h3>
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

			<?php echo form::open(Route::url('kms-action', array('action'=>'list_add'))) ?>
				<fieldset>
					<p>
						<label>List Name</label>
						<input class="text-input large-input" type="text" name="name" value="<?php echo arr::get($site_list->as_array(), 'name') ?>" />
					</p>
					<p>
						<label>Columns</label>
					</p>
				</fieldset>
			<?php
			foreach (KMS::Session()->path('ua.fields.column_name', array('')) as $key => $value) {
				$column = array(
					'name' => $value,
					'type' => KMS::Session()->path('ua.fields.column_name.' . $key)
				);
				?>
				<fieldset class="column-left">
					<p>
						<label>Name</label>
						<input class="text-input medium-input" type="text" name="column_name[]" value="<?php echo arr::get($column, 'name') ?>" />
					</p>
				</fieldset>
				<fieldset class="column-right">
					<p>
						<label>Type</label>
						<?php echo form::select('column_type[]', $column_types, arr::get($column, 'type'), array('class'=>'medium-input')) ?>
					</p>
				</fieldset>
				<div class="clear"></div>
			<?php	} ?>
				<fieldset>
					<p>
						<a href="#" id="add_list_column" class="button">Add Column</a>
						<input class="button" type="submit" value="Create List" />
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->