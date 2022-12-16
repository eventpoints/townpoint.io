import {Controller} from '@hotwired/stimulus';
import anime from "animejs";

export default class extends Controller {

    static targets = [
        'svg'
    ]

    static values = {
        file: String,
        id: String
    }

    initialize() {
        this.audio = new Audio(this.fileValue);
        this.audioId = this.idValue
        this.svg = this.svgTarget

        let audioCtx = window.AudioContext || window.webkitAudioContext;
        this.audioContext = new audioCtx;
        this.gain = this.audioContext.createGain();
        this.analyser = this.audioContext.createAnalyser();
        this.track = this.audioContext.createMediaElementSource(this.audio);
        this.track
            .connect(this.gain)
            .connect(this.analyser)
            .connect(this.audioContext.destination);

        this.fftSize = 512
        this.tilt = 40
        this.choke = 110
        this.c = 0;
        this.analyser.minDecibels = -90;
        this.analyser.maxDecibels = -10;
        this.analyser.smoothingTimeConstant = 1;//0.75;
        this.analyser.fftSize = this.fftSize;
        this.width = 100
        this.height = 100
        this.maxHeight = Math.max(this.height * 0.3, 100)
    }

    connect() {
        this.audio.addEventListener('timeupdate', function () {
            update();
        });

        this.audio.addEventListener('canplay', function () {
            let g = document.createElementNS('svg', "g");
            this.svg.appendChild(g);
        });
    }

    shape(g, freqValue, freqSequence, freqCount, colorSequence) {
        let freqRatio = freqSequence / freqCount,
            x = (this.width - (this.tilt * 2)) * freqRatio + this.tilt,
            y = this.height / 2;

        let polyline = document.createElementNS('svg', "polyline"),
            throttledRatio = (freqValue - this.choke) / (255 - this.choke),
            strokeWidth = this.width / freqCount * 0.6 * throttledRatio,
            throttledY = Math.max(throttledRatio, 0) * this.maxHeight,
            // color
            color = "hsl(" +
                ((freqSequence / 2) + Math.floor(colorSequence)) + ", " +
                100 + "%," +
                freqRatio * 80 + "%" +
                ")";

        let loc_x = x - strokeWidth / 2,
            loc_y1 = y - throttledY / 2,
            loc_y2 = y + throttledY / 2,
            x_offset = this.tilt * throttledRatio;

        let points = []
        if (throttledRatio > 0) {
            let point_1 = (loc_x - x_offset) + "," + loc_y1,
                point_2 = (loc_x + x_offset) + "," + loc_y2;
            let points = [point_1, point_2];
        } else {
            let points = [loc_x + "," + (y - 1), loc_x + "," + (y + 1)]
        }

        polyline.setAttribute("stroke-width", strokeWidth);
        polyline.setAttribute("stroke", color);
        polyline.setAttribute("points", points.join(" "));
        g.appendChild(polyline);
    }

    update() {
        let g = document.createElementNS('svg', "g");
        let freqArray = new Uint8Array(this.analyser.frequencyBinCount);
        this.analyser.getByteTimeDomainData(freqArray);

        for (let i = 0; i < freqArray.length; i++) {
            let v = freqArray[i];
            this.shape(g, v, i + 1, freqArray.length, this.c);
        }
        this.svgTarget.appendChild(g);

        this.c += 0.5;
        requestAnimationFrame(update);
    }


    play(event) {
        if (this.audio.paused) {
            this.audioContext.resume()
            event.target.classList.remove('mdi-play')
            event.target.classList.add('mdi-pause')
            this.audio.play()
            return;
        }

        event.target.classList.remove('mdi-pause')
        event.target.classList.add('mdi-play')
        this.audio.pause()
    }

}
