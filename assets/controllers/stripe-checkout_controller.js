import {Controller} from '@hotwired/stimulus';
import {loadStripe} from '@stripe/stripe-js';

export default class extends Controller {

    static targets = ['cardElement', 'submitBtn', 'stripeToken']

    async connect() {
        this.stripe = await loadStripe("{{ stripe_public_key }}");
        this.elements = this.stripe.elements();
        this.cardElement = this.elements.create('card');
        this.cardElement.mount(this.cardElementTarget);
    }

    getTokenAndSubmitForm(event) {
        event.preventDefault()
        this.submitBtnTarget.disabled = true;
        this.stripe.createToken(this.cardElement).then(function (result) {

            if (typeof result.error != 'undefined') {
                this.submitBtnTarget.disabled = false;
                this.submitBtnTarget.innerHTML = '<div class="d-flex justify-content-center"> <div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span> </div> </div>';
                alert(result.error.message);
            }

            // creating token success
            if (typeof result.token != 'undefined') {
                this.stripeTokenTarget.value = result.token.id;
                this.element.submit();
            }
        });
    }

}
