/**
 * Created by Александр on 07.06.2015.
 */


$(function () {

	var setProfile = function (wrap, id) {
		if (id == 'new') {
			wrap.find('input').val('');
			wrap.find('select').val('none');
		} else {
			var wrapIndex = wrap.index();
			$.each(window.profiles[id], function (index, value) {
				var input = wrap.find('input[name="Profiles[' + wrapIndex + '][' + index + ']"]');
				if (input.length > 0) {
					input.val(value);
				}

				var select = wrap.find('select[name="Profiles[' + wrapIndex + '][' + index + ']"]');
				if (select.length > 0) {
					console.log(select);
					console.log(select.find('option:contains("' + value + '")'));
					var val = select.find('option:contains("' + value + '")').attr('value');
					select.val(val);
				}
				if (index == 'passport') {
					phonekey_onload(input);
				}
			});
		}
	};

	$('#checkout-profiles-wrapper #profiles select.select-profile').on('change', function () {
		var val = $(this).val(),
			wrap = $(this).parents('.profile-item');
		setProfile(wrap, val);
	});

	$('#UserInterface_models_Checkout_places input[value^="not-"]').attr('disabled', 'disabled');


});