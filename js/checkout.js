/**
 * Created by Александр on 07.06.2015.
 */


$(function () {

	var removeItem = function () {
		$('#checkout-profiles-wrapper #profiles .del').on('click', function () {
			$(this).parent('.profile-item').remove();
		});
	};

	var initCheckout = function(wrap){
		var code_number_wrap = wrap.find('.code_number');
		if (code_number_wrap.length > 0) {
			phonekey_onload(code_number_wrap);
		}

		wrap.find('.code, .number').on('keyup', phonekey);
		wrap.find('.phone').mask("+9 (999) 999-9999");

		wrap.find('.datapiker-wrap input').datepicker($.extend({showMonthAfterYear:false},$.datepicker.regional['ru'],{'altFormat':'d.m.Y'}));
	};

	removeItem();

	$('#add-profile').on('click', function () {
		var wrap = $('#checkout-profiles-wrapper #profiles');
		$.getJSON(window.params.ajaxForm,
			{
				i: wrap.find('.profile-item').length
			},
			function (data, status, xhr) {
				var item_wrap = $('<div class="profile-item"></div>');
				item_wrap.html(data.form + '<div class="del">Удалить</div>');
				wrap.append(item_wrap);
				removeItem();
				initCheckout(item_wrap)
			});
	});


});