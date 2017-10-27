<?php
// change from email name class
if ( ! class_exists('Wb_General_Email_From_Name_Tweak')) {
	class Wb_general_email_from_name_tweak {

		//generate email from name setting form field
		public function wbSettings( ) {
			?>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Change from email name', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <input name="wb_general_tweak[<?php echo $this->option; ?>]" type="text" value="<?php echo $this->value; ?>">
					<br />
					<?php echo __('You can define any name, name will be used for all sent emails.<br/> Default name is "&#87;ordPress" This address and name will be used for all sended emails.', WB_CHANGE_EMAIL_SLUG); ?>
				</td>
			</tr>
			<?php
		}
	// function to apply filter to change the email from name
		public function wbTweak() {

		}
	}
}
