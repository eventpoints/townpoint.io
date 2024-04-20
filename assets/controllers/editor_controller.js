import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["editor", "viewer", "output"]; // Define both editor and viewer targets

    connect() {
        this.element.addEventListener("input", () => this.updateViewer());
    }

    updateViewer() {
        this.outputTarget.value = this.editorTarget.innerHTML;
    }

    toggleBold() {
        document.execCommand("bold", false, null);
    }

    toggleItalic() {
        document.execCommand("italic", false, null);
    }

    toggleUnderline() {
        document.execCommand("underline", false, null);
    }

    addUrl() {
        const url = prompt("Enter URL:");
        if (url) {
            document.execCommand("createLink", false, url);
        }
    }

    createUnorderedList() {
        document.execCommand("insertUnorderedList", false, null);
    }

    createOrderedList() {
        document.execCommand("insertOrderedList", false, null);
    }

    insertCodeBlock() {
        document.execCommand("insertHTML", false, "<pre><code>Your code here</code></pre>");
    }

    toggleH3() {
        document.execCommand("formatBlock", false, "<h3>");
    }
}
