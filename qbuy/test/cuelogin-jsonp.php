<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" >
 <head>
     <title>Untitled Page</title>
	 <meta charset="utf-8">
<?php

date_default_timezone_set("Asia/Taipei");

/* $acc = $_GET['acc'];
$pws = $_GET['pws'];
$sec =  md5(date("Ymd").'cuebuy'.date("Hi")); */

$url = 'http://sso.cue.social/cue-product-p.php?auth=279738bf8971eb963e9e12bdd8d6c9fd&sn=28263';


?>
      <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
      <script type="text/javascript">
     jQuery(document).ready(function(){ 
        $.ajax({
             type: "get",
             async: false,
             url: "<?=$url?>&callback=localhandler",
             dataType: "jsonp",
             jsonp: "callback",
             jsonpCallback:"localhandler",
             success: function(json){
                 alert('產品編號： ' + json.product_sn + '，產品名稱： ' + json.name );
             },
             error: function(){
                 alert('fail');
             }
         });
     });
     </script>
     </head>
  <body>
  </body>
 </html>