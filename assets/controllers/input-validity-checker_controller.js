import { Controller } from '@hotwired/stimulus';
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
         await axios.post(this.pathValue, {
             value: value
         }).then((response) => {
             console.log(response.data.isAvailable)
             if(response.data.isAvailable === true){
                 this.inputTarget.classList.add('is-valid')
                 this.inputTarget.classList.remove('is-invalid')
             }else{
                 this.inputTarget.classList.add('is-invalid')
                 this.inputTarget.classList.remove('is-valid')
             }
         })
     }

    inputChange(){
        this.isValid(this.inputTarget.value)
    }

}
