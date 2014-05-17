var ui = {
	speed: 1000,
	ajaxFile: "control.php",
	px2int: function(v) {
		return v.split("p")[0]*1;
	},
	zufall: function(a, b) {
		return Math.round(Math.random()*(b-a))+a;
	}
};
shown = null;
$(function() {
	$("#wrap").hide().cssFadeIn(1000, function() {
		if(shown)
			shown();
	});
	$("#home").click(function() {
		location.search = "";
	});
});