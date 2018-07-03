import $ from 'jquery';
import Foundation from 'foundation-sites';
import fontawesome from '@fortawesome/fontawesome';
import faCheck from '@fortawesome/fontawesome-free-solid/faCheck';
import faEdit from '@fortawesome/fontawesome-free-solid/faEdit';
import faEnvelope from '@fortawesome/fontawesome-free-solid/faEnvelope';
import faHeart from '@fortawesome/fontawesome-free-solid/faHeart';
import faQuestion from '@fortawesome/fontawesome-free-solid/faQuestion';
import faThumbsDown from '@fortawesome/fontawesome-free-solid/faThumbsDown';
import faThumbsUp from '@fortawesome/fontawesome-free-solid/faThumbsUp';
import faTimes from '@fortawesome/fontawesome-free-solid/faTimes';
import faTrash from '@fortawesome/fontawesome-free-solid/faTrash';

// Setup jQuery
window.$ = $;

// Setup Foundation
Foundation.addToJquery($);

// Setup FontAwesome
fontawesome.library.add(
    faCheck,
    faEdit,
    faEnvelope,
    faHeart,
    faQuestion,
    faThumbsDown,
    faThumbsUp,
    faTimes,
    faTrash,
);

// Start
$(document).ready(function () {
    $(document).foundation();
});

// Managing guests
var $guestModal = $('#guest-modal');

$('.guest-add, .guest-edit').on('click', function (event) {
    event.preventDefault();

    $.ajax($(this).attr('href'))
        .done(function (html) {
            $guestModal.html($(html).find('#content'));
            $guestModal.foundation('open');
        });
});

// Managing songs
var $songModal = $('#song-modal');

$('.song-add, .song-edit').on('click', function (event) {
    event.preventDefault();

    $.ajax($(this).attr('href'))
        .done(function (html) {
            $songModal.html($(html).find('#content'));
            $songModal.foundation('open');
        });
});

var ceremonyData = [{
        position: {
            lat: 51.688442,
            lng: 5.303108,
        },
        title: 'Stadhuis',
        open: true,
    }, {
        position: {
            lat: 51.687654,
            lng: 5.307381,
        },
        title: '\'t Pumpke',
        open: true,
    }, {
        position: {
            lat: 51.685242,
            lng: 5.314218,
        },
        title: 'Parkeergarage St-Jan',
        open: false,
    }, {
        position: {
            lat: 51.691440,
            lng: 5.304047,
        },
        title: 'Parkeergarage Arena',
        open: false,
    }, {
        position: {
            lat: 51.686721,
            lng: 5.303237,
        },
        title: 'Parkeergarage Wolvenhoek',
        open: false,
    }, {
        position: {
            lat: 51.690556,
            lng: 5.293543,
        },
        title: 'Station \'s-Hertogenbosch Centraal',
        open: true,
}];

var partyData = [{
        position: {
            lat: 51.696302,
            lng: 5.299841,
        },
        title: 'Koudijs Lokaal',
        open: true,
    }, {
        position: {
            lat: 51.690556,
            lng: 5.293543,
        },
        title: 'Station \'s-Hertogenbosch Centraal',
        open: true,
}];

function initMap(data) {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: {
            lat: 51.688442,
            lng: 5.303108,
        },
    });

    var bounds = new google.maps.LatLngBounds();

    $.each(data, function (i, point) {
        var marker = new google.maps.Marker({
            position: point.position,
            map: map,
        });

        var label = new google.maps.InfoWindow({
            content: point.title,
        });

        marker.addListener('click', function () {
            label.open(map, marker);
        });

        if (point.open) {
            label.open(map, marker);
        }

        bounds.extend(marker.getPosition());
    });

    map.fitBounds(bounds);
}

// Map for the ceremony page
initCeremonyMap = window.initCeremonyMap = function () {
    initMap(ceremonyData);
}

// Map for the party page
initPartyMap = window.initPartyMap = function () {
    initMap(partyData);
}
