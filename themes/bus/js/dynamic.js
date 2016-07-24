var endy = endy || {};
endy.dynamic = {
	el: {svg: null}, config: {pathSvg: "../svg/"}, callback_: [], isBuild: !1, loadCache: function (n) {
		return localStorage["dynamic-" + n]
	}, loadXhr: function (n, a, e) {
		var d = "onload" in new XMLHttpRequest ? XMLHttpRequest : XDomainRequest, c = new d;
		c.open("GET", n, !0), c.send(), e && endy.dynamic.visible(), c.onreadystatechange = function () {
			4 !== c.readyState && 3 !== c.readyState || 200 !== c.status || (endy.dynamic.buildBlock(a, c.responseText), localStorage["dynamic-" + a] = c.responseText, e && (endy.dynamic.visible(), endy.dynamic.isBuild = !0, endy.dynamic.callbackRun()))
		}
	}, visible: function () {
		document.querySelector("body").classList.add("load")
	}, callbackRun: function () {
		for (var n in endy.dynamic.callback_)endy.dynamic.callback_[n]()
	}, callback: function (n) {
		endy.dynamic.callback_.push(n), endy.dynamic.isBuild && endy.dynamic.callbackRun()
	}, build: function () {
		0 == endy.dynamic.el.svg.length && endy.dynamic.visible(), [].forEach.call(endy.dynamic.el.svg, function (n, a) {
			var e = n.getAttribute("data-dynamic"), d = n.getAttribute("data-dynamic-path"), c = endy.dynamic.loadCache(e), i = endy.dynamic.el.svg.length, l = i - 1 === a;
			d = d || endy.dynamic.config.pathSvg, c ? endy.dynamic.buildBlock(e, c, l) : endy.dynamic.loadXhr(d + e + ".svg", e, l)
		})
	}, buildBlock: function (n, a, e) {
		e && (endy.dynamic.visible(), endy.dynamic.isBuild = !0, endy.dynamic.callbackRun()), [].forEach.call(document.querySelectorAll('[data-dynamic="' + n + '"]'), function (n) {
			var e = n.getAttribute("data-mod");
			if (n.innerHTML = a, e) {
				e = e.split(" ");
				var d = n.getElementsByTagName("svg")[0], c = d.attributes["class"].nodeValue, i = [c];
				for (var l in e)i.push(c + "_" + e[l]);
				d.attributes["class"].nodeValue = i.join(" ")
			}
		})
	}, getEl: function () {
		endy.dynamic.el.svg = document.querySelectorAll("[data-dynamic]")
	}, update: function () {
		endy.dynamic.getEl(), endy.dynamic.build()
	}
};