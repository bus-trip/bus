
var phonekey = function () {
	var parent = $(this).parent(),
		code = parent.find('.code').val(),
		number = parent.find('.number').val(),
		code_number = (code + number).replace(/\D/g, '');

	console.log(code_number);

	parent.find('.code_number').val(code_number).trigger('change');
}

$(function() {

	$('.code, .number').on('keyup', phonekey);

});

