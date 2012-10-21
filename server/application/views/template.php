<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo $this->page_obj->page_title; ?></title>
<base href="/">  
<script src="http://yui.yahooapis.com/3.7.3/build/yui/yui-min.js"></script>
<script src="js/jquery-1.8.2.min.js"></script>
<script src="js/jquery-ui-1.8.20.custom.min.js"></script>
<script src="js/jquery.mobile-1.1.1/jquery.mobile-1.1.1.js"></script>
<script src="js/fancyBox-v2.1.1/fancybox/jquery.fancybox.js"></script>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
<link href="js/fancyBox-v2.1.1/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />

</head>

<body>

<div style="width:100%; overflow:hidden;">

  <!-- Header -->
  <div data-role="header" data-theme="a">
    <h1><?php echo $this->page_obj->page_title ?></h1>
  </div>
  <!-- Header -->
  
  <!-- Content -->
  <div data-role="content" data-time="a">
   <?php echo $this->page_obj->main_content; ?>
  </div>
  <!-- Content -->

</div>

</body>
</html>