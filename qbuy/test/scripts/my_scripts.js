$(document).ready(function(){
 getXMLproduct1();
 getXMLproduct2();
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
            $("#product2").append(
                    '<div><a href=' + $(this).find("sale_url").text() + ' target="_blank"><img width="200" height="200" src='+ $(this).find("pic_url").text() + '></a><br/>'	+				
                    '產品名稱:' + $(this).find("name").text()   + '</br>' +
                    '產編:' + $(this).find("product_sn").text() + '</br>' + 
				'市價:<font color="red">$' + $(this).find("sale_price").text() + '</font></br></br></div>' );			
					});
			}
		});
	} 

 });
 