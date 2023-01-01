import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [
        'trackList',
        'currentTrack',
        'trackTime',
        'seek',
        'playPauseBtn',
        'canvas'
    ]

    static values = {
        files: Array
    }

    initialize() {
        this.canvas = this.canvasTarget
        this.canvasContext = this.canvas.getContext("2d");
        this.playPauseBtn = this.playPauseBtnTarget
        this.seek = this.seekTarget
        this.current = this.currentTrackTarget
        this.trackTime = this.trackTimeTarget
        this.files = this.filesValue
        this.trackList = this.trackListTarget
        this.audioContext = new window.AudioContext()
        this.audio = new Audio(null)
        this.gain = this.audioContext.createGain()
        this.analyser = this.audioContext.createAnalyser()
        this.track = this.audioContext.createMediaElementSource(this.audio);
        this.track
            .connect(this.gain)
            .connect(this.analyser)
            .connect(this.audioContext.destination);

        this.analyser.fftSize = 512;
        this.analyser.minDecibels = -80;
        this.bufferLength = this.analyser.fftSize;
        this.dataArray = new Uint8Array(this.bufferLength);
    }

    connect() {
        this.files.forEach((file) => {
            let item = document.createElement('div')
            item.classList.add('list-group-item')
            item.classList.add('list-group-item-action')
            item.innerHTML = `<div>${file.title}</div><div>${file.createdAt}</div>`
            this.trackList.appendChild(item)

            item.addEventListener('click', () => this.loadTrack(file, item))
        })


        this.audio.addEventListener("timeupdate", () => {
            this.seek.value = this.audio.currentTime / this.audio.duration * 100;
            this.trackTime.textContent = this.fmtTime(this.audio.currentTime);
        })
    }

    seeking(event) {
        if (this.audio.src) {
            const pct = this.seek.value / 100;
            this.audio.currentTime = (this.audio.duration || 0) * pct;
        }
    }

    async loadTrack(file, item) {
        this.playPauseBtn.parentNode.classList.remove('disabled')
        this.seek.value = 0
        this.current.innerText = file.title
        this.audio.src = file.content
        await this.audioContext.resume()

        this.audio.onloadedmetadata = async (event) => {
            console.log(this.audio.duration)
            this.trackTime.textContent = this.audio.duration;
        }
    }

    async playPause() {
        if (this.audio.paused) {
            await this.audio.play()
            this.animate()
            this.playPauseBtn.classList.replace('mdi-play', 'mdi-pause')
        } else {
            this.audio.pause()
            this.playPauseBtn.classList.replace('mdi-pause', 'mdi-play')
        }
    }

    getAudioData() {
        this.analyser.getByteTimeDomainData(this.dataArray);
        return this.dataArray;
    }

    animate() {
        window.requestAnimationFrame(this.animate);

        let width = this.canvas.width = window.innerWidth,
            height = this.canvas.height = 200;

        this.canvasContext.fillStyle = '#dddddd';
        this.canvasContext.fillRect(0, 0, width, height);
        let data =[]
        data[0] = this.getAudioData();
        let frameCount = this.audioContext.sampleRate * 2.0;
        let myArrayBuffer = this.audioContext.createBuffer(2, frameCount, this.audioContext.sampleRate);

        let len = this.bufferLength,
            stepX = width / len,
            stepY = height,
            maxH = height * 0.2,
            cy = stepY * 0.5;

        this.canvasContext.lineWidth = 3;
        this.canvasContext.lineJoin = 'round';
        this.canvasContext.lineCap = 'round';
        this.canvasContext.strokeStyle = 'black';

        for (let j = 0; j <= 1; j++) {
            console.log(`loop J: ${j}`)
            let x = stepX * 0.5;
            this.canvasContext.beginPath();
            for (let i = 0; i < len; i++) {
                console.log(`loop I: ${i}`)
                let rat = (data[j][i] - 512.0) / 512.0,
                    y = rat * maxH + cy;
                if (i === 0 && j === 0) this.canvasContext.moveTo(x, y);
                else this.canvasContext.lineTo(x, y);
                x += stepX;
            }
            cy += stepY;
            this.canvasContext.stroke();
        }
    }

    padTime = (n) => (~~(n) + "").padStart(2, "0");
    fmtTime = (s) => s < 1 ? "00:00" : `${this.padTime(s / 60)}:${this.padTime(s % 60)}`;
}
