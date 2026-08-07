

'use strict';

(function () {
    
    const youTubeModal = document.querySelector('#youTubeModal'),
        youTubeModalVideo = youTubeModal.querySelector('iframe');
    youTubeModal.addEventListener('hidden.bs.modal', function () {
        youTubeModalVideo.setAttribute('src', '');
    });

    
    const autoPlayYouTubeModal = function () {
        const modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
        modalTriggerList.map(function (modalTriggerEl) {
            modalTriggerEl.onclick = function () {
                const theModal = this.getAttribute('data-bs-target'), videoSRC = this.getAttribute('data-theVideo'),
                    videoSRCauto = `${videoSRC}?autoplay=1`, modalVideo = document.querySelector(`${theModal} iframe`);
                if (modalVideo) {
                    modalVideo.setAttribute('src', videoSRCauto);
                }
            };
        });
    };

    
    autoPlayYouTubeModal();
})();

