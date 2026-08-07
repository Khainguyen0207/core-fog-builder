

'use strict';

(function () {
    
    
    const toastPlacementExample = document.querySelector('.toast-placement-ex'),
        toastPlacementBtn = document.querySelector('#showToastPlacement');
    let selectedType, selectedPlacement, toastPlacement;

    
    function toastDispose(toast) {
        if (toast && toast._element !== null) {
            if (toastPlacementExample) {
                toastPlacementExample.classList.remove(selectedType);
                DOMTokenList.prototype.remove.apply(toastPlacementExample.classList, selectedPlacement);
            }
            toast.dispose();
        }
    }

    
    if (toastPlacementBtn) {
        toastPlacementBtn.onclick = function () {
            if (toastPlacement) {
                toastDispose(toastPlacement);
            }
            selectedType = document.querySelector('#selectTypeOpt').value;
            selectedPlacement = document.querySelector('#selectPlacement').value.split(' ');

            toastPlacementExample.classList.add(selectedType);
            DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
            toastPlacement = new bootstrap.Toast(toastPlacementExample);
            toastPlacement.show();
        };
    }
})();
