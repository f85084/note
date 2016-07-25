<?
$url = 'http://sso.cue.social/cue-product-p.php?auth=279738bf8971eb963e9e12bdd8d6c9fd&sn=26411';

 ?>
  <html xmlns="http://www.w3.org/1999/xhtml" >
 <head>
		<title>商品</title> 
	 <meta charset="utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
 $(document).ready(function(){
			getXMLproduct1();
			getXMLproduct2();
			//getXMLproduct3();
	function getXMLproduct1() {
    $.getJSON("cuebuy_api.json", function(data) {
        $.each(data.product, function(i, item) {
            $("#product1").append(
                    '<div><a href=' + item.sale_url + ' target="_blank"><img width="200" height="200" src='+ item.pic_url + '></a><br/>'	+				
                    '產品名稱:' + item.name   + '</br>' +
                    '產編:' + item.product_no + '</br>' + 
                    '市價:$' + item.market_price + '</br></br></div>' );
					
        });
        });
}		
		function getXMLproduct2(){
		$.ajax({
			url: "cuebuy_api.xml",
			cache: false,
			dataType: "xml",
			success:  function(xml){
				$(xml).find("product").each(function() {
					var info = '<a href=' + $(this).find("sale_url").text() + ' target="_blank"><img width="300" height="300" src='
					+ $(this).find("pic_url").text() + '></a><br/><br/>編號: ' + $(this).find("product_sn").text() + '<br/>商品名稱:' + $(this).find("name").text() +
					'<br/>優惠價：<font color="red">$' + $(this).find("sale_price").text() + '</font><br/>內容: <br/>' + $(this).find("context").text() + ' <br/><br/><br/><br/><br/>';
			$('#product2').append( info );
				});
			}
		});
	}
 		function getXMLproduct3(){
		$.ajax({
            type: "get",
			async: false,
			url: "<?=$url?>&callback=localhandler",
			dataType: "jsonp",
			jsonp: "callback",
			jsonpCallback:"localhandler",
			success:  function(json){
			$(json).find("product").each(function() {
            $("#product1").append(
                    '<div><a href=' + json.sale_url + ' target="_blank"><img width="200" height="200" src='+ item.pic_url + '></a><br/>' +				
                    '產品名稱:' + json.name   + '</br>' +
                    '產編:' + json.product_no + '</br>' + 
                    '市價:$' + json.market_price + '</br></br></div>' );
					
        });	
        },
		    error: function(){
            alert('fail');
             }
		});	
		}	
	
	
 });

      </script>
     </head>
  <body>
  		<div id="main">
				<h4>商品</h4>
				<div id="product1"></div>
				<div id="product2"></div>

		</div>
		<script  src="scripts/jquery-1.6.2.min.js"></script>

  </body>
 </html>