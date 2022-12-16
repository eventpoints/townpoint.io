import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {

            console.log('CONNECTED!')

            // this.element.style.backgroundImage = 'linear-gradient('+window.scrollY+'deg,#33b7e2,#5e62b0,#dc307c)'
    }

    scrolling(e){
        console.log(e)
    }
}


