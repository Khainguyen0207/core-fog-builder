@section('page-script')

@endsection

<section id="toast-section">
    <div id="toast-container" class="align-items-end toast-container top-right"></div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const safeShow = (opts) => {
            if (window.toastManager) {
                window.toastManager.show(opts);
            } else {
                
                setTimeout(() => {
                    if (window.toastManager) window.toastManager.show(opts);
                }, 100);
            }
        };

        @if(session('success'))
        safeShow({
            type: 'success'
            , title: 'Success'
            , message: "{{ session('success') }}"
        });
        @endif

        @if(session('error'))
        safeShow({
            type: 'error'
            , title: 'Error'
            , message: "{{ session('error') }}"
        });
        @endif

        @if(session('warning'))
        safeShow({
            type: 'warning'
            , title: 'Warning'
            , message: "{{ session('warning') }}"
        });
        @endif

        @if(session('info'))
        safeShow({
            type: 'info'
            , title: 'Info'
            , message: "{{ session('info') }}"
        });
        @endif

        @if($errors->any())
        @if($errors->count() == 1)
        safeShow({
            type: 'error'
            , title: 'Validation Error'
            , message: "{{ $errors->first() }}"
        });
        @else
        let errorHtml = '<ul style="margin: 0; padding-left: 16px;">';
        @foreach($errors->all() as $error)
        errorHtml += '<li>{{ $error }}</li>';
        @endforeach
        errorHtml += '</ul>';
        safeShow({
            type: 'error'
            , title: 'Validation Errors'
            , message: errorHtml
        });
        @endif
        @endif
    });

</script>
