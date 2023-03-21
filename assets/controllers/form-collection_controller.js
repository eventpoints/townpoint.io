import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer", "current", "counter", 'indicators']

    static values = {
        index: Number,
        prototype: String,
    }

    connect() {
    }

    slide() {
        let current = document.querySelector('.carousel-indicators .active').getAttribute('data-bs-slide-to')

        console.log(parseInt(current)+1)

        this.currentTarget.innerHTML = parseInt(current)+1
    }

    addCollectionElement(event) {
        const item = document.createElement('div');
        item.classList.add('carousel-item')

        const card = document.createElement('div');
        card.classList.add('card')
        card.classList.add('card-body')
        card.classList.add('shadow')
        card.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        item.appendChild(card)

        this.collectionContainerTarget.prepend(item);
        this.indexValue++;
        this.counterTarget.innerHTML = this.indexValue

        let indicator = document.createElement('button')
        indicator.setAttribute('type', 'button')
        indicator.setAttribute('data-bs-target', '#carouselExample')
        indicator.setAttribute('data-bs-slide-to', this.indexValue)
        indicator.setAttribute('data-action', 'click->form-collection#slide')

        this.indicatorsTarget.append(indicator)
    }
}