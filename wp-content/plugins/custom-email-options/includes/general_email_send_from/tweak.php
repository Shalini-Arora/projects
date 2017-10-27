<?php
// change from email name class
if ( ! class_exists('Wb_General_Email_Send_From_Tweak')) {
	class Wb_general_email_send_from_tweak {
		//generate email from name setting form field
		public function wbSettings( ) {
			?>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Send Email using SMTP', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <label ><input name="wb_general_tweak[<?php echo $this->option?>]" type="radio" value="yes" <?php echo ($this->value=="yes") ? ' checked="checked"' : ''; ?>> Yes</label>
				   <label ><input name="wb_general_tweak[<?php echo $this->option?>]" type="radio" value="" <?php echo ($this->value=="") ? ' checked="checked"' : ''; ?>> No</label>
					<br />
					<?php echo __('If you checked this option the smtp section will be added to send email through smtp', WB_CHANGE_EMAIL_SLUG); ?>
				</td>
			</tr>
			<?php
		}
	// function to apply filter to change the email from name
		public function wbTweak() {
			// function with no action
		}
	}
}
