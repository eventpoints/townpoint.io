
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    initialize() {
        this.recorder = null
    }

    connect() {
        window.addEventListener(
            'message',
            event => {
                const data = event.data || {};
                switch (data.name) {
                    case 'stop-recording':
                        if (this.recorder) {
                            this.recorder.stop();
                            audio.pause();
                        }
                        break;
                }
            },
            false);

        const getStreamForWindow = () => navigator.mediaDevices.getUserMedia({
            audio: true
        });

        const getStreamForCamera = () => navigator.mediaDevices.getUserMedia({
            audio: true
        });

        const appendCamera = (stream) => {
            const audio = new Audio()
            audio.srcObject = stream;
            audio.controls = true
            audio.volume = 0;
            audio.play();
        };

        getStreamForCamera().then(streamCamera => {
            appendCamera(streamCamera);
            getStreamForWindow().then(streamWindow => {

                const finalStream = new MediaStream();
                const videoTrack = streamWindow.getVideoTracks()[0];
                finalStream.addTrack(videoTrack);
                const audioTrack = streamCamera.getAudioTracks()[0];
                finalStream.addTrack(audioTrack);

                this.recorder = new MediaRecorder(finalStream);
                this.recorder.ondataavailable = function(e) {
                    console.log('ondataavailable');
                    const link = document.createElement('a');
                    link.setAttribute('href', window.URL.createObjectURL(e.data));
                    link.setAttribute('download', 'video_' + Math.floor((Math.random() * 999999)) + '.webm');
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
                this.recorder.start();
            }).catch(function(err) {
                console.error('getStreamForWindow', err);
            });
        }).catch(function(err) {
            console.error('getStreamForCamera', err);
        });
    }
}
