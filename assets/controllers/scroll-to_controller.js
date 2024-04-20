import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.scrollToElement();
    }

    scrollToElement() {
        document.addEventListener('htmx:load', function (event) {
            const hash = window.location.hash;

            if (hash) {
                const id = hash.substring(1);
                let el = document.getElementById(id)
                el.scrollIntoView()
                el.classList.add('shadow')
            }
        });
    }

}