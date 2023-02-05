import {Tab, Tooltip} from 'bootstrap'
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';


document.addEventListener('turbo:load', function (e) {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl)
    })
})

document.addEventListener('turbo:load', function (e) {
    $("a[role='tab']").on("click", function (event) {
        event.preventDefault();
        history.pushState("object or string", "Page title", $(this).attr("href"));
    });

    let selectedTab = window.location.hash;
    console.log(selectedTab)
    let tabTrigger = new Tab($('.nav-link[href="' + selectedTab + '"]:first'));
    tabTrigger.show();
});