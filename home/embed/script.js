$(function() {
	$(".menuPoint").each(function(i, v) {
		var name = $(v).attr("name");
		$(v).append("<img src='img/menu/"+name+".png' alt=''>").click(function() {
			location.href = "?s="+name;
		});
	});
});