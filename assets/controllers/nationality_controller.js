import {Controller} from '@hotwired/stimulus';
import nationalities from "i18n-nationality";

export default class extends Controller {

    static values = {
        country: String
    }

    connect() {
        nationalities.registerLocale(require("i18n-nationality/langs/en.json"));
        this.element.innerHTML = nationalities.getName(this.countryValue, "en");
    }
}
