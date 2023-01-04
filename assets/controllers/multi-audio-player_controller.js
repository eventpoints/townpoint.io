import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [
        'canvas',
        'title',
        'time',
        'seek'
    ]


    connect() {
        this.current = null
        this.canvas = this.canvasTarget
        this.canvasContext = this.canvas.getContext("2d");
        this.seek = this.seekTarget
        this.seekTarget.value = 0;
        this.audioContext = new window.AudioContext()
        this.audio = new Audio(null)
        this.audio.loop = false
        this.audio.preload = 'auto';

        this.analyser = this.audioContext.createAnalyser()
        this.analyser.minDecibels = -90;
        this.analyser.maxDecibels = -10;
        this.analyser.smoothingTimeConstant = 0.85;

        this.track = this.audioContext.createMediaElementSource(this.audio);
        this.track
            .connect(this.analyser)
            .connect(this.audioContext.destination);

        this.analyser.fftSize = 2048;
        this.analyser.minDecibels = -80;
        this.bufferLength = this.analyser.fftSize;
        this.dataArray = new Uint8Array(this.bufferLength);

        this.audio.addEventListener("timeupdate", () => {
            this.seekTarget.value = this.audio.currentTime / this.audio.duration * 100;
            this.timeTarget.textContent = this.fmtTime(this.audio.currentTime);
        });
    }

    seeking(event) {
        if (this.audio.src) {
            const pct = this.seekTarget.value / 100;
            this.audio.currentTime = (this.audio.duration || 0) * pct;
        }
    }

    async loadTrack(event) {
        this.titleTarget.innerText = event.params.title;
        this.audio.src = event.params.content
        this.timeTarget.innerText = this.fmtTime(this.audio.duration)
        await this.audioContext.resume()
    }

    async playPause(event) {
        if (this.audio.paused) {
            await this.audio.play()
            event.target.classList.replace('mdi-play', 'mdi-pause')
            this.animate()
        } else {
            event.target.classList.replace('mdi-pause', 'mdi-play')
            this.audio.pause()
        }
    }

    animate() {
        let WIDTH = this.canvas.width;
        let HEIGHT = this.canvas.height;
        this.canvasContext.clearRect(0, 0, WIDTH, HEIGHT);

        const draw = () => {
            let WIDTH = this.canvas.width;
            let HEIGHT = this.canvas.height;

            requestAnimationFrame(draw);

            this.analyser.getByteTimeDomainData(this.dataArray);

            this.canvasContext.fillStyle = "rgb(255, 255, 255)";
            this.canvasContext.fillRect(0, 0, WIDTH, HEIGHT);

            this.canvasContext.lineWidth = 3;
            this.canvasContext.strokeStyle = "rgb(221,221,221)";

            this.canvasContext.beginPath();

            const sliceWidth = (WIDTH * 1.0) / this.bufferLength;
            let x = 0;

            for (let i = 0; i < this.bufferLength; i++) {
                let v = this.dataArray[i] / 128.0;
                let y = (v * HEIGHT) / 2 ;

                if (i === 0) {
                    this.canvasContext.moveTo(x, y);
                } else {
                    this.canvasContext.lineTo(x, y);
                }

                x += sliceWidth;
            }

            this.canvasContext.lineTo(this.canvas.width, this.canvas.height / 2);
            this.canvasContext.stroke();
        };

        draw(this.dataArray, this.bufferLength, WIDTH, HEIGHT);
    }



    padTime = (n) => (~~(n) + "").padStart(2, "0");
    fmtTime = (s) => s < 1 ? "00:00" : `${this.padTime(s / 60)}:${this.padTime(s % 60)}`;
}
