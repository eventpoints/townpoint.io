import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        timezone: String
    }

    connect() {
        if (!this.value.timezone) {
            return Intl.DateTimeFormat().resolvedOptions().timeZone;
        }

        return this.value.timezone
    }
}
