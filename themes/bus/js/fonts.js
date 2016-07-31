var endy = endy || {};
endy.fonts = {
	ffBuild: function () {
		var n = [{name: "Decima", url: "/themes/bus/fonts/dnp.woff2", options: {weight: "normal"}}, {
			name: "Decima",
			url: "/themes/bus/fonts/dnpbold.woff2",
			options: {weight: "bold"}
		}], e = [];
		for (var t in n)e[t] = new FontFace(n[t].name, "url(" + n[t].url + ")", n[t].options), document.fonts.add(e[t]);
		document.fonts.ready.then(function () {
			endy.fonts.ready()
		})
	}, ready: function () {
		0 == document.querySelectorAll("body").length ? document.addEventListener("DOMContentLoaded", function () {
			endy.fonts.readyActions()
		}) : endy.fonts.readyActions()
	}, readyActions: function () {
		document.querySelector("body").classList.add("fonts")
	}, init: function () {
		"function" == typeof FontFace ? endy.fonts.ffBuild() : document.addEventListener("DOMContentLoaded", function () {
			var n = document.createElement("link");
			n.setAttribute("href", "/themes/bus/css/fonts.css"), n.setAttribute("rel", "stylesheet"), document.querySelector("body").appendChild(n), endy.fonts.readyActions()
		})
	}
}, endy.fonts.init();