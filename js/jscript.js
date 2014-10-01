var phonekey = function () {
	var parent = $(this).parent(),
		code = parent.find('.code').val(),
		number = parent.find('.number').val(),
		code_number = (code + number).replace(/\D/g, '');

	parent.find('.code_number').val(code_number).trigger('change');
}
var phonekey_onload = function (el) {
	var val = el.val();
	if (val != '') {
		el.parent().find('.code').val(val.substr(0, 4));
		el.parent().find('.number').val(val.substr(4));
	}
}

var passengers = function () {
	$('#passengers .item .name span').on('click', function () {
		var block = $(this).parents('.item').find('.data');
		if (block.is(':visible')) {
			block.hide();
		} else {
			block.show();
		}
	})
}


$(function () {

	passengers();

	if ($('.code_number').length > 0) {
		phonekey_onload($('.code_number'));
	}

	$('.code, .number').on('keyup', phonekey);
	$('.phone').mask("+9 (999) 999-9999");


	$('.chose-profile').on('change', function () {
		var id = $(this).val(),
			wrap = $(this).parent('.item-profile');

		wrap.find('.profile-data').addClass('hide');
		wrap.find('.profile-data[data-id=' + id + ']').removeClass('hide');
	})
});

