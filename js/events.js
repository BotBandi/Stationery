$(document).ready(function(){
	$(".cart").blur(function(){
		var db = $(this).val();
		var itemNumber = $(this).attr('id');
		var url = 'cart.php?product='+itemNumber+'&quantity='+db;
		$(location).attr('href',url);
	});
});