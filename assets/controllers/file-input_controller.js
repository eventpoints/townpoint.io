import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['file']

    connect() {
        this.fileTarget.style.display = 'none';
    }

    select(){
        this.fileTarget.click();
    }
}
