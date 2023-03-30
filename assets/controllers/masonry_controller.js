import { Controller } from '@hotwired/stimulus';
import Masonry from 'masonry-layout'

export default class extends Controller {
    connect() {
        const masonry = new Masonry( this.element, {
            columnWidth: '.grid-sizer',
            itemSelector: '.grid-item',
            percentPosition: true,
            gutter: 20
        });
    }
}
