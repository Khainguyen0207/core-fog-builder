@php($containerFooter = $containerNav ?? 'container-fluid')
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body">&copy; {{ date('Y') }}, made by <a href="{{ config('figure-admin-shared.branding.creator_url') }}" target="_blank" class="footer-link">{{ config('figure-admin-shared.branding.creator_name') }}</a></div>
        </div>
    </div>
</footer>
