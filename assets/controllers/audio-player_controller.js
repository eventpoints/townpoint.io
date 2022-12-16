import {Controller} from '@hotwired/stimulus';
import anime from 'animejs'

export default class extends Controller {

    static targets = [
        'player',
        'play',
        'seek',
        'time',
        'visualiser',
        'circle'
    ]

    static values = {
        file: String,
        id: String
    }

    initialize() {
        this.audio = new Audio(this.fileValue);
        this.audioId = this.idValue

        let audioCtx = window.AudioContext || window.webkitAudioContext;
        this.audioContext = new audioCtx;
        this.gain = this.audioContext.createGain();
        this.analyser = this.audioContext.createAnalyser();
        this.track = this.audioContext.createMediaElementSource(this.audio);
        this.track
            .connect(this.gain)
            .connect(this.analyser)
            .connect(this.audioContext.destination);

        this.seek = this.seekTarget;
        this.time = this.timeTarget;
        this.setup()
    }

    connect() {
        this.audio.addEventListener("loadeddata", () => {
            this.seek.setAttribute('value', 0);
            this.time.textContent = this.fmtTime(this.audio.duration);
        });

        this.audio.addEventListener("timeupdate", () => {
            this.graphic()
            this.seek.value = this.audio.currentTime / this.audio.duration * 100;
            this.time.textContent = this.fmtTime(this.audio.currentTime);
        });
    }

    play(event) {

        if (this.audio.paused) {
            this.audioContext.resume()
            event.target.classList.remove('mdi-play')
            event.target.classList.add('mdi-pause')
            this.audio.play()
            this.visualiser.play()
            return;
        }

        event.target.classList.remove('mdi-pause')
        event.target.classList.add('mdi-play')
        this.audio.pause()
        this.visualiser.pause()
    }

    seeking(event) {
        const pct = this.seek.value / 100;
        this.audio.currentTime = (this.audio.duration || 0) * pct;
        this.visualiser.seek(this.visualiser.duration * (this.seek.value / 100));
    }

    setup() {
        this.time.textContent = this.fmtTime(this.audio.duration)
    }

    graphic() {
        let freqArray = new Uint8Array(this.analyser.frequencyBinCount);
        this.analyser.getByteTimeDomainData(freqArray);

        for (let i = 0; i < freqArray.length; i++) {
            let v = freqArray[i];
            this.shape(v, i + 1, freqArray.length);
        }
    }

    shape(freqValue, freqSequence, freqCount){
        let width = window.innerWidth,
            height = window.innerHeight,
            maxHeight = Math.max(height * 0.3, 300),
            fftSize = 512, // 512
            tilt = 40,
            choke = 110,
            c = 0,
            freqRatio = freqValue / 255,
            throttledRatio = (freqValue - choke) / (255 - choke),
            strokeWidth = width / freqCount * 0.6 * throttledRatio,
            throttledY = Math.max(throttledRatio, 0) * maxHeight,
            freqR = freqSequence/freqCount,
            x = (100 - (40 * 2)) * freqRatio,
            y = 60 / 2;

        // this.visualiser = anime({
        //     targets: `.circle`,
        //     easing: 'easeOutInCirc',
        // });
        // this.visualiser.set( `.circle`,{
        //     scale: function() { return freqRatio; }
        // })
    }

    padTime = (n) => (~~(n) + "").padStart(2, "0");
    fmtTime = (s) => s < 1 ? "00:00" : `${this.padTime(s / 60)}:${this.padTime(s % 60)}`;

}
