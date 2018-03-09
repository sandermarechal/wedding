import $ from 'jquery';
import { Foundation } from 'foundation-sites/js/foundation.core';
import fontawesome from '@fortawesome/fontawesome';
import faHeart from '@fortawesome/fontawesome-free-solid/faHeart';

window.$ = $;
Foundation.addToJquery($);

fontawesome.library.add(faHeart);

$(document).ready(function () {
    $(document).foundation();
});
