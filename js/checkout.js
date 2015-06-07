/**
 * Created by Александр on 07.06.2015.
 */


$(function () {

	var removeItem = function () {
		$('#checkout-profiles-wrapper #profiles .del').on('click', function () {
			$(this).parent('.profile-item').remove();
		});
	};

	removeItem();

	$('#add-profile').on('click', function () {
		var wrap = $('#checkout-profiles-wrapper #profiles');
		$.getJSON(window.params.ajaxForm,
			{
				i: wrap.find('.profile-item').length
			},
			function (data, status, xhr) {
				wrap.append('<div class="profile-item">' + data.form + '<div class="del">Удалить</div></div>');
				removeItem();
			});
	});


});