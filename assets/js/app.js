import $ from 'jquery';
import Foundation from 'foundation-sites';
import fontawesome from '@fortawesome/fontawesome';
import faCheck from '@fortawesome/fontawesome-free-solid/faCheck';
import faEdit from '@fortawesome/fontawesome-free-solid/faEdit';
import faHeart from '@fortawesome/fontawesome-free-solid/faHeart';
import faQuestion from '@fortawesome/fontawesome-free-solid/faQuestion';
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
    faHeart,
    faQuestion,
    faTimes,
    faTrash,
);

// Start
$(document).ready(function () {
    $(document).foundation();
});
