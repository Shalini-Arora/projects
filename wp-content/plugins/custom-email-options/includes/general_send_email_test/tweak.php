<?php
// Display form to send test email main class
if ( ! class_exists('Wb_General_Send_Email_Test_Tweak')) {
	class Wb_general_send_email_test_tweak {
		//generate form field to send email
		public function wbSettings() {
			if ($this->value!=""):?>
				<tr valign="top">
					<th colspan="2"><?php echo $this->value; ?></th>
				</tr>
		   	<?php endif; ?>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('To', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <input type="email" value="" name="mail_to" /></td>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Subject', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <input type="text" value="" name="mail_subject" /></td>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Message', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <textarea name="mail_message"></textarea></td>
				</td>
			</tr>
			<?php
		}

		public function wbTweak() {
			// function with no action
		}
	}
}
