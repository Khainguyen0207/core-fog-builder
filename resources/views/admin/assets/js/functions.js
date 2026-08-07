window.generateForm = function generateForm(formData, formId) {
    const $form = $('form#' + formId);

    if ($form.length === 0) {
        console.error('Form với ID "' + formId + '" không tồn tại');
        return;
    }

    $.each(formData, function (key, value) {
        let $element = $form.find('[name="' + key + '"]');
        if ($element.length === 0) {
            $element = $form.find('#' + key);
        }

        if ($element.length > 0) {
            const tagName = $element.prop('tagName').toLowerCase();
            const inputType = $element.attr('type');

            if (tagName === 'input') {
                if (inputType === 'checkbox') {
                    if (Array.isArray(value)) {
                        $element.each(function () {
                            const shouldCheck = value.includes($(this).val());
                            if ($(this).prop('checked') !== shouldCheck) {
                                $(this).prop('checked', shouldCheck);
                            }
                        });
                    } else {
                        const shouldCheck = (value === true || value === $element.val());
                        if ($element.prop('checked') !== shouldCheck) {
                            $element.prop('checked', shouldCheck);
                        }
                    }
                } else if (inputType === 'radio') {
                    const $radio = $form.find('[name="' + key + '"][value="' + value + '"]');
                    if (!$radio.prop('checked')) {
                        $radio.prop('checked', true);
                    }
                } else if (inputType === 'number') {
                    if (parseFloat($element.val()) !== parseFloat(value)) {
                        $element.val(value);
                    }
                } else if (inputType === 'file') {

                    handleFileInput($element, value, key);
                } else {
                    if ($element.val() !== value) {
                        $element.val(value);
                    }
                }
            } else if (tagName === 'select') {

                if ($element.hasClass('select2-hidden-accessible')) {
                    const currentVal = $element.val();
                    if ($element.prop('multiple')) {
                        if (!arraysEqual(currentVal, value)) {
                            $element.val(value).trigger('change');
                        }
                    } else {
                        if (currentVal !== value) {
                            $element.val(value).trigger('change');
                        }
                    }
                } else {
                    const currentVal = $element.val();
                    if ($element.prop('multiple')) {
                        if (!arraysEqual(currentVal, value)) {
                            $element.val(value);
                        }
                    } else {
                        if (currentVal !== value) {
                            $element.val(value);
                        }
                    }
                }
            } else if (tagName === 'textarea') {
                if ($element.val() !== value) {
                    $element.val(value);
                }
            }
        }

        function handleFileInput($element, value, fieldName) {
            const $fileInput = $element;
            const $container = $fileInput.closest('.form-group, .mb-3, .field-wrapper');


            let $previewContainer = $container.find('.file-preview-container');

            if ($previewContainer.length === 0) {
                $previewContainer = $('<div class="file-preview-container mt-2"></div>');
                $fileInput.after($previewContainer);
            }


            $previewContainer.empty();

            if (!value) return;

            try {

                let imageUrls = [];
                if (typeof value === 'string') {
                    try {
                        imageUrls = JSON.parse(value);
                    } catch (e) {

                        imageUrls = [value];
                    }
                } else if (Array.isArray(value)) {
                    imageUrls = value;
                }

                if (imageUrls.length > 0) {

                    const $title = $('<div class="preview-title mb-2"><small class="text-muted">Ảnh hiện có:</small></div>');
                    $previewContainer.append($title);


                    const $imagesList = $('<div class="images-preview-list d-flex flex-wrap gap-2"></div>');

                    imageUrls.forEach((url, index) => {
                        if (url && url.trim() !== '') {
                            const $imageItem = createImagePreviewItem(url, index, fieldName);
                            $imagesList.append($imageItem);
                        }
                    });

                    $previewContainer.append($imagesList);
                }
            } catch (error) {
                console.error('Error handling file input preview:', error);
            }
        }

        function createImagePreviewItem(imageUrl, index, fieldName) {
            const $item = $(`
                <div class="image-preview-item position-relative" style="width: 100px; height: 100px;">
                    <img src="${imageUrl}"
                         class="img-thumbnail w-100 h-100"
                         style="object-fit: cover; cursor: pointer;"
                         alt="Preview ${index + 1}"
                         data-bs-toggle="modal"
                         data-bs-target="#imagePreviewModal"
                         onclick="showImageModal('${imageUrl}')">
                    <button type="button"
                            class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-0 remove-image-btn"
                            style="width: 20px; height: 20px; font-size: 10px; line-height: 1;"
                            data-index="${index}"
                            data-field="${fieldName}"
                            title="Xóa ảnh">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);


            $item.find('.remove-image-btn').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const imageIndex = $(this).data('index');
                const fieldName = $(this).data('field');

                if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                    removeImageFromPreview(fieldName, imageIndex);
                }
            });

            return $item;
        }

        function arraysEqual(a, b) {
            if (a === b) return true;
            if (a == null || b == null) return false;
            if (a.length !== b.length) return false;

            const sortedA = [...a].sort();
            const sortedB = [...b].sort();

            for (let i = 0; i < sortedA.length; i++) {
                if (sortedA[i] !== sortedB[i]) return false;
            }
            return true;
        }

    });
}

window.actionForm = function actionForm($form) {
    $form.on('submit', function (event) {
        event.preventDefault()

        $form = $(this);
        const url = $form.attr('action');
        const method = $form.find('input[name="_method"]').val();

        $.ajax({
            url: url,
            method: method,
            data: $form.serialize(),
            success: function (res) {
                const data = res.data

                if (res.error) {
                    Notification.showError(data.message ?? '', 1)
                } else {
                    sessionStorage.setItem('success', data.message ?? '')
                }

                if (data.nextUrl !== undefined) {
                    window.location.href = data.nextUrl
                } else {
                    window.location.reload()
                }

            },
            error: function (message) {
                Notification.showError(message.responseJSON.message, 1)
            }
        })
    });
}