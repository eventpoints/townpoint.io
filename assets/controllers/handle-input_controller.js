import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['input', 'list']
    static values = {
        path: String
    }

    connect() {
        this.inputTarget.addEventListener('keyup', (event) => {
            if(event.keyCode === 50){
                this.listTarget.innerHTML = '<turbo-frame id="handle-select-frame" src="'+this.pathValue+'" ></turbo-frame>\n'
            }
        });
    }
}
