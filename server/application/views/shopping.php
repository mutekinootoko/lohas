<script>
  $(function(){
    /* Reset View */
    $("#shopping-list").parent().css("padding", "0px");
    $("#shopping-list ul li:last").css("height", "53px");
    
    $(".plus").click(function(){
    	$("#form_booking_"+$(this).attr("rel")).submit();
    });
  });
</script>
<!-- ShoppingList -->
   <div class="padding"></div>
   <div id="shopping-list">
     <div class="padding"></div>
     <ul>
				<?php foreach($items as $item):?>
       		<li> 
       			<div style="float:left;font-size:18px;width:720px;height:33px;overflow:hidden;"><?php echo $item["title"];?></div> 
	       			<?php if($item["action_pk"]!=''):?>
	       				<div class="minus" rel="<?php echo $item["sell_pk"]?>" style="float:left;">
	       			<?php else:?>
	       				<div class="plus" rel="<?php echo $item["sell_pk"]?>" style="float:left;">
	       			<?php endif;?>
       				<form id="form_booking_<?php echo $item["sell_pk"]?>" action="/index.php/products/sell_booking/<?php echo $item["sell_pk"]?>" method="post" target="_self">
       				</form>
       			</div>
       		</li>
       	<?php endforeach;?>
       <!-- New Product -->
       <li>
         <div class="new_product_block">
           <input type="text" class="new_team_input" name="new_product_val" value="" /> 
           <div style="margin-left: 15px; margin-top: 4px; float:left;">新增</div>
         </div>
       </li>
       <!-- New Product -->
     </ul>
     <div class="padding"></div>
     
   </div>
<!-- Shopping nmList -->