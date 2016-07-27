$(document).ready(function(){
	
 getXMLproduct1();
 getXMLproduct2();
 getXMLproduct3();

 	function getXMLproduct1() {
    $.getJSON("cuebuy_api_1.json", function(data) {
        $.each(data.product, function(i, item) {
            $("#product1").append(
                    '<a href=' + item.sale_url + 
					' target="_blank"><img width = "100%" height="100%"  src='+ item.pic_url +
					'></a><br/><div class="ground_glass_caption">'	+				
                      item.name   + '</br>' +
                    '<font color="red">TWD $<span style="font-weight:bold;font-size:30px;">' + item.market_price  + '</span></font></div>' );
        });
        });
}	

 			function getXMLproduct2(){
		$.ajax({
			url: "cuebuy_api_1.xml",
			cache: false,
			dataType: "xml",
			success:  function(xml){
				$(xml).find("product").each(function() {
            $("#product2").append(
                    '<a href=' + 
					$(this).find("sale_url").text() + ' target="_blank"><img width = "100%" height="100%" style="display: block;margin: auto;" src='+ $(this).find("pic_url").text() + '></a><br/><div class="ground_glass_caption">'	+				
                     $(this).find("name").text()   + '</br>' +
				'優惠價:<font color="red">TWD $<span style="font-weight:bold;font-size:30px;">' + $(this).find("sale_price").text()  + '</span></font></div>' );			
					});
			}
		});
	} 
 	function getXMLproduct3() {
    $.getJSON("cuebuy_api_2.json", function(data) {
        $.each(data.product, function(i, item) {
            $("#product3").append(
                    '<a href=' + item.sale_url + 
					' target="_blank"><img width = "100%" height="100%"  src='+ item.pic_url +
					'></a><br/><div class="ground_glass_caption">'	+  item.name   + '</br>' +
                    '<font color="red">TWD $<span style="font-weight:bold;font-size:30px;">' + item.market_price  + '</span></font></div>' );
        });
        });
}	
 });
 