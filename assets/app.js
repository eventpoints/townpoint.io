import {Tab} from 'bootstrap'
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

document.addEventListener('DOMContentLoaded', () => {

    let tabs = document.querySelectorAll('a[role="tab"]')

    tabs.forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            console.log(event.target.getAttribute('href'))
            history.pushState("object or string", "Page title", event.target.getAttribute('href'));
        })
    })

    let hash = window.location.hash;
    let element = document.querySelector('a[href="' + hash + '"]');
    let tab = new Tab(element);
    tab.show();
});