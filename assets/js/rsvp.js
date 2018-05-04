import $ from 'jquery';
import Foundation from 'foundation-sites';

var $modal = $('#guest-modal');

$('.guest-add').on('click', function (event) {
    event.preventDefault();

    $.ajax($(this).attr('href'))
        .done(function (html) {
            $modal.html($(html).find('#content'));
            $modal.foundation('open');
        });
});
