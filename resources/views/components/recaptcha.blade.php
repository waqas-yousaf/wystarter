@props(['action' => 'submit'])

@once
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}" defer></script>
@endonce

<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-{{ $action }}">

<script>
    (function () {
        const input = document.getElementById('g-recaptcha-response-{{ $action }}');
        const form = input ? input.closest('form') : null;

        if (!form) return;

        form.addEventListener('submit', function (e) {
            if (input.value) return;

            e.preventDefault();

            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('recaptcha.site_key') }}', { action: '{{ $action }}' })
                    .then(function (token) {
                        input.value = token;
                        form.submit();
                    });
            });
        });
    })();
</script>
