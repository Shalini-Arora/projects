<?php
// Display memory used to generate the page class
if ( ! class_exists('Wb_General_Generation_Time_Tweak')) {
	class Wb_general_generation_time_tweak {
		//generate setting form field to select option to show the memory used
		public function wbSettings( ) {
			?>
			<tr valign="top">
				<th scope="row">
					<label for="num_elements">
						<?php echo __('Show generation time', WB_CHANGE_EMAIL_SLUG); ?>:
					</label>
				</th>
				<td>
				   <label ><input name="wb_general_tweak[<?php echo $this->option; ?>]" type="radio" value="yes" <?php echo ($this->value=="yes") ? ' checked="checked"' : ''; ?>> Yes</label>
				   <label ><input name="wb_general_tweak[<?php echo $this->option; ?>]" type="radio" value="" <?php echo ($this->value=="") ? ' checked="checked"' : ''; ?>> No</label>
					<br />
					<?php echo __('Only admins can see this information.<br/> Number of SQL requests, generation time and memory consumption will be shown for all pages.', WB_CHANGE_EMAIL_SLUG); ?>
				</td>
			</tr>
		<?php
		}

		// function to apply action to display time taken and memory used
		public function wbTweak() {
			add_action('init', array($this, 'wbInit'));
		}

		//	SQL requests:62. Generation time:1.248 sec. Memory consumption:45.31 mb
		public function wbInit() {
			add_filter('wp_footer', array($this, 'wbDo'));
		}

		// Function to show the memory and time used
		public function wbDo() {
			printf(__('SQL requests:%d. Generation time:%s sec. Memory consumption:', WB_CHANGE_EMAIL_SLUG), get_num_queries(), timer_stop(0, 3));
			if (function_exists('memory_get_usage'))
			{
				echo round(memory_get_usage()/1024/1024, 2) . ' mb ';
			}
		}
	}
}
