import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['fields', 'field', 'addButton', 'submitButton']
    static values = {
        prototype: String,
        minItems: Number,
        maxItems: Number,
        itemsCount: Number,
    }

    connect() {
        const isUnderMinOptions = this.itemsCountValue < this.minItemsValue
        this.submitButtonTarget.classList.toggle('disabled', isUnderMinOptions)

        this.index = this.itemsCountValue = this.fieldTargets.length
    }

    addItem() {
        let prototype = JSON.parse(this.prototypeValue)
        const newField = prototype.replace(/__name__/g, this.index)
        this.fieldsTarget.insertAdjacentHTML('beforeend', newField)
        this.index++
        this.itemsCountValue++
    }

    removeItem(event) {
        this.fieldTargets.forEach(element => {
            if (element.contains(event.target)) {
                element.remove()
                this.itemsCountValue--
            }
        })
    }

    itemsCountValueChanged() {
        const isUnderMinOptions = this.itemsCountValue < this.minItemsValue
        console.log(isUnderMinOptions)
        this.submitButtonTarget.classList.toggle('disabled', isUnderMinOptions)

        if (false === this.hasAddButtonTarget || 0 === this.maxItemsValue) {
            return
        }


        const maxItemsReached = this.itemsCountValue >= this.maxItemsValue
        this.addButtonTarget.classList.toggle('disabled', maxItemsReached)
    }

}