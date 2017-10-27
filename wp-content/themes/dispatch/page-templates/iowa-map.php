<?php
/* Template Name: Iowa-Map */ 

get_header();

//entries from registration form
$search_criteria['status'] = 'active';
$entries = GFAPI::get_entries( 5 , $search_criteria );
$region = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0); 
$reg = array(); 
$result = array();
$mapData = [];
//echo '<pre>' ; print_r($entries);
//count of regions 
$regionCount = count($entries);
foreach($entries as $en){
	$regionArr[$en[48]][] = $en[47];
	$reg[]= $en[48];
	$region[$en[48]] = ++$region[$en[48]];
	$address = json_encode($en[61]);
	$city= $en[47];
	$type[]= $en[66];
	 $v = str_replace(' ','+',convert_chars(addslashes(iconv('', 'utf-8',$address))));
				 //$url = $http.'maps.google.com/maps/api/geocode/json?address='.$v.'&sensor=false';
				 $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$v&key=AIzaSyAVD0ngfhOFs5rnww7UFyz9rN6UznOIZ1U";
				 $ch = curl_init();
				  curl_setopt($ch, CURLOPT_URL, $url);
				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
					 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					 $geocode = curl_exec($ch);
					curl_close($ch); 
				$output= json_decode($geocode);
				$lat = $output->results[0]->geometry->location->lat ;
				$long = $output->results[0]->geometry->location->lng ;
	$mapData[] = array($city, $lat ,$long);
}
//echo '<pre>' ;print_r($mapData);
for($i =1; $i<=6; $i++){
	$res = ($region[$i] /  $regionCount) * 100 ;
	$result[] = number_format($res, 2, '.', ',' ); 
}

$mapData = json_encode($mapData);
$type = "'" . implode("','",$type) . "'";

//calculate region count in percentage	


?>

<script src="http://maps.google.com/maps/api/js?key=AIzaSyAVD0ngfhOFs5rnww7UFyz9rN6UznOIZ1U" type="text/javascript"></script>
<!-- DataTables  -->
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"> </script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>

<!-- Css for pie-chart  -->
<style type= "text/css">

.g-blue{
    color: rgba(25,57,165,1);
    fill: rgba(25,57,165,1);
}
.g-orange{
    color: rgba(255,106,1,1);
    fill: rgba(255,106,1,1);
}
.g-grape{
    color: rgba(147,25,123,1);
    fill: rgba(147,25,123,1);
}
.g-green{
    color: rgba(111,167,66,1);
    fill: rgba(111,167,66,1);
}
.g-sky{
    color: rgba(104,205,230,1);
    fill: rgba(104,205,230,1);
}
.g-brick{
    color: rgba(165,64,35,1);
    fill: rgba(165,64,35,1);
}
.g-gold{
    color: rgba(249,200,23,1);
    fill: rgba(249,200,23,1);
}
.g-red{
    color: rgba(255,0,0,1);
    fill: rgba(255,0,0,1);
}
.col-sm-12.col-md-12.charts {
    padding-top: 40px;
}
canvas.piechart.chartarea{
	margin-top:-30px
}
.isicsbiotis-outer{
	margin-top:25px;
}
.canvas-chart{
	overflow:hidden;
}
.charts .col-md-4 {
    overflow: hidden;
}
pre{
    white-space: normal
}
.dataTables_wrapper{
    margin-top: 35px;
}
.charts-area{
    margin-top: 50px;
    text-align: right;
}
.page_title{
        margin-bottom: 45px;
    }
.region-count{
	 margin-bottom: -5px;
	color: rgba(200,0,0,1);
    fill: rgba(200,0,0,1);
}
.main ul, #main ul.disc {
    list-style: none;
	margin-left: 36px;
}
.legend li { float: inherit; margin-left: 225px; text-align: left;}
.legend span { border: 1px solid #000000; float: left; width: 15px; height: 10px; margin-top: 5px; margin-left: 13px;}
/*colors */
.legend .Inprogress {margin-right: 4px; background-color: #FF7A6B; }
.legend .Online {margin-right: 4px; background-color: #6991FD; }
.legend .Approved {margin-right: 4px; background-color: #00E64D; }
.status{margin-right: 60px; color: rgba(200,0,0,1); fill: rgba(200,0,0,1);}
	
</style>
<!-- end Css for pie-chart  -->

<!--  pie-chart  -->
<div class="isicsbiotis-outer">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 col-md-8" >
				<h2 class="page_title"> Iowa Homeland Security Regions </h2>
				<div id="map" style="height: 500px; ">
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="charts-area">
					<div class="row charts">
						<h3 class ="region-count"> Region Count:</h3>
						<div class= "col-sm-12 col-md-12  charts">
							<div class="g-gold"> <b>Region 1:&nbsp;</b><?php echo $region[1];?> </div>
						</div>
					</div>
				<div class="row charts">
					<div class= "col-sm-12 col-md-12  charts">
						<div class="g-green"> <b>Region 2:&nbsp;</b><?php echo $region[2];?> </div>
					</div>
				</div>
				<div class="row charts ">
					<div class= "col-sm-12 col-md-12  charts">
						<div class="g-red"> <b>Region 3:&nbsp;</b><?php echo $region[3];?> </div>
					</div>
				</div>
				<div class="row charts">
					<div class= "col-sm-12 col-md-12  charts">
						<div class="g-sky"> <b>Region 4:&nbsp;</b><?php echo $region[4];?> </div>
					</div>
				</div>
				<div class="row charts">		
					<div class= "col-sm-12 col-md-12  charts">
						<div class="g-orange"> <b>Region 5:&nbsp;</b><?php echo $region[5];?> </div>
					</div>
				</div>
				<div class="row charts">
					<div class= "col-sm-12 col-md-12  charts">
						<div class="g-grape"> <b>Region 6:&nbsp;</b><?php echo $region[6];?> </div>
					</div>
				</div>
				<div class="row charts">
					<div class= "col-sm-12 col-md-12  charts">
						<h3 class ="status"> Status:</h3>
						<ul class="legend">
							<li><span class="Inprogress"></span> Inprogress</li>
							<li><span class="Online"></span>Online</li>
							<li><span class="Approved"></span>Approved</li>
						</ul>
					</div>
				</div>
			</div>
		</div>	
		</div>
<!--  pie-chart End  -->

<!--  Data table for region-country-agency sorting  -->
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<table id="example" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Region</th>
							<th>County</th>
							<th>Agency</th>
							<th>Agency Discipline</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($entries as $key=>$entry){?>
						<tr>
								<td><?php echo $entry[48];?></td> <!--  Region  -->
								<td><?php echo $entry[47];?></td>  <!--  Country  -->
								<td><?php echo $entry[44];?></td>  <!--  Agency  -->
								<td><?php echo $entry[60];?></td>  <!--  Agency  -->
								<td><?php echo $entry[66];?></td>  <!--  Agency  -->
						</tr>
						<?php } ?>
					</tbody>
			   </table>
			</div>
		</div>
	</div>
</div>
<!--  End Data table for region-country-agency sorting  -->	

<!--  Chart-Js to draw PieChart -->

<script>
<!--Js to draw DataTable -->
jQuery(document).ready(function() {
    jQuery('#example').DataTable( {
		"searching": false,
		"lengthChange": false
    } );
} );

<!--Js to draw Map -->
var locations = <?php echo $mapData; ?>;
var type = new Array(<?php echo $type ?>);
var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 7,
      center: new google.maps.LatLng(42.0512233,-93.9136361),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

	var triangleCoords = [
            {lat: 43.5410117, lng: -96.8157992},
            {lat: 43.8223811, lng: -91.2869538},
            {lat: 42.091713, lng: -89.946569},
            {lat: 40.434069, lng: -90.993389},
            {lat: 40.509287, lng: -96.728252}
		];

        // Construct the polygon.
        var bermudaTriangle = new google.maps.Polygon({
          paths: triangleCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#FF0000',
          fillOpacity: 0.1
        });
       // bermudaTriangle.setMap(map);


    var marker, i;
	var iconBase = 'http://maps.google.com/mapfiles/ms/icons/';
        var icons = {
          Online: {
            icon: iconBase + 'blue-dot.png'
          },
          InProgress: {
            icon: iconBase + 'red-dot.png'
          },
          Approved: {
            icon: iconBase + 'green-dot.png'
          }
        };

    for (i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		icon: icons[type[i]].icon,
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
	  
    }
</script>
<?php get_footer();?>