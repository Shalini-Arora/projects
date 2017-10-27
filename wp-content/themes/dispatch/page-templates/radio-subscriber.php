<?php /* Template Name: Radio Subscriber Form */ 
	get_header(); 
?>
<?php
// Dispay Loop Meta at top
hoot_display_loop_title_content( 'pre', 'page.php' );
get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
hoot_display_loop_title_content( 'post', 'page.php' );

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'page.php' );
?>
<div class="grid main-content-grid">
<div id="content" class="content grid-span-9 no-sidebar layout-none custom-form">
	<div class = "radio-form" >
		<ul class ="description_below">
			<li class= "radio-li"><h2 class="gsection_title"> RADIO SUBSCRIBER FORM </h2> </li>
		</ul><hr>
		<ul class ="description_below">
			<li class= "radio-li"><h2 class="gsection_title">Step:1 &nbsp; Simply Download the file below</h2></li>
		</ul>
		
		<a  href="<?php echo content_url(). '/uploads/2017/10/subscriber-submission-2.xls' ; ?>" target="_blank"  class="dwnld-button" > Download  Submission.xls </a><br/>
		<?php
			$uploads = wp_upload_dir();  
				 $filename =  $uploads['basedir'] . '/subscriber-submission.xls'  ;
				if(file_exists($filename)) { 
					echo "(Last Updated: " . date ("m-d-Y", filemtime($filename)). ")";
				}
		?>
		<ul class ="description_below">
			<li class= "radio-li"><h2>Step:2 &nbsp; Upload Subscriber Submission using the button below</h2></li>
		</ul>
		<?php if(is_user_logged_in() ){  ?>
				<a href = "<?php echo home_url().'/after-subscriber' ?>" class="upld-button">Upload Form</a> </br>
		<?php }else { ?>
				<a href = "http://urtestsite.com/projects/web/shawn/isicsbiotis/wp-login.php?redirect_to=index.php/after-subscriber" class="upld-button">Upload Form</a>
		<?php } ?>
</div>
</div>
</div>
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>