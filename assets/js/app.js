import $ from 'jquery';
import Foundation from 'foundation-sites';
import fontawesome from '@fortawesome/fontawesome';
import faHeart from '@fortawesome/fontawesome-free-solid/faHeart';

// Setup jQuery
window.$ = $;

// Setup Foundation
Foundation.addToJquery($);

// Setup FontAwesome
fontawesome.library.add(faHeart);

// Start
$(document).ready(function () {
    $(document).foundation();
});
