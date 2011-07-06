<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Deleting User: <?php echo $user['id'] ?></h3>
		<div class="clear"></div>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<div class="tab-content default-tab" id="tab1">

			<div class="notification attention png_bg">
				<div>
					You are about to delete the user "<strong><?php echo arr::get($user, 'first_name') ?> <?php echo arr::get($user, 'last_name') ?></strong>" and this action cannot be undone! Are you sure you want to continue?
				</div>
			</div>

			<?php if ($sites->count() > 1) { ?>
			<div class="notification information png_bg">
				<div>
					The user "<strong><?php echo arr::get($user, 'first_name') ?> <?php echo arr::get($user, 'last_name') ?></strong>" belongs to the following sites. The user will only be deleted from this site.<br /><br />
					<ul>
						<?php foreach ($sites as $site) { ?>
						<li><?php echo $site->site->description ?> (<?php echo $site->site->domain ?>)</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php } ?>

			<?php echo form::open(Route::url('kms-action', array('action'=>'user_delete'))) ?>
			<?php echo form::hidden('id', $user['id']) ?>
				<fieldset>
					<p>
						<input class="button" type="submit" value="Yes, Delete It!" />
						<?php echo html::anchor(Route::url('kms-admin', array('action'=>'admin', 'section'=>'users')), 'No, Go Back!', array('class'=>'button')) ?>
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->