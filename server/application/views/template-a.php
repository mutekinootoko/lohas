<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="js/jquery-1.8.2.min.js"></script>
  	<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.mobile-1.1.1/jquery.mobile-1.1.1.min.js"></script>
		
		<?php foreach($this->page_obj->js as $js):?>
			<script type="text/javascript" src="<?php echo $js;?>.js"></script>
		<?php endforeach;?>
		<link rel="stylesheet" type="text/css" href="js/jquery.mobile-1.1.1/jquery.mobile-1.1.1.min.css" media="screen" />
		<?php foreach($this->page_obj->css as $css):?>
			<link rel="stylesheet" type="text/css" href="<?php echo $css;?>.css" media="screen" />
		<?php endforeach;?>
		<title><?php echo $this->page_obj->title; ?></title>
		
<script>
	<?php $this->load->helper('url'); ?>
	// 取得 base url
	var site = '<?php echo base_url(); ?>';
	var site_len = site.length;
	
	$(document).ready(function(){
		if(location.href.substr(site_len+7) == '' || location.href.substr(site_len+7) == 'home'){
			
		}
		if(location.href.substr(site_len+7,7) == 'app/top' || location.href.substr(site_len+7,8) == 'app/top/'){
			$('.ui-block-a a').addClass('btn_active');
		}
		if(location.href.substr(site_len+7,6) == 'review'){
			$('.ui-block-b a').addClass('btn_active');
		}
		if(location.href.substr(site_len+7,8) == 'app/free' || location.href.substr(site_len+7,9) == 'app/free/'){
			$('.ui-block-c a').addClass('btn_active');
		}
		if(location.href.substr(site_len+7,5) == 'packs'){
			$('.ui-block-d a').addClass('btn_active');
		}
		if(location.href.substr(site_len+7,4) == 'news'){
			$('.ui-block-e a').addClass('btn_active');
		}
	});
</script>
<style>
	.ui-btn-icon-top .ui-btn-inner .ui-icon{left:47%;}
	.ui-btn-icon-top .ui-btn-inner .ui-icon, .ui-btn-icon-bottom .ui-btn-inner .ui-icon{margin-left:-4px;}
	a{text-decoration:none;}
	.con_inside{width:100%;}
	.home .ui-icon{background:url('images/icon/List bullets.png');display:block;width:30px;height:30px;border-radius:0;margin-top:-6px;}
	.actives .ui-icon{background:url('images/icon/Ads.png');display:block;width:30px;height:30px;border-radius:0;margin-top:-6px;}
	.free .ui-icon{background:url('images/icon/Runner.png');display:block;width:30px;height:30px;border-radius:0;margin-top:-6px;}
	.garbage .ui-icon{background:url('images/icon/Synchronize.png');display:block;width:30px;height:30px;border-radius:0;margin-top:-6px;}
	.btn_active{background: -moz-linear-gradient(#444444, #FFFFFF) repeat scroll 0 0 #444444;background: -webkit-linear-gradient(#444444, #FFFFFF) repeat scroll 0 0 #444444;}
</style>
	</head>

	<body>
		<?php $this->load->helper('url'); ?>
		<div data-role="page">
			<div data-role="header" data-position="fixed" data-theme="d">
				<a href="#" data-role="button" data-icon="back" data-mini="true" data-add-back-btn="true" data-rel="back">Back</a>
				<a href="<?php echo base_url('mobile/'); ?>" target="_self" data-role="button" data-icon="home">Home</a>
				<h1><?php echo $this->page_obj->title; ?></h1>
			</div>
			<!-- /header -->
			<div data-role="content">
				<?php echo $this->page_obj->main_content; ?>
			</div>
			<div data-role="footer" role="contentinfo">
				<div data-role="navbar" role="navigation">
					<ul>
						<li><a class="home btn" href="<?php echo base_url('mobile/app/top'); ?>" rel="external" target="_self" data-role="button" data-icon="custom" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="d" data-inline="true">今日清單</a></li>
						<li><a class="actives btn" href="<?php echo base_url('mobile/review/lists'); ?>" rel="external" target="_self" data-role="button" data-icon="custom" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="d" data-inline="true">揪團活動</a></li>
						<li><a class="free btn" href="<?php echo base_url('mobile/app/free'); ?>" rel="external" target="_self" data-role="button" data-icon="custom" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="d" data-inline="true">商品優惠</a></li>
						<li><a class="garbage btn" href="<?php echo base_url('mobile/packs/lists'); ?>" rel="external" target="_self" data-role="button" data-icon="custom" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="d" data-inline="true">垃圾車時間</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /page -->
	</body>
</html>