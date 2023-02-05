import { Controller } from '@hotwired/stimulus';
import Swiper from 'swiper/bundle';

export default class extends Controller {

    static targets = [ 'mainSwiper', 'thumbnailSwiper']

    connect() {
        var thumb = new Swiper(this.thumbnailSwiperTarget, {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        new Swiper(this.mainSwiperTarget, {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: thumb,
            },
        });
    }
}
