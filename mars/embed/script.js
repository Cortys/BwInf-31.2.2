var loader = {
	curr: null,
	load: function(param, callback) {
		var t = loader;
		t.stop();
		$("#meta").show();
		t.curr = $.post("mars/"+ui.ajaxFile, param, callback, "json").always(function() {
			t.curr = null;
			$("#meta").hide();
		}).fail(function() {
			loader.load(param, callback);
		});
	},
	stop: function() {
		if(this.curr)
			this.curr.abort();
	}
};

var mars = {
	parts: null,
	settings: null,
	pos: 0,
	interval: null,
	speed: 150,
	viewMode: 0,
	running: false,
	request: 0,
	init: function() {
		this.parts = {
			current: {positions:[[]],stop:false},
			next: null,
		};
		this.pos = 0;
	},
	simulate: function(settings) {
		this.init();
		canvas.clearField();
		this.running = true;
		this.request++;
		this.settings = settings;
		this.settings.fieldSize = canvas.size;
		this.loadNextPart();
		this.startInterval();
		$("#sideTable .stop").fadeIn(300);
		$("#sideTable .start input, #sideTable .start select").attr("disabled", 1);
		$("#distribution").slider("disable");
		$("#sideTable .startButton").hide();
	},
	stopSimulation: function() {
		this.stopInterval();
		this.running = false;
		loader.stop();
		$("#meta").hide();
		$("#sideTable .stop").fadeOut(300, function() {
			$("#sideTable .startButton").show();
		});
		$("#sideTable .start input, #sideTable .start select").removeAttr("disabled");
		$("#distribution").slider("enable");
	},
	startInterval: function() {
		this.stopInterval();
		this.showPart();
		this.interval = setInterval(this.showPart, this.speed);
	},
	stopInterval: function() {
		if(this.interval) {
			clearInterval(this.interval);
			this.interval = null;
		}
	},
	toggleInterval: function() {
		this[(this.interval?"start":"stop")+"Interval"]();
	},
	showPart: function() {
		var t = mars;
		if(!t.running) {
			t.stopInterval();
			return;
		}
		var state = t.parts.current[t.pos];
		if(!state && t.parts.next) {
			t.parts.current = t.parts.next;
			t.loadNextPart();
			t.pos = 0;
			state = t.parts.current[t.pos];
		}
		else if(state)
			t.pos++;
		t.displayState(state);
	},
	loadNextPart: function() {
		var t = this;
		var c = t.parts.current;
		t.settings.robots = c[c.length-1];
		if(t.settings.robots == "END")
			return;
		t.parts.next = null;
		var currRequest = t.request;
		loader.load(t.settings, function(data) {
			if(currRequest == t.request)
				t.parts.next = data;
		});
	},
	displayState: function(state) {
		var t = this;
		if(state == "END") {
			t.stopSimulation();
			return;
		}
		if(!state || (!state.fallen && !state.falling))
			return;
		if(t.viewMode != 1)
			canvas.clearField();
		if(state.falling)
			$.each(state.falling, function(i,v) {
				canvas.drawTarget(v.x, v.y);
			});
		if(state.fallen) {
			var focusedRobot = null;
			$.each(state.fallen, function(i,v) {
				if(v.circle && t.viewMode == 2)
					focusedRobot = v;
				else
					canvas.drawRobot(v.x, v.y, 0, i);
			});
			if(focusedRobot) {
				var circle = focusedRobot.circle;
				var pos = circle.center;
				canvas.drawViewCircle(pos.x+focusedRobot.x, pos.y+focusedRobot.y, circle.radius);
				canvas.drawRobot(focusedRobot.x, focusedRobot.y, focusedRobot.range, 0);
			}
		}
	}
};

var canvas = {
	dom: null,
	context: null,
	scale: 1,
	size: null,
	colors: {
		darkGreen: "#235F01",
		green: "#489D06",
		white: "#ffffff",
		blue: "#06539d",
		darkBlue: "#000e5f",
		transparentBlue: "rgba(6, 83, 157, 0.41)"
	},
	init: function() {
		this.dom = $("#landscape");
		var c = this.context = this.dom[0].getContext("2d");
		var size = props.fieldSizes[2];
		this.resize(size[0], size[1], size[2]);
		c.lineWidth = 2*this.scale;
	},
	resize: function(width, height, scale) {
		this.scale = scale;
		this.size = [width, height];
		width *= scale;
		height *= scale;
		this.dom.css({ backgroundImage:"url('img/mars"+scale+".jpg')" });
		this.dom.cssAnimate({ width:width, height:height, marginLeft:(-width/2), marginTop:(-height/2) }, 300);
		this.dom.attr({ width:width, height:height });
		this.context.fillStyle = "#489D06";
		this.context.strokeStyle = "#235F01";
		this.context.lineWidth = 2*this.scale;
		this.clearField();
	},
	drawRobot: function(x, y, radius, id) {
		var c = this.context;
		var s = this.scale;
		if(radius) {
			c.globalCompositeOperation = "destination-over";
			c.beginPath();
			c.fillStyle = this.colors.transparentBlue;
			c.arc(x*s, y*s, radius*s, 0, 2*Math.PI, false);
			c.fill();
			c.closePath();
			c.globalCompositeOperation = "source-over";
		}
		c.beginPath();
		c.strokeStyle = radius?this.colors.darkBlue:this.colors.darkGreen;
		c.fillStyle = radius?this.colors.blue:this.colors.green;
		c.arc(x*s, y*s, 3*s, 0, 2*Math.PI, false);
		c.fill();
		c.stroke();
		c.closePath();
	},
	drawViewCircle: function(x, y, radius) {
		var c = this.context;
		var s = this.scale*3;
		x *= this.scale;
		y *= this.scale;
		c.globalCompositeOperation = "destination-over";
		c.beginPath();
		c.strokeStyle = "#000e5f";
		c.arc(x, y, radius*this.scale, 0, 2*Math.PI, false);
		c.stroke();
		c.closePath();
		c.globalCompositeOperation = "source-over";
		c.beginPath();
		c.moveTo(x-s, y-s);
		c.lineTo(x+s, y+s);
		c.stroke();
		c.moveTo(x-s, y+s);
		c.lineTo(x+s, y-s);
		c.stroke();
		c.closePath();
	},
	drawTarget: function(x, y) {
		var c = this.context;
		var s = this.scale*3;
		x *= this.scale;
		y *= this.scale;
		c.beginPath();
		c.strokeStyle = "#235F01";
		c.moveTo(x-s, y-s);
		c.lineTo(x+s, y+s);
		c.stroke();
		c.moveTo(x-s, y+s);
		c.lineTo(x+s, y-s);
		c.stroke();
		c.closePath();
	},
	clearField: function() {
		this.context.clearRect(0, 0, this.size[0]*this.scale, this.size[1]*this.scale);
	},
	save: function() {
		if(this.dom)
			window.open(this.dom[0].toDataURL() , "_blank");
	}
};

var props = {
	fieldSizes: [
		[200, 200, 1], [400, 400, 1], [750, 525, 1], [1500, 1050, 0.5]
	]
};

$(function() {
	canvas.init();
	
	$("#start").click(function() {
		mars.simulate({ robots:[], robotNum:$("#robotNum").val()*1, robotView:$("#robotView").val()*1, distribution:$("#distribution").slider("value")*1, fallDuration:$("#fallDuration").val()*1 });
	});
	
	$("#stop").click(function() {
		canvas.clearField();
		mars.stopSimulation();
	});
	
	var tPos = { my:"left top+2", at:"left bottom", collision: "flipfit" };
	
	$("#robotNum").bind("keyup change", function() {
		if($(this).val()*1 > 200)
			$(this).val(200);
	}).tooltip({ position:tPos });
	$("#distribution").slider({
		animate:true, value:0.5, min:0, max:1, step:0.1, range:"min"
	}).tooltip({ position:tPos });
	
	function fallString(d) {
		var s = "";
		if(!isNaN(d) && d)
			s = " <b>Flugh&ouml;he = "+(Math.round(0.5*3.71*d*d))+" m.</b>";
		return "Zeit zwischen Abwurf eines Roboters und seiner Landung."+s;
	}
	
	$("#fallDuration").bind("keyup change", function() {
		var s = fallString($(this).val()*1);
		$(this).tooltip("option", "content", s);
		$(this).attr("title", s);
	}).tooltip({ content:fallString(5), position:tPos});
	
	$("#fieldSize").change(function() {
		var size = props.fieldSizes[$(this).val()*1];
		canvas.resize(size[0], size[1], size[2]);
	});
	
	$("#speed").slider({
		animate:true, value:850, min:-50, max:950, step:50, range:"min", change:function() {
			mars.speed = 1000-($(this).slider("value")*1);
			mars.startInterval();
		}
	});
	
	$("input[name=showMovements]").change(function() {
		mars.viewMode = $(this).val()*1;
		if(mars.running)
			canvas.clearField();
	});
	
	$(document).keydown(function(e) {
		if(!e.altKey)
			return;
		if(String.fromCharCode(e.keyCode) == "S")
			canvas.save();
		else if(String.fromCharCode(e.keyCode) == "P")
			mars.toggleInterval();
	});
});