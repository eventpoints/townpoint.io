import {Controller} from '@hotwired/stimulus';
import axios from 'axios';

export default class extends Controller {

    static values = {
        path: String,
        isValid: {
            type: Boolean,
            default: false
        }
    }

    static targets = ['input']

    async isValid(value) {

        let isUserNameValid = this.isUserNameValid(this.inputTarget.value)
        console.log(isUserNameValid)
        if(!isUserNameValid){
            this.inputTarget.classList.add('is-invalid')
            this.inputTarget.classList.remove('is-valid')
        }else{
            await axios.post(this.pathValue, {
                value: value
            }).then((response) => {
                console.log(response.data.isAvailable)
                if (response.data.isAvailable === true) {
                    this.inputTarget.classList.add('is-valid')
                    this.inputTarget.classList.remove('is-invalid')
                } else {
                    this.inputTarget.classList.add('is-invalid')
                    this.inputTarget.classList.remove('is-valid')
                }
            })
        }
    }

    isUserNameValid(username) {
        /*
          Usernames can only have:
          - Lowercase Letters (a-z)
          - Numbers (0-9)
          - Dots (.)
          - Underscores (_)
        */
        const res = /^[a-z0-9.]{3,15}$/.exec(username);
        const valid = !!res;
        return valid;
    }

    inputChange() {
            this.isValid(this.inputTarget.value)
    }

}
