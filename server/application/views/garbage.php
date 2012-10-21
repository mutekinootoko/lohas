<link href="https://developers.google.com/maps/documentation/javascript/examples/default.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="https://raw.github.com/HPNeo/gmaps/master/gmaps.js"></script>

<script>
$(function(){
      
    /* Reset View */
    $("#garbage").parent().css("padding", "0px");
    
    /* Google Map */
    var map;
    map = new GMaps({
      div: '#map',
      zoom: 16,
      lat: 25.05589,
      lng: 121.6140
    });
    
    <?php $use_type_mode = intval($use_type_mode); ?>
    
    <?php if($use_type_mode==1): ?>
      <?php foreach($user_position->reponse1->user_position as $i): ?>
          <?php $position = explode(",", $i->action_content); ?>
          map.addMarker({
            lat: <?php echo $position[0] ?>,
            lng: <?php echo $position[1] ?>
          });
      <?php endforeach; ?>
    <?php else: ?>
      <?php foreach($user_position->reponse2->trash_truck as $u): ?>
      map.addMarker({
        lat: <?php echo $u->latitude ?>,
        lng: <?php echo $u->longitude ?>
      });
      <?php endforeach; ?>
    <?php endif; ?>
    

});
</script>
<!-- garbage view -->
   <div id="garbage">
     
     <div class="padding"></div>
     
     <div id="map"></div>
     
     <div id="line"></div>
     
     <div id="menu">
       
      <div style="margin-left: auto; margin-right: auto; width: 380px;"> 
        <div id="post_add"><a href="location.reload();">我要打卡</a></div>
        <div id="change_mode"><a href="javascript: location.href='http://lohas.adct.org.tw/index.php/garbage/index/2'; ">查詢地點</a></div>
        <div id="search"><a href="javascript: location.href='http://lohas.adct.org.tw/index.php/garbage/index/1'; ">查詢完成</a></div>
      </div>
     
     </div>
     
     <!--
     <ul>
       <li>
        <div style="float:left;font-size:18px;width:720px;height:33px;overflow:hidden;"></div>
        <div class="plus" style="float:left;"></div></li>
       <li>
     </ul>
     -->
     
     <div class="padding"></div>
     
   </div>
<!-- garbage view -->