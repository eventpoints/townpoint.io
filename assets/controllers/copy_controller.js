import Clipboard from 'stimulus-clipboard'
import src_default from "stimulus-clipboard";

export default class extends Clipboard {

    static targets = ["button", "source"];
    static values = {
        successDuration: {
            type: Number,
            default: 2e3
        }
    }

    connect() {
        if (!this.hasButtonTarget)
            return;
        this.originalContent = this.buttonTarget.innerHTML;
    }

    copy(event) {
        event.preventDefault();
        const text = this.sourceTarget.innerHTML || this.sourceTarget.value;
        navigator.clipboard.writeText(text).then(() => this.copied());
    }

    copied() {
        if (!this.hasButtonTarget)
            return;
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
        this.buttonTarget.innerHTML = this.data.get("successContent");
        this.timeout = setTimeout(() => {
            this.buttonTarget.innerHTML = this.originalContent;
        }, this.successDurationValue);
    }
}