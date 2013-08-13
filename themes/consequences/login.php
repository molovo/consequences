<?php theme_include('header'); ?>

	<!--<section id="login">
		<div class="container">
			<form action="login" class="grid 4of12 offset-4">
				<label for="edEmail">Email</label>
				<input type="email" name="edEmail" />

				<label for="edPassword">Password</label>
				<input type="password" name="edPassword" />

				<fieldset class="grid 1of2 remove-padding">
					<input type="submit" value="Login">
				</fieldset>
			</form>

			<div class="grid 1of1"></div>
		</div>
	</section>-->

	<section id="login">
		<div class="container">
			<form method="post" action="<?php echo Uri::to('login'); ?>" class="grid 4of12 offset-4">

				<?php echo $messages; ?>
				<?php $user = filter_var(Input::previous('email'), FILTER_SANITIZE_STRING); ?>

				<input name="token" type="hidden" value="<?php echo $token; ?>">

				<fieldset>
					<label for="email"><?php echo __('users.email'); ?>:</label>
					<?php echo Form::email('email', $user, array(
						'id' => 'email',
						'autocapitalize' => 'off',
						'autofocus' => 'true',
						'placeholder' => __('users.email')
					)); ?>

					<label for="pass"><?php echo __('users.password'); ?>:</label>
					<?php echo Form::password('pass', array(
						'id' => 'pass',
						'placeholder' => __('users.password')
					)); ?>

					<input type="submit" value="<?php echo __('global.login'); ?>" />
				</fieldset>
			</form>
		</div>
	</section>
