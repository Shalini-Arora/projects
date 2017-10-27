<?php

if ( ! class_exists('Wb_Advance_Email_Option_Tweak')) {
	class Wb_advance_email_option_tweak {
		public function wbSettings( ) {
			?>
			   <tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Sender (Return-Path)', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <input name="<?php echo $this->option; ?>[wb_mail_sender]" type="text" value="<?php echo $this->value['wb_mail_sender']; ?>">
					<br />
					<?php echo __("Sets the Sender email (Return-Path) of the message.  If not empty, will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.", WB_CHANGE_EMAIL_SLUG); ?>
				</td>
              </tr>
			  <tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Confirm Reading To', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <input name="<?php echo $this->option; ?>[wb_mail_confirm_reading_to]" type="text" value="<?php echo $this->value['wb_mail_confirm_reading_to']; ?>">
					<br />
					<?php echo __('Sets the email address that a reading confirmation will be sent.', WB_CHANGE_EMAIL_SLUG); ?>
				</td>
              </tr>
			<?php
		}

		public function isStrAndNotEmpty($var) {
			if ( ! is_string($var))
				return false;

			if (empty($var))
				return false;

			if ($var=='')
				return false;

			return true;
		}

		public function wbTweak() {

			add_action('phpmailer_init', array($this, 'wpMailPhpmailer'));
		}

		public function wpMailPhpmailer(&$mailer) {

			$phpmailer = &$mailer;
			$wp_mail_options = $this->value;

			if ($this->isStrAndNotEmpty($wp_mail_options['wb_mail_sender']))
			$phpmailer->Sender = $wp_mail_options['wb_mail_sender'];

			if ($this->isStrAndNotEmpty($wp_mail_options['wb_mail_confirm_reading_to']))
			$phpmailer->ConfirmReadingTo = $wp_mail_options['wb_mail_confirm_reading_to'];
		}

	}
}
