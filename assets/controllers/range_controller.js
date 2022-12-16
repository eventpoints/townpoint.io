import {Controller} from '@hotwired/stimulus';
import anime from 'animejs';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        let timeControlEl = document.querySelector('.time-control');
        let rullerEl = document.querySelector('.ruller');
        let timeCursorEl = document.querySelector('.time-cursor');
        let timeEl = document.querySelector('.time-cursor input');
        let infoEls = document.querySelectorAll('.info');
        let valueEl = document.querySelectorAll('.value');
        let fragment = document.createDocumentFragment();
        let numberOfElements = 271;
        let controlAnimationCanMove = true;

        for (let i = 0; i < numberOfElements; i++) {
            let dotEl = document.createElement('div');
            dotEl.classList.add('line');
            fragment.appendChild(dotEl);
        }
        rullerEl.appendChild(fragment);
        let animationPXOffset = 0 ;

        function pxToTime(px) {
            let percent = px / window.innerWidth;
            return percent * (timelineAnimation.duration);
        }



        let time = {
            anim: null,
            start: 0,
            end: 0
        };

        let drag = this.dragElement(timeCursorEl, {
            begin: function (e) {
                anime.remove(time);
                time.start = timelineAnimation.currentTime;
                controlAnimationCanMove = false;
            },
            move: function (e) {
                timelineAnimation.seek(time.start + pxToTime(-e.deltaX));
            },
        });

        let timelineAnimation = anime.timeline({
            easing: 'linear',
            autoplay: false
        })
            .add({
                targets: timeCursorEl,
                translateZ: 0,
                keyframes: [
                    {translateX: 1080, duration: 2000},
                ],
                duration: 1500
            })


        let windowHeight = window.innerHeight;
        let scrollAnim;

        function moveControlAnimation() {
            let rect = timeControlEl.getBoundingClientRect();
            let top = rect.top;
            let height = rect.height;
            let scrolled = (top - windowHeight + 100) * -1.5;
            timelineAnimation.seek(scrolled * 2);
            if (controlAnimationCanMove) scrollAnim = requestAnimationFrame(moveControlAnimation);
        }

        this.isElementInViewport(timeControlEl, function (el, entry) {
            windowHeight = window.innerHeight;
            controlAnimationCanMove = true;
            moveControlAnimation();
        }, function (el, entry) {
            controlAnimationCanMove = false;
        }, '50px');

        this.onScroll(function () {
            if (time.anim && !time.anim.paused) {
                time.anim.pause();
                controlAnimationCanMove = true;
                moveControlAnimation();
            }
        });
    }

    // drag Element
    dragElement(el, events) {
        function getPointer(e) {
            var x = 'clientX';
            var y = 'clientY';
            var evt = e.touches ? e.touches[0] : e;
            return { x: evt[x], y: evt[y] };
        }

        var drag = { x: 0, y: 0, deltaX: 0, deltaY: 0, active: true, events: events || {} };
        var originalX = 0;
        var originalY = 0;
        var pointerX = 0;
        var pointerY = 0;

        function move(e) {
            if (drag.active) return;
            drag.deltaX = pointerX - getPointer(e).x;
            drag.deltaY = pointerY - getPointer(e).y;
            drag.x = originalX - drag.deltaX;
            drag.y = originalY - drag.deltaY;
            if (drag.events.move) drag.events.move(drag);
        }

        function release(e) {
            drag.active = true;
            if (drag.events.release) drag.events.release(drag);
            document.removeEventListener('mousemove', move, false);
            document.removeEventListener('mouseup', release, false);
            document.removeEventListener('touchmove', move, false);
            document.removeEventListener('touchend', release, false);
        }

        function start(e) {
            if (!drag.active) return;
            e.preventDefault();
            drag.active = false;
            pointerX = getPointer(e).x;
            pointerY = getPointer(e).y;
            originalX = drag.x;
            originalY = drag.y;
            if (drag.events.begin) drag.events.begin(drag);
            document.addEventListener('mousemove', move, false);
            document.addEventListener('mouseup', release, false);
            document.addEventListener('touchmove', move, false);
            document.addEventListener('touchend', release, false);
        }

        el.addEventListener('mousedown', start, false);
        el.addEventListener('touchstart', start, false);

        return drag;

    }

    // Better scroll events
     onScroll(cb) {
        var isTicking = false;
        var scrollY = 0;
        var body = document.body;
        var html = document.documentElement;
        var scrollHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
        function scroll() {
            scrollY = window.scrollY;
            if (cb) cb(scrollY, scrollHeight);
            requestTick();
        }
        function requestTick() {
            if (!isTicking) requestAnimationFrame(updateScroll);
            isTicking = true;
        }
        function updateScroll() {
            isTicking = false;
            var currentScrollY = scrollY;
        }
        scroll();
        window.onscroll = scroll;
    }

    // Scroll to element
    scrollToElement(el, offset) {
        var off = offset || 0;
        var rect = el.getBoundingClientRect();
        var top = rect.top + off;
        var animation = anime({
            targets: [document.body, document.documentElement],
            scrollTop: '+='+top,
            easing: 'easeInOutSine',
            duration: 1500
        });
        // onScroll(animation.pause);
    }

    // Check if element is in viewport
    isElementInViewport(el, inCB, outCB, rootMargin) {
        var margin = rootMargin || '-10%';
        function handleIntersect(entries, observer) {
            var entry = entries[0];
            if (entry.isIntersecting) {
                if (inCB && typeof inCB === 'function') inCB(el, entry);
            } else {
                if (outCB && typeof outCB === 'function') outCB(el, entry);
            }
        }
        var observer = new IntersectionObserver(handleIntersect, {rootMargin: margin});
        observer.observe(el);
    }
}
