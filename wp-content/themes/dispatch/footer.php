		<?php 
		// Dispay Loop Meta at top
			hoot_display_loop_title_content( 'pre', 'page.php' );
			get_template_part( 'template-parts/loop-meta' ); // Loads the template-parts/loop-meta.php template to display Title Area with Meta Info (of the loop)
			hoot_display_loop_title_content( 'post', 'page.php' );

			// Template modification Hook
			do_action( 'hoot_template_before_content_grid', 'page.php' );
		
		// Template modification Hook
		do_action( 'hoot_template_main_wrapper_end' );
		?>
		</div><!-- #main -->

		<?php get_template_part( 'template-parts/footer', 'subfooter' ); // Loads the template-parts/footer-subfooter.php template. ?>

		<?php get_template_part( 'template-parts/footer', 'footer' ); // Loads the template-parts/footer-footer.php template. ?>

		<?php get_template_part( 'template-parts/footer', 'postfooter' ); // Loads the template-parts/footer-postfooter.php template. ?>

	</div><!-- #page-wrapper -->

	<?php wp_footer(); // WordPress hook for loading JavaScript, toolbar, and other things in the footer. ?>

	
	<?php if(get_the_ID() == 24){ ?>
		<script type="text/javascript">
			// 21-6-17 start : for getting regions based on country selection
			var countryRegions = {'AR':'4','AD':'4','AL':'2','AP':'5','AU':'4','BN':'6','BH':'6','BO':'1','BR':'2','BU':'6','BV':'3','BT':'2','CN':'1','CA':'1','CS':'4','CE':'6','CG':'2','CH':'3','CW':'2','CK':'4','CY':'3','CT':'6','CL':'6','CR':'3','DA':'1','DV':'5','DC':'4','DE':'6','DM':'5','DK':'3','DQ':'6','EM':'2','FA':'2','FL':'2','FK':'2','FR':'4','GR':'1','GY':'1','GU':'4','HM':'1','HK':'2','HR':'1','HA':'4','HE':'5','HW':'2','HU':'2','ID':'3','IA':'6','JK':'6','JA':'1','JE':'5','JO':'6','JN':'6','KK':'5','KO':'2','LE':'5','LI':'6','LO':'5','LU':'5','LY':'3','MD':'4','MA':'5','MR':'5','MH':'1','ML':'4','MI':'2','MO':'3','MN':'5','MG':'4','MU':'5','OB':'3','OS':'3','PG':'4','PA':'3','PL':'3','PO':'3','PK':'1','PT':'4','PS':'1','RG':'4','SA':'3','SC':'6','SH':'4','SX':'3','ST':'1','TM':'1','TA':'4','UN':'4','VB':'5','WP':'5','WN':'1','WA':'5','WY':'5','WE':'1','WB':'2','WS':'2','WD':'3','WO':'2','WR':'2'};

			var countryNum = {'AR':'1','AD':'2','AL':'3','AP':'4','AU':'5','BN':'6','BH':'7','BO':'8','BR':'9','BU':'10','BV':'11','BT':'12','CN':'13','CA':'14','CS':'15','CE':'16','CG':'17','CH':'18','CW':'19','CK':'20','CY':'21','CT':'22','CL':'23','CR':'24','DA':'25','DV':'26','DC':'27','DE':'28','DM':'29','DK':'30','DQ':'31','EM':'32','FA':'33','FL':'34','FK':'35','FR':'36','GR':'37','GY':'38','GU':'39','HM':'40','HK':'41','HR':'42','HA':'43','HE':'44','HW':'45','HU':'46','ID':'47','IA':'48','JK':'49','JA':'50','JE':'51','JO':'52','JN':'53','KK':'54','KO':'55','LE':'56','LI':'57','LO':'58','LU':'59','LY':'60','MD':'61','MA':'62','MR':'63','MH':'64','ML':'65','MI':'66','MO':'67','MN':'68','MG':'69','MU':'70','OB':'71','OS':'72','PG':'73','PA':'74','PL':'75','PO':'76','PK':'77','PT':'78','PS':'79','RG':'80','SA':'81','SC':'82','SH':'83','SX':'84','ST':'85','TM':'86','TA':'87','UN':'88','VB':'89','WP':'90','WN':'91','WA':'92','WY':'93','WE':'94','WB':'95','WS':'96','WD':'97','WO':'98','WR':'99'};

			//var countryRegions = {'AR':'AR-1','AD':'AD-2','AL':'AL-3','AP':'AP-4','AU':'AU-5','BN':'BN-6','BH':'BH-7','BO':'BO-8','BR':'BR-9','BU':'BU-10','BV':'BV-11','BT':'BT-12','CN':'CN-13','CA':'CA-14','CS':'CS-15','CE':'CE-16','CG':'CG-17','CH':'CH-18','CW':'CW-19','CK':'CK-20','CY':'CY-21','CT':'CT-22','CL':'CL-23','CR':'CR-24','DA':'DA-25','DV':'DV-26','DC':'DC-27','DE':'DE-28','DM':'DM-29','DK':'DK-30','DQ':'DQ-31','EM':'EM-32','FA':'FA-33','FL':'FL-34','FK':'FK-35','FR':'FR-36','GR':'GR-37','GY':'GY-38','GU':'GU-39','HM':'HM-40','HK':'HK-41','HR':'HR-42','HA':'HA-43','HE':'HE-44','HW':'HW-45','HU':'HU-46','ID':'ID-47','IA':'IA-48','JK':'JK-49','JA':'JA-50','JE':'JE-51','JO':'JO-52','JN':'JN-53','KK':'KK-54','KO':'KO-55','LE':'LE-56','LI':'LI-57','LO':'LO-58','LU':'LU-59','LY':'LY-60','MD':'MD-61','MA':'MA-62','MR':'MR-63','MH':'MH-64','ML':'ML-65','MI':'MI-66','MO':'MO-67','MN':'MN-68','MG':'MG-69','MU':'MU-70','OB':'OB-71','OS':'OS-72','PG':'PG-73','PA':'PA-74','PL':'PL-75','PO':'PO-76','PK':'PK-77','PT':'PT-78','PS':'PS-79','RG':'RG-80','SA':'SA-81','SC':'SC-82','SH':'SH-83','SX':'SX-84','ST':'ST-85','TM':'TM-86','TA':'TA-87','UN':'UN-88','VB':'VB-89','WP':'WP-90','WN':'WN-91','WA':'WA-92','WY':'WY-93','WE':'WE-94','WB':'WB-95','WS':'WS-96','WD':'WD-97','WO':'WO-98','WR':'WR-99'};

			jQuery('#input_4_21').change(function(){
				var countyCode = jQuery(this).val();
				if(countyCode != undefined){
					var region = countryRegions[countyCode];
					var Num = countryNum[countyCode];
				}
				else{
					var region = '';
					var Num = '';
				}
				

				jQuery('#input_4_64').val(region);
				jQuery('#input_4_65').val(countyCode);
				jQuery('#input_4_66').val(Num);
			});
			// 21-6-17 end : for getting regions based on country selection 

			// 27-6-17 start
			jQuery('#input_4_56 li input').change(function() {
			    if(this.checked) {
			        console.log('add '+ jQuery(this).val());
			        var v = jQuery(this).val().split('-');
			        var h = '<span>';
			        h += v[1];
			        h += '</span>';
			        jQuery(this).parent().append(h);
			    }
			    else{
			        jQuery(this).parent().find('span').remove();
			    }
			});
			// 27-6-17 end
		</script>
	<?php }	?>

	<!-- for home page grid view -->
	<?php if(get_the_ID() == 62 ){ ?>
	
	<script type="text/javascript">
		//jQuery(document).ready(function(){
			var icons = jQuery('.pt-cv-page .pt-cv-content-item').last().find('.pt-cv-content div');
			jQuery('.pt-cv-page .pt-cv-content-item').last().find('a.pt-cv-href-thumbnail').append(icons);
		//});

	</script>

	<?php }	?>
	
<?php  //15/09/2017
		if(get_the_ID() == 207){ ?>
		<script type="text/javascript">
			//  start : for getting regions based on country selection
			var countryRegions = {'AR':'4','AD':'4','AL':'2','AP':'5','AU':'4','BN':'6','BH':'6','BO':'1','BR':'2','BU':'6','BV':'3','BT':'2','CN':'1','CA':'1','CS':'4','CE':'6','CG':'2','CH':'3','CW':'2','CK':'4','CY':'3','CT':'6','CL':'6','CR':'3','DA':'1','DV':'5','DC':'4','DE':'6','DM':'5','DK':'3','DQ':'6','EM':'2','FA':'2','FL':'2','FK':'2','FR':'4','GR':'1','GY':'1','GU':'4','HM':'1','HK':'2','HR':'1','HA':'4','HE':'5','HW':'2','HU':'2','ID':'3','IA':'6','JK':'6','JA':'1','JE':'5','JO':'6','JN':'6','KK':'5','KO':'2','LE':'5','LI':'6','LO':'5','LU':'5','LY':'3','MD':'4','MA':'5','MR':'5','MH':'1','ML':'4','MI':'2','MO':'3','MN':'5','MG':'4','MU':'5','OB':'3','OS':'3','PG':'4','PA':'3','PL':'3','PO':'3','PK':'1','PT':'4','PS':'1','RG':'4','SA':'3','SC':'6','SH':'4','SX':'3','ST':'1','TM':'1','TA':'4','UN':'4','VB':'5','WP':'5','WN':'1','WA':'5','WY':'5','WE':'1','WB':'2','WS':'2','WD':'3','WO':'2','WR':'2'};

			var countryNum = {'AR':'1','AD':'2','AL':'3','AP':'4','AU':'5','BN':'6','BH':'7','BO':'8','BR':'9','BU':'10','BV':'11','BT':'12','CN':'13','CA':'14','CS':'15','CE':'16','CG':'17','CH':'18','CW':'19','CK':'20','CY':'21','CT':'22','CL':'23','CR':'24','DA':'25','DV':'26','DC':'27','DE':'28','DM':'29','DK':'30','DQ':'31','EM':'32','FA':'33','FL':'34','FK':'35','FR':'36','GR':'37','GY':'38','GU':'39','HM':'40','HK':'41','HR':'42','HA':'43','HE':'44','HW':'45','HU':'46','ID':'47','IA':'48','JK':'49','JA':'50','JE':'51','JO':'52','JN':'53','KK':'54','KO':'55','LE':'56','LI':'57','LO':'58','LU':'59','LY':'60','MD':'61','MA':'62','MR':'63','MH':'64','ML':'65','MI':'66','MO':'67','MN':'68','MG':'69','MU':'70','OB':'71','OS':'72','PG':'73','PA':'74','PL':'75','PO':'76','PK':'77','PT':'78','PS':'79','RG':'80','SA':'81','SC':'82','SH':'83','SX':'84','ST':'85','TM':'86','TA':'87','UN':'88','VB':'89','WP':'90','WN':'91','WA':'92','WY':'93','WE':'94','WB':'95','WS':'96','WD':'97','WO':'98','WR':'99'};

			//var countryRegions = {'AR':'AR-1','AD':'AD-2','AL':'AL-3','AP':'AP-4','AU':'AU-5','BN':'BN-6','BH':'BH-7','BO':'BO-8','BR':'BR-9','BU':'BU-10','BV':'BV-11','BT':'BT-12','CN':'CN-13','CA':'CA-14','CS':'CS-15','CE':'CE-16','CG':'CG-17','CH':'CH-18','CW':'CW-19','CK':'CK-20','CY':'CY-21','CT':'CT-22','CL':'CL-23','CR':'CR-24','DA':'DA-25','DV':'DV-26','DC':'DC-27','DE':'DE-28','DM':'DM-29','DK':'DK-30','DQ':'DQ-31','EM':'EM-32','FA':'FA-33','FL':'FL-34','FK':'FK-35','FR':'FR-36','GR':'GR-37','GY':'GY-38','GU':'GU-39','HM':'HM-40','HK':'HK-41','HR':'HR-42','HA':'HA-43','HE':'HE-44','HW':'HW-45','HU':'HU-46','ID':'ID-47','IA':'IA-48','JK':'JK-49','JA':'JA-50','JE':'JE-51','JO':'JO-52','JN':'JN-53','KK':'KK-54','KO':'KO-55','LE':'LE-56','LI':'LI-57','LO':'LO-58','LU':'LU-59','LY':'LY-60','MD':'MD-61','MA':'MA-62','MR':'MR-63','MH':'MH-64','ML':'ML-65','MI':'MI-66','MO':'MO-67','MN':'MN-68','MG':'MG-69','MU':'MU-70','OB':'OB-71','OS':'OS-72','PG':'PG-73','PA':'PA-74','PL':'PL-75','PO':'PO-76','PK':'PK-77','PT':'PT-78','PS':'PS-79','RG':'RG-80','SA':'SA-81','SC':'SC-82','SH':'SH-83','SX':'SX-84','ST':'ST-85','TM':'TM-86','TA':'TA-87','UN':'UN-88','VB':'VB-89','WP':'WP-90','WN':'WN-91','WA':'WA-92','WY':'WY-93','WE':'WE-94','WB':'WB-95','WS':'WS-96','WD':'WD-97','WO':'WO-98','WR':'WR-99'};

			jQuery('#input_6_24').change(function(){
				var countyCode = jQuery(this).val();
				if(countyCode != undefined){
					var region = countryRegions[countyCode];
					var Num = countryNum[countyCode];
				}
				else{
					var region = '';
					var Num = '';
				}
				

				jQuery('#input_6_21').val(region);
				jQuery('#input_6_26').val(countyCode);
				jQuery('#input_6_27').val(Num);
			});
			//  for getting regions based on country selection 

			//  start
			jQuery('#input_6_18 li input').change(function() {
			    if(this.checked) {
			        console.log('add '+ jQuery(this).val());
			        var v = jQuery(this).val().split('-');
			        var h = '<span>';
			        h += v[1];
			        h += '</span>';
			        jQuery(this).parent().append(h);
			    }
			    else{
			        jQuery(this).parent().find('span').remove();
			    }
			}); 
			// 27-6-17 end
			
		</script>
	<?php }	?>
	

</body>
</html>