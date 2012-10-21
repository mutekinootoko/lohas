<?php $this->load->helper('url'); ?>
<style>
	#active_center-list #title a {
    font-family: "微軟正黑體";
    font-size: 30px;
    letter-spacing: 1px;
    margin-right: 10px;
    text-decoration: underline;
    color:#4C372D;
	}
</style>
<script>
  $(function(){
    /* Reset View */
    $("#active_center-list").parent().css("padding","0px");
  });
</script>

<!-- ActivecCenterList -->
   <!-- <div class="padding"></div> -->
   <div id="active_center-list">
     <div id="title"><a href="<?php echo base_url('index.php/active'); ?>" target="_self"><?php echo $area; ?></a></div>
     <!-- <div class="padding"></div> -->
     <ul>
     	<?php foreach($name as $index => $i): ?>
       <li><?php echo $i['name']; ?><a class="enter" href="<?php echo base_url('index.php/active/info/'.$i['s_address_code']); ?>" target="_self" style="background: url('../images/layout.png') no-repeat scroll -245px -303px transparent;height: 30px;position: absolute;right: 25px;top: 5px;width: 30px;"></a></li>       
      <?php endforeach; ?>
     </ul>
     
     <div class="padding"></div>
   </div>
<!-- ActivecCenterList -->