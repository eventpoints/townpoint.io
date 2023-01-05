import {Controller} from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { TimePlugin } from '@easepick/time-plugin';

export default class extends Controller {

    static targets = ['picker']

    connect() {
        new easepick.create({
            element: this.pickerTarget,
            css: [
                "https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css"
            ],
            zIndex: 10,
            format: "YYYY-MM-DD HH:mm",
            readonly: false,
            plugins: [
                TimePlugin
            ]
        })
    }
}
