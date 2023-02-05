import Trix from "trix"

import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['editor', 'textarea']

    connect() {
        console.log(this.textareaTarget, this.editorTarget)
    }
}
