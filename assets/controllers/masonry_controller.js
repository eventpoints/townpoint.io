import { Controller } from '@hotwired/stimulus';
import Masonry from 'masonry-layout'

export default class extends Controller {
    connect() {
        new Masonry( this.element, {
            columnWidth: '.grid-sizer',
            itemSelector: '.gallery-item',
            percentPosition: true,
            gutter: 20
        });
    }
}
