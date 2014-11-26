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


function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

jQuery.expr[':'].regex = function (elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ?
                matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels, '')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g, ''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
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
            data: {tripId: tripId, placeId: placeId},
            beforeSend: function () {
                $('body').append('<div id="overlay-loading"><div id="loader"></div></div>');
            }
        }).done(function (data) {
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
            var op = (self.hasClass('create-ticket')) ? 'add' : 'up';
            last_col.append('<div class="save-line" data-tripid="' + tripId + '" data-placeid="' + placeId + '" data-op="' + op + '">Сохранить</div><div class="cancel-line" data-tripid="' + tripId + '" data-placeid="' + placeId + '">Отменить</div>');

            self.parents('tr').find('input[name="Profiles\[phone\]"]').mask("+9 (999) 999-9999");

            var cache = {};
            $('input.autocomplete').each(function () {
                var input = $(this),
                    line_wrapp = $(this).parents('tr'),
                    field = input.attr('name');
                $(this).autocomplete({
                    //minLength: 2,
                    source: function (request, response) {
                        request.field = field;
                        var term = request.term;
                        if (term in cache) {
                            response(cache[term]);
                            return;
                        }

                        $.getJSON("/trips/sprofiles", request, function (data, status, xhr) {
                            cache[term] = data;
                            response(data);
                        });
                    },
                    select: function (event, ui) {
                        line_wrapp.find('input.autocomplete').each(function () {
                            var input_name = $(this).attr('name');
                            $(this).val(ui.item.data[input_name]);
                        })
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li class='ui-menu-item'></li>")
                        .data("item.autocomplete", item)
                        .append('<a><b>' + item.value + '</b> ... ' + item.info + '</a>')
                        .appendTo(ul);
                };
            });


            $('#overlay-loading').remove();
        });
    });

    $('.save-line').live('click', function () {
        var self = $(this),
            form_data = {},
            op = self.data('op'),
            tripId = self.data('tripid'),
            placeId = self.data('placeid'),
            form_inputs = self.parents('tr').find('input,textarea');

        form_inputs.removeClass('error').each(function () {
            var val = $(this).val(),
                name = $(this).attr('name');
            form_data[name] = val;
        });

        var input_data = {
            data: form_data,
            tripId: tripId,
            placeId: placeId
        };

        $.ajax({
            type: "POST",
            url: "/trips/inline",
            data: input_data,
            beforeSend: function () {
                $('body').append('<div id="overlay-loading"><div id="loader"></div></div>');
            }
        }).done(function (data) {
            if (!isEmpty(data.errors)) {
                $.each(data.errors, function (index, value) {
                    alert(value.join("\n"));
                    form_inputs.filter(":regex(name,\\[" + index + "\\])").addClass('error');
                });
            } else {
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

                if (op == 'add') {
                    location.reload();
                    return false;
                }
            }

            $('#overlay-loading').remove();
        });
    });

    $('.cancel-line').live('click', function () {
        var self = $(this),
            tripId = self.data('tripid'),
            placeId = self.data('placeid');

        $.ajax({
            type: "POST",
            url: "/trips/inline",
            data: {tripId: tripId, placeId: placeId},
            beforeSend: function () {
                $('body').append('<div id="overlay-loading"><div id="loader"></div></div>');
            }
        }).done(function (data) {
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

            $('#overlay-loading').remove();
        });
    });


})
;

