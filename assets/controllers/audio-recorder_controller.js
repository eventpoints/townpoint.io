import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [
        'recordBtn',
        'playBtn',
        'content'
    ]

    getAudioStream = async () => await navigator.mediaDevices.getUserMedia({audio: true})

    initialize() {
        console.log(this.contentTarget)
        this.recordBtn = this.recordBtnTarget
        this.playBtn = this.playBtnTarget
        this.audioChunks = []
        this.mediaRecorder = null
    }

    record() {
        this.getAudioStream().then((stream) => {

            if (!this.mediaRecorder) {
                this.mediaRecorder = new MediaRecorder(stream)
            }

            switch (this.mediaRecorder.state) {
                case "inactive":
                    this.beginRecodingAudio()
                    break;
                case "recording":
                    this.stopRecodingAudio()
                    break;
            }

            this.mediaRecorder.addEventListener("dataavailable", (event) => {
                this.audioChunks.push(event.data)
            })

            this.mediaRecorder.addEventListener("stop", (event) => {
                let audioBlob = new Blob(this.audioChunks, {type: "audio/mp3"});
                let audioUrl = window.URL.createObjectURL(audioBlob);
                this.audio = new Audio(audioUrl);
                this.audio.loop = false
            })

        })
    }

    beginRecodingAudio() {
        this.mediaRecorder.start();
        this.recordBtn.classList.remove('mdi-microphone')
        this.recordBtn.classList.add('mdi-stop')
    }

    stopRecodingAudio() {
        this.mediaRecorder.stop()
        this.recordBtn.classList.remove('mdi-stop')
        this.recordBtn.classList.add('mdi-arrow-u-left-top-bold')
        this.playBtn.disabled = false
    }

     play() {
        this.audio.play()
    }

}
