import {Controller} from '@hotwired/stimulus';
import axios from 'axios'

export default class extends Controller {

    static values = {
        path: String
    }

    static targets = [
        'messageTextarea',
        'locationBtn'
    ]

    connect() {
    }

    getLocation(event) {
        event.preventDefault();
        if (window.navigator.geolocation) {
            this.locationBtnTarget.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            window.navigator.geolocation
                .getCurrentPosition((data) => {
                    axios.get(this.pathValue, {
                        params: {
                            longitude: data.coords.longitude,
                            latitude: data.coords.latitude
                        }
                    }).then((response) => {
                        this.messageTextareaTarget.value += '///'+response.data.words
                    }).finally(() => {
                        this.locationBtnTarget.innerHTML = '///'
                    })
                }, () => {
                    alert('location unavailable')
                });
        }
    }

}
