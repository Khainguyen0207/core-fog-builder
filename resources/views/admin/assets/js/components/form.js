'use strict'
import Quill from 'quill';

$(document).ready(() => {
    $('form[data-bs-toggle="crawler-form"]').on('submit', function (e) {
        $(this).find('button[data-bs-toggle="btn-crawler"]').prop('disabled', true)
    })

    const fullToolbar = [
        [
            {
                font: []
            },
            {
                size: []
            }
        ],
        ['bold', 'italic', 'underline', 'strike'],
        [
            {
                color: []
            },
            {
                background: []
            }
        ],
        [
            {
                script: 'super'
            },
            {
                script: 'sub'
            }
        ],
        [
            {
                header: '1'
            },
            {
                header: '2'
            },
            'blockquote',
            'code-block'
        ],
        [
            {
                list: 'ordered'
            },
            {
                list: 'bullet'
            },
            {
                indent: '-1'
            },
            {
                indent: '+1'
            }
        ],
        [
            'direction',
            {
                align: []
            }
        ],
        ['link', 'image', 'video', 'formula'],
        ['clean']
    ];

    const $fullEditor = $('#full-editor');

    let fullEditor = null;

    if ($fullEditor.length) {
        fullEditor = new Quill($fullEditor[0], {
            bounds: '#full-editor',
            placeholder: 'Type Something...',
            modules: {
                toolbar: fullToolbar
            },
            theme: 'snow'
        });
    }

    const form = document.querySelector('form');
    if (form && fullEditor) {
        form.addEventListener('submit', () => {
            document.querySelector('#input-hidden-full-editor').value =
                fullEditor.root.innerHTML;
        });
    }
})
