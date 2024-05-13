import './bootstrap.js';
import 'htmx.org';
import './styles/app.scss';
import 'bootstrap';
import {Tooltip} from 'bootstrap';

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

    console.log(tooltipTriggerList.length)

    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl)
    });