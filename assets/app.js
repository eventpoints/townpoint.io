import './bootstrap.js';
import 'htmx.org';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import './styles/app.scss';
import 'bootstrap';

document.addEventListener("trix-initialize", function(e) {
    const fileTools = document.querySelector(".trix-button-group--file-tools");
    // null check hack: trix-initialize gets called twice for some reason, sometimes it is null :shrug:
    fileTools?.remove();
});
// Dont allow images/etc to be pasted
document.addEventListener("trix-attachment-add", function(event) {
    if (!event.attachment.file) {
        event.attachment.remove()
    }
})
// No files, ever
document.addEventListener("trix-file-accept", function(event) {
    event.preventDefault();
});