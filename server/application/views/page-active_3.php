<?php $this->load->helper('url'); ?>
<style>
	#active-info ul li .team .join{text-decoration:none;height:35px;}
	#active-info ul li .team .thing img{float:none;}
</style>
<script>
	var i = 0;
	$(document).ready(function(){
		$('.join').click(function(){
			if(i == 0){
				$.fancybox.showLoading();
				var pk = $(this).attr('rel');
				$.ajax({
					url: '<?php echo base_url("index.php/active/api_add_activity"); ?>',
				  cache: false,
				  dataType: 'json',
				  async : false,
				  type: 'POST',
				  data: 'pk='+pk,
				  success: function(data){
				  	if(data.success == 'Y'){
				  		location.href = '<?php echo base_url("index.php/home"); ?>';
				  	}
				  	$.fancybox.hideLoading();
				  }
				});
				i++;
			}
		});
		$('.add_btn').click(function(){
			if(i == 0){
				$.fancybox.showLoading();
				$.ajax({
					url: '<?php echo base_url("index.php/active/api_add_activity"); ?>',
				  cache: false,
				  dataType: 'json',
				  async : false,
				  type: 'POST',
				  data: 'pk='+$('input[name="pk"]').val()+'&title='+$('input[name="title"]').val(),
				  success: function(data){
				  	if(data.success == 'Y'){
				  		location.href = '<?php echo base_url("index.php/home"); ?>';
				  	}
				  	$.fancybox.hideLoading();
				  }
				});
				i++;
			}
		});
	});
</script>
<link href="https://developers.google.com/maps/documentation/javascript/examples/default.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
  $(function(){
    /* Reset View */
    $("#active-info").parent().css("padding", "0px");
    $("#active-info ul li:eq(3)").css("height", "412px");
    $("#active-info ul li .team").parent().height(60);
    $("#active-info ul li:last").css("height", "53px");
    initialize();
  });
  
  /* GoogleMap */
  var rendererOptions = {
        draggable: true
      };
      var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);;
      var directionsService = new google.maps.DirectionsService();
      var map;

      var australia = new google.maps.LatLng('25.057469', '121.614371');

      function initialize() {

        var mapOptions = {
          zoom: 7,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: australia
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        directionsDisplay.setMap(map);
        
        calcRoute();
      }

      function calcRoute() {

        var request = {
          origin: new google.maps.LatLng('25.057469', '121.614371'),
          destination: new google.maps.LatLng(<?php echo $info['s_lat']; ?>, <?php echo $info['s_lon']; ?>),
          waypoints: [],
          optimizeWaypoints: true, 
          travelMode: google.maps.TravelMode.WALKING
        };
        
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
          }
        });
        
      }
  /* GoogleMap */ 
  
</script>

<!-- ActivecCenterList -->
   <!-- <div class="padding"></div> -->
   <div id="active-info">
   
     <div class="padding"></div>
     <ul>
       
       <li><span>名稱: <?php echo $info['s_org_name']; ?></span></li>
       <li><span>電話: <a href="tel:<?php echo $info['s_phone']; ?>"><?php echo $info['s_phone']; ?></a></span></li>
       <li><span>地址: <?php echo $info['s_address']; ?></span></li>
       <li>  
        <!-- GoogleMap -->
        <div style="width: 745px; height: 400px; margin:10px;">
          <div id="map_canvas" style="top:30px;"></div>
        </div>
        <!-- GoogleMap -->
       </li>
       
       <!-- Team -->
       <?php if(!empty($list)): ?>
       	 <?php foreach($list as $indexi => $i): ?>
		       <li> 
		         <div class="team" style="margin:10px;">
		           <img src="<?php echo $i['avatar']; ?>" /> 
		           <div class="thing">
		           	 <?php echo $i['title']; ?>
		           </div>
		           <?php if(!empty($i['friends'])): ?>
		           	 <?php foreach($i['friends'] as $indexj => $j): ?>
		           	   <img src="<?php echo 'https://graph.facebook.com/'.$j['fbid'].'/picture'; ?>" style="width:40px;">
		           	 <?php endforeach; ?>
		           <?php endif; ?>
		           <?php $fb_data = $this->session->userdata('fb_data'); ?>
		           <?php if($i['fbid'] != $fb_data['uid']): ?>
		           	<div class="join" href="#" rel="<?php echo $i['item_pk'].','.$code.','.$date; ?>" data-ajax="false">加入</div>
		           <?php endif; ?>
		         </div>
		       </li>
		     <?php endforeach; ?>
	     <?php endif; ?>
       <!-- Team -->
       
       <!-- New Team -->
       <li>
         <form class="new_team_block">
           <input type="text" class="new_team_input" name="title" value="" /> 
           <input type="hidden" name="pk" value="<?php echo '0,'.$code.','.$date; ?>" />
           <div class="add_btn" style="margin-left: 15px; margin-top: 4px; float:left; cursor:pointer;" data-ajax="false" rel="external">新增</div>
         </form>
       </li>
       <!-- New Team -->
       
     </ul>
     
     <div class="padding"></div>
   </div>
<!-- ActivecCenterList -->