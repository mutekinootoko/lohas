<?php $this->load->helper('url'); ?>
<style>
	#activity-list ul li .top .right .content, #activity-list ul li .bottom .info .content{background:none;}
	.image{background:#ffffff;}
	.content ul li{margin-left:10px;}
	#activity-list ul li .bottom .active, #activity-list ul li .bottom .info1{background-position:-4px -129px;}
	.ui-body-c, .ui-overlay-c, .ui-body-c, .ui-overlay-c{text-shadow:none;}
	#activity-list .bottom ul li{font-size:16px;font-weight:bold;}
</style>
<script>
	$(document).ready(function(){
		$('.status_not_ok').click(function(){
			$.fancybox.showLoading();
			var pk = $(this).attr('rel');
			$.ajax({
				url: '<?php echo base_url("index.php/home/api_checkin"); ?>',
			  cache: false,
			  dataType: 'json',
			  async : false,
			  type: 'POST',
			  data: 'pk='+pk,
			  success: function(data){
			  	if(data.success == 'Y'){
			  		$('.info[rel='+pk+']').addClass('info1');
			  		$('.status_not_ok[rel='+pk+']').after('<a class="status_ok"></a>');
			  		$('.status_not_ok[rel='+pk+']').remove();
			  	}
			  	$.fancybox.hideLoading();
			  }
			});
		});
	});
</script>
<!-- ActivityList -->
   <div class="padding"></div>
   <div id="activity-list">
     <div class="padding"></div>
     
     <ul>
       <li>
         <div class="top">
           <div class="left">
             <div class="photo"><div class="image"><a href="#"><img width="194" src="<?php echo $user_data['user_avatar']; ?>" /></a></div></div>
           </div>
           <div class="right">
            <div class="content">
            	<ul>
            		<li><?php echo $user_data['user_name']; ?></li>
            		<li><?php echo date('Y-m-d'); ?></li>
            	</ul>
            </div>
           </div>
         </div>
       </li>
       <?php if(!empty($todo)): ?>
	       	<?php foreach($todo as $indexi => $i): ?>
		       <li>
		         <div class="bottom">
		           <div class="info <?php echo ($i['checkin'] == 'Y' ? 'info1':''); ?>" rel="<?php echo $i['action_pk']; ?>">
		            <div class="content">
		            	<ul>
		            		<li><?php echo $i['action_content']; ?></li>
		            		<?php if(!empty($i['friends'])): ?>
		            			<li>
		            				<?php foreach($i['friends'] as $indexj => $j): ?>
		            					<img src="<?php echo $j['avatar']; ?>">
		            				<?php endforeach; ?>
		            			</li>
		            		<?php endif; ?>
		            	</ul>
		            </div>
		            <?php if($i['checkin'] == 'N'): ?>
		            	<a class="status_not_ok" href="#" rel="<?php echo $i['action_pk']; ?>"></a>
		            <?php else: ?>
		            	<a class="status_ok"></a>
		            <?php endif; ?>
		           </div>
		         </div>
		       </li>
		    	<?php endforeach; ?>
		    <?php endif; ?>
      </ul>
     <div class="padding"></div>
   </div>
<!-- ActivityList -->