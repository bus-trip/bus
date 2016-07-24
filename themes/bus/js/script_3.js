for (var i in endy.run_) {
	var item = endy.run_[i];
	if (typeof item === 'object') {
		item.init.bind(item)();
	}
}