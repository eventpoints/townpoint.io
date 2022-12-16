import {Controller} from '@hotwired/stimulus';
import anime from "animejs";

export default class extends Controller {

    static targets = [
        'view',
        'numbersDisplay'
    ]

    connect() {
        this.viewport = this.viewTarget
        this.numbersDisplay = this.numbersDisplayTarget

        this.numbers = [
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
        ]
        this.randomNumbers = [
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100),
            this.getRandomArbitrary(10, 100)
        ]

        this.allNumbers = [...this.randomNumbers, ...this.numbers]
        this.shuffle(this.allNumbers)

        this.numbers.forEach((number) => {
            var randomColor = Math.floor(Math.random()*16777215).toString(16);
            let el = document.createElement('div');
            el.setAttribute('class', 'required-number shadow')
            el.innerText = number
            el.style.backgroundColor = `#${randomColor}`
            this.numbersDisplay.appendChild(el)
        })

        this.allNumbers.forEach((number) => {
            var randomColor = Math.floor(Math.random()*16777215).toString(16);
            let el = document.createElement('div');
            el.setAttribute('class', 'captcha-number shadow')
            el.innerText = number
            el.style.backgroundColor = `#${randomColor}`
            el.style.transform = 'translateY(-8rem)'
            this.viewport.appendChild(el)
        })

        this.animation = anime({
            targets: '.captcha-number',
            duration: 10000,
            translateY: 100,
            delay: function (el, i) {
                return i * Math.floor(Math.random() * (1500 - 500) + 500);
            },
            elasticity: 200,
            easing: 'easeInOutQuad',
            autoplay: false,
            loop: 1,
            complete: function(anim) {
                alert('failed')
            }
        });

        setTimeout(()=>{
            this.animation.play()
        }, 2000)

    }

    getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }

    getRandomArbitrary(min, max) {
        return Math.floor(Math.random() * (max - min) + min);
    }

    shuffle(array) {
        let currentIndex = array.length,  randomIndex;

        // While there remain elements to shuffle.
        while (currentIndex !== 0) {

            // Pick a remaining element.
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            // And swap it with the current element.
            [array[currentIndex], array[randomIndex]] = [
                array[randomIndex], array[currentIndex]];
        }

        return array;
    }
}