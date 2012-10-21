<?php $this->load->helper('url'); ?>
<style>
	
</style>
<script>
  $(function(){
    /* Reset View */
    $("#active-list").parent().css({"padding-left":"0px","padding-right":"0px"});
    $("#active-list ul li").width("100%");
  });
</script>

<!-- ActiveList -->
   <div class="padding"></div>
   <div id="active-list">
     <div class="padding"></div>
     
     <ul>
     	 <?php foreach($area as $index => $i): ?>
       	 <li><?php echo $i['area']; ?><a class="enter" href="<?php echo base_url('index.php/active/name/'.$i['area']); ?>"  target="_self"></a></li>    
       <?php endforeach; ?> 
     </ul>
     
     <div class="padding"></div>
   </div>
<!-- ActiveList -->