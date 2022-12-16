import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['input', 'output']

    static values = {
        regex: {
            type: String,
            default: '@[a-zA-Z0-9]{0,20}'
        },
        path: String
    }

    connect() {
        this.inputTarget.addEventListener('keyup', (event) => {

            let keyword = event.target.value

            const handles = [
                'john3828',
                'kez',
                'martin3290',
                'mar90',
                'mar9120',
                'sheyla900',
                'margret832',
                'kerrial328',
                'shell832',
                'shyla27197'
            ]

            let filteredHandles = this.getMatches(keyword, handles)
            this.renderAutocomplete(keyword, filteredHandles)
        })
    }

    getMatches(wordToMatch, handles) {
        return handles.filter((handle) => {
            let atHandle = '@' + handle
            const regex = RegExp(wordToMatch, 'gi');
            return atHandle.match(regex)
        });
    }

    renderAutocomplete(keyword, handles) {
        this.outputTarget.innerHTML = handles.map((handle) => {
            let atHandle = '@' + handle
            const regex = RegExp(keyword, "gi");
            let matchedHandle = atHandle.replace(regex, `<span class='text-primary'>${keyword}</span>`);
            return `<div data-id="${handle}" class="list-group-item list-group-item-action handle-item">${matchedHandle}</div>`
        }).join('');

        let options = document.querySelectorAll('.handle-item')

        options.forEach((option) => {
            option.addEventListener('click', (event) => {
                this.inputTarget.value += event.target.getAttribute('data-id');
            });
        })


    }
}
