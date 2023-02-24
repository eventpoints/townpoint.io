import {Controller} from '@hotwired/stimulus';
import anime from "animejs";

export default class extends Controller {
    static targets = ['checkbox', 'toolbar', 'checkAllBtn']

    connect() {
        this.isAllChecked = false
        this.isToolbarVisable = false
        this.checked = []
    }

    checkAll(event) {
        if (this.isAllChecked) {
            this.isAllChecked = false
            event.target.classList.replace('mdi-check-circle', 'mdi-circle-outline')
            event.target.classList.toggle('text-primary')

            if (this.checked.length > 1) {
                this.hideToolBar()
            }

            for (let checkbox of this.checkboxTargets) {
                let id = checkbox.getAttribute('data-id')
                if (id !== undefined) {
                    let image = document.querySelector(`#image-${id}`)
                    let checkbox = document.querySelector(`#checkbox-${id}`)
                    image.classList.remove('visually-hidden')
                    checkbox.classList.add('visually-hidden')
                    checkbox.classList.replace('mdi-check-circle', 'mdi-circle-outline')
                    checkbox.classList.remove('text-primary')
                    this.checked = this.checked.filter((checkId) => {
                        return checkId !== id;
                    })
                }
            }
        } else {
            this.isAllChecked = true
            event.target.classList.replace('mdi-circle-outline', 'mdi-check-circle')
            event.target.classList.toggle('text-primary')

            this.checked = []
            if (this.checked.length < 1) {
                this.showToolBar()
            }

            for (let checkbox of this.checkboxTargets) {
                let id = checkbox.getAttribute('data-id')

                if (id !== undefined) {
                    this.checked.push(id)
                    let image = document.querySelector(`#image-${id}`)
                    let checkbox = document.querySelector(`#checkbox-${id}`)
                    image.classList.add('visually-hidden')
                    checkbox.classList.remove('visually-hidden')
                    checkbox.classList.replace('mdi-circle-outline', 'mdi-check-circle')
                    checkbox.classList.add('text-primary')
                }
            }
        }
        console.log(this.checked)
    }

    check(event) {
        if (!this.checked.includes(event.params.id)) {

            this.checked.push(event.params.id)
            event.target.classList.replace('mdi-circle-outline', 'mdi-check-circle')
            event.target.classList.add('text-primary')
            this.showToolBar()
        } else {
            this.checked = this.checked.filter((id) => {
                return event.params.id !== id
            })
            this.uncheck(event)
        }

        if (this.checked.length === this.checkboxTargets.length) {
            this.isAllChecked = true
            this.checkAllBtnTarget.classList.replace('mdi-circle-outline', 'mdi-check-circle')
            this.checkAllBtnTarget.classList.add('text-primary')
        }else{
            this.isAllChecked = false
            this.checkAllBtnTarget.classList.replace('mdi-check-circle','mdi-circle-outline',  )
            this.checkAllBtnTarget.classList.remove('text-primary')
        }
    }

    uncheck(event) {
        if (this.checked.length < 1) {
            this.hideToolBar()
        }

        event.target.classList.replace('mdi-check-circle', 'mdi-circle-outline')
        event.target.classList.remove('text-primary')
    }

    showToolBar() {
        if (this.isToolbarVisable) {
            anime({
                targets: this.toolbarTarget,
                keyframes: [{scale: 1}, {scale: 1.05}, {scale: 1}],
                easing: 'easeOutElastic(1, .8)',
                opacity: 1,
                duration: 300
            });
        } else {
            this.isToolbarVisable = true
            anime({
                targets: this.toolbarTarget,
                translateY: [-25, 0],
                opacity: [0.5, 1],
                easing: 'easeOutElastic(1, .8)',
                duration: 300
            });
            this.toolbarTarget.classList.remove('visually-hidden')
        }
    }

    hideToolBar() {
        this.isToolbarVisable = false
        this.toolbarTarget.classList.add('visually-hidden')
    }

    mouseover(event) {
        let id = event.params.id
        let image = document.querySelector(`#image-${id}`)
        let checkbox = document.querySelector(`#checkbox-${id}`)
        image.classList.add('visually-hidden')
        checkbox.classList.remove('visually-hidden')
    }

    mouseleave(event) {
        if (!this.checked.includes(event.params.id)) {
            let id = event.params.id
            let image = document.querySelector(`#image-${id}`)
            let checkbox = document.querySelector(`#checkbox-${id}`)
            image.classList.remove('visually-hidden')
            checkbox.classList.add('visually-hidden')
        }
    }

}
