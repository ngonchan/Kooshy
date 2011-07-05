<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="content-box"><!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Editing Profile</h3>
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

			<?php echo form::open(Route::url('kms-action', array('action'=>'profile_edit'))) ?>
				<fieldset>
					<p>
						<label>Username</label>
						<input class="text-input large-input" type="text" id="large-input" name="username" value="<?php echo arr::get($profile, 'username') ?>" />
					</p>
					<p>
						<label>First Name</label>
						<input class="text-input large-input" type="text" id="large-input" name="first_name" value="<?php echo arr::get($profile, 'first_name') ?>" />
					</p>
					<p>
						<label>Last Name</label>
						<input class="text-input large-input" type="text" id="large-input" name="last_name" value="<?php echo arr::get($profile, 'last_name') ?>" />
					</p>
					<p>
						<label>Email Address</label>
						<input class="text-input large-input" type="text" id="large-input" name="email" value="<?php echo arr::get($profile, 'email') ?>" />
					</p>
					<p>
						<label>Change Password</label>
						<input class="text-input large-input" type="password" id="large-input" name="password" value="<?php echo arr::get($profile, 'password') ?>" />
					</p>
					<p>
						<label>Password Confirm</label>
						<input class="text-input large-input" type="password" id="large-input" name="password_confirm" value="<?php echo arr::get($profile, 'password_confirm') ?>" />
					</p>

					<p>
						<input class="button" type="submit" value="Submit" />
						<?php echo html::anchor(Route::url('kms-admin', array('action'=>'profile')), 'Cancel', array('class'=>'button')) ?>
					</p>
				</fieldset>
				<div class="clear"></div><!-- End .clear -->
			</form>

		</div><!-- End .tab-content -->

	</div><!-- End .content-box-content -->

</div><!-- End .content-box -->