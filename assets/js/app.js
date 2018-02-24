import $ from 'jquery';
import { Foundation } from 'foundation-sites/js/foundation.core';

window.$ = $;
Foundation.addToJquery($);

$(document).ready(function () {
    $(document).foundation();
});
