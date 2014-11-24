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


	$('.create-ticket, .edit-ticket').on('click', function (e) {
		e.preventDefault();
		var self = $(this),
			href = self.attr('href'),
			tripId = self.data('tripid'),
			placeId = href.split('/')[4];

		$.ajax({
			type: "POST",
			url: "/trips/inline",
			data: {tripId: tripId, placeId: placeId}
		})
			.done(function (data) {
				var i = 0;
				self.parents('tr').find('td').each(function (e, j) {
					var index = $(this).index();
					if (index != 0 && data.inputs[i] != undefined) {
						$(this).html(data.inputs[i]);
						i++;
					}
				});

				var last_col = self.parents('tr').find('td.button-column');
				last_col.find('a').hide();
				last_col.append('<div class="save-line" data-tripid="' + tripId + '" data-placeid="' + placeId + '">Сохранить</div><div class="cancel-line" data-tripid="' + tripId + '" data-placeid="' + placeId + '">Отменить</div>');
			});
	});

	$('.save-line').live('click', function () {
		$(this).parent('td').find('a').show();
		$(this).remove();
		console.log('save!')
	});

	$('.cancel-line').live('click', function () {
		var self = $(this),
			tripId = self.data('tripid'),
			placeId = self.data('placeid');

		$.ajax({
			type: "POST",
			url: "/trips/inline",
			data: {tripId: tripId, placeId: placeId}
		})
			.done(function (data) {
				var i = 0;
				self.parents('tr').find('td').each(function (e, j) {
					var index = $(this).index();
					if (index != 0 && data.inline[i] != undefined) {
						$(this).html(data.inline[i]);
						i++;
					}
				});

				self.parent('td').find('a').show();
				self.parent('td').find('.save-line, .cancel-line').remove();
			});
	});


});

