'use strict'

$(document).ready(() => {
    $('input[aria-label="Select all rows"]').on('change', function (e) {
        if ($(this).prop('checked')) {
            $('input[aria-label="Select row"]').prop('checked', true);
        } else {
            $('input[aria-label="Select row"]').prop('checked', false);
        }
    })

    const confirmDom = '[data-bs-toggle="modal"][data-bs-target="#confirm-modal"]';
    const editDom = '[data-bs-toggle="modal"][data-bs-target="#create-modal"]';

    $('#dataTable').on('click', function (e) {
        if ($(e.target).is(confirmDom) || $(e.target).parent().is(confirmDom)) {
            let modalA = $(e.target);
            if (!$(e.target).is(confirmDom)) {
                modalA = $(e.target).parent();
            }

            const href = modalA.attr('data-bs-action');
            const result = href.substring(0, href.lastIndexOf('/')) + "/" + modalA.attr('data-bs-key');

            const method = modalA.attr('data-bs-method');

            $('#confirm-modal .id-row-del').text('#' + modalA.attr('data-bs-key'))

            $('#form-confirm-modal').attr('action', result)
            $('#form-confirm-modal input[name="_method"]').val(method)
            $('#confirm-modal .id-row-del-content').text(modalA.attr('data-bs-content'));
        }

        if ($(e.target).is(editDom) || $(e.target).parent().is(editDom)) {
            let modal = $(e.target);

            if (!$(e.target).is(editDom)) {
                modal = $(e.target).parent();
            }

            const href = modal.attr('data-bs-action');

            const result = href.substring(0, href.lastIndexOf('/0/edit')) + "/" + modal.attr('data-bs-key');

            const method = modal.attr('data-bs-method');

            $('#create-modal .id-row-del').text('#' + modal.attr('data-bs-key'))

            const $form = $('#create-modal form');

            $form.attr('action', result)
            $('#create-modal input[name="_method"]').val(method)
            $('.modal-header-content').text('Chỉnh sửa ' + $('.card-datatable h4').text());

            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').text();

            $.ajax({
                url: result,
                method: 'GET',
                error: function (request, error) {
                    alert(" Can't do because: " + error);
                },
                success: function ({error, data, message}) {
                    const $form = $('#create-modal').find('form');

                    $form[0].reset();
                    $form.find('input:checkbox, input:radio').prop('checked', false);

                    $form.find('[name]').each(function () {
                        const $el = $(this);
                        const name = $el.attr('name');

                        if (!(name in data)) return;
                        const val = data[name];

                        
                        if ($el.is(':checkbox')) {
                            $el.prop('checked', !!val);
                            return;
                        }

                        
                        if ($el.is(':radio')) {
                            $form.find(`[name="${name}"][value="${val}"]`).prop('checked', true);
                            return;
                        }

                        if ($el.is('.selectpicker')) {
                            $el.selectpicker('val', `${val}`);

                            return;
                        }

                        if ($el.is('select')) {
                            $el.val(val).trigger('change');
                            return;
                        }

                        $el.val(val);
                    });
                }
            });
        }
    })

    $('[data-bs-toggle="modal"][data-bs-target="#create-modal"]').on('click', function (e) {
        const href = $(this).attr('data-bs-action');

        $('#create-modal .id-row-del').text('#' + $(this).attr('data-bs-key'))

        $('#create-modal form').attr('action', href)
        $('#create-modal form input[name="_method"]').val('POST')

        const $form = $('#create-modal').find('form');

        $form.removeClass('was-validated');

        $('.modal-header-content').text('Thêm ' + $('.card-datatable h4').text());

        $form[0].reset();

        $form.find('input:checkbox, input:radio').prop('checked', false);

        $form.find('.selectpicker').each(function () {
            const $select = $(this);

            $select.selectpicker('destroy');
            $select.selectpicker();
        });

        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text();
    })

    $(document).on('mouseenter', '.product-hover-image', function () {
        const url = $(this).data('bs-url');
        if (url === undefined || url === '') {
            return;
        }

        const tooltip = $(`
        <div>
            <img src="${url}" alt="">
        </div>
    `);

        
        $(this).parent().css({
            position: 'relative',
        });

        
        tooltip.css({
            position: 'absolute',
            top: '-60px',
            left: '30px',

            width: '64px',
            height: '64px',

            borderRadius: '20px',
            overflow: 'hidden',
            border: '2px solid gray',

            background: '#fff',
            boxShadow: '0 8px 20px rgba(0,0,0,0.18)',

            opacity: 0,
            transform: 'scale(0.85) translateY(10px)',
            transition: 'opacity 0.2s ease, transform 0.2s ease',

            pointerEvents: 'none',
            zIndex: 9999,
        });

        
        tooltip.find('img').css({
            width: '100%',
            height: '100%',
            objectFit: 'cover',
            display: 'block',
        });

        $(this).before(tooltip);

        
        requestAnimationFrame(() => {
            tooltip.css({
                opacity: 1,
                transform: 'scale(1) translateY(0)',
            });
        });

        $(this).data('tooltip', tooltip);
    });

    $(document).on('mouseleave', '.product-hover-image', function () {
        const tooltip = $(this).data('tooltip');
        if (!tooltip) return;

        tooltip.css({
            opacity: 0,
            transform: 'scale(0.85) translateY(10px)',
        });

        setTimeout(() => {
            tooltip.remove();
        }, 200);
    });

    $('[data-bs-toggle="export"]').on('click', function () {
        const params = $('#form-filter').serialize();

        window.location.href = $(this).attr('data-bs-action') + '?' + params;
    });

    $('[data-bs-toggle="import"]').on('click', function (e) {
        e.preventDefault();

        const url = $(this).attr('data-bs-action');

        if (!url) {
            alert('Thiếu data-bs-action (URL import).');
            return;
        }

        const $fileInput = $('<input>', {
            type: 'file',
            accept: '.xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv',
            style: 'display:none'
        });

        $('body').append($fileInput);

        $fileInput.on('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                $fileInput.remove();
                return;
            }

            const fd = new FormData();
            fd.append('file', file); 
            fd.append('_token', $('meta[name="csrf-token"]').attr('content')); 

            $.ajax({
                url: url,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    
                    const ok = res && (res.error === false || res.success === true);

                    if (ok) {
                        alert(res.message || 'Import thành công.');
                        window.location.reload();
                    } else {
                        
                        alert(res.message || 'Import thất bại.');
                    }
                },
                error: function (xhr) {
                    
                    let msg = 'Import thất bại.';

                    if (xhr.responseJSON) {
                        const r = xhr.responseJSON;

                        if (r.message) msg = r.message;

                        
                        if (r.errors) {
                            const all = [];
                            Object.values(r.errors).forEach(arr => {
                                if (Array.isArray(arr)) all.push(...arr);
                            });
                            if (all.length) msg = all.join('\n');
                        }
                    }

                    alert(msg);
                },
                complete: function () {
                    $fileInput.remove();
                }
            });
        });

        $fileInput.trigger('click');
    });
})
