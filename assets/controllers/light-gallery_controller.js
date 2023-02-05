import { Controller } from '@hotwired/stimulus';
import lightGallery  from 'lightgallery';

export default class extends Controller {
    connect() {
        const inlineGallery = lightGallery(this.element, {
            container: this.element,
            dynamic: true,
            // Turn off hash plugin in case if you are using it
            // as we don't want to change the url on slide change
            hash: false,
            // Do not allow users to close the gallery
            closable: false,
            // Add maximize icon to enlarge the gallery
            showMaximizeIcon: true,
            // Append caption inside the slide item
            // to apply some animation for the captions (Optional)
            appendSubHtmlTo: '.lg-item',
            // Delay slide transition to complete captions animations
            // before navigating to different slides (Optional)
            // You can find caption animation demo on the captions demo page
            slideDelay: 400,
        });

// Since we are using dynamic mode, we need to programmatically open lightGallery
        inlineGallery.openGallery();
    }
}
