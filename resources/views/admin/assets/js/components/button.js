import $ from "jquery";

$(document).ready(function () {
    $('button[type="reset"][data-bb-toggle="btn-with-href"]').on('click', function (e) {
        e.preventDefault();

        window.location.href = $(this).data('url');
    })

    $('button[type="reset"][data-bb-toggle="btn-header-action"]').on('click', function (e) {
        e.preventDefault();

        console.log('OK')

        const type = $(this).data('type');
        const name = $(this).data('name');
        const route = $(this).data('url');
        const csrf = $('meta[name="csrf-token"]').attr('content');

        if (type !== 'file') {
            window.location.href = route;
        }

        if (type === 'file') {
            const form = $(domFormAction(type, name, route, csrf));
            $('body').append(form);

            form.find('input[type="file"]').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    form.submit();
                }
            });

            form.find('input[type="file"').click();
        }
    })

    function domFormAction(type, name, route, csrf) {
        return `<form action="${route}" method="post"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_token" value="${csrf}">
            <input type="${type}" class="form-control" name="${name}" accept=".json">
            <button type="submit" class="btn d-none btn-inverse-primary btn-icon-text me-3">
        </form>`;
    }
})
