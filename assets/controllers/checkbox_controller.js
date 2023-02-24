import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['input', 'img']

    connect() {
        this.isChecked = false

        this.imgTarget.addEventListener('mouseover', (event) => {
            if (!this.isChecked) {
                this.inputTarget.classList.toggle('visually-hidden')
                event.target.classList.toggle('visually-hidden')
            }
        })

        this.inputTarget.addEventListener('mouseleave', (event) => {
            if (!this.isChecked) {
                this.imgTarget.classList.toggle('visually-hidden')
                event.target.classList.toggle('visually-hidden')
            }
        })
    }

    check(event) {
        event.target.classList.toggle('mdi-circle-outline')
        event.target.classList.toggle('mdi-check-circle')
        event.target.classList.toggle('text-primary')
    }
}
