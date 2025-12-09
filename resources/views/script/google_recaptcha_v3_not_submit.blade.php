<!-- Google reCAPTCHA v3 Script not submit -->
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'view_info' }).then(function (token) {
            fetch("/validate-recaptcha", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ token: token })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('protected-content').style.display = 'block';
                    } else {
                        document.getElementById('warning').style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('warning').innerText = 'Captcha check failed.';
                    document.getElementById('warning').style.display = 'block';
                });
        });
    });
</script>

{{--<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>--}}
{{--<script>--}}
{{--    grecaptcha.ready(function() {--}}
{{--        grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", { action: "form_submit" }).then(function(token) {--}}
{{--            document.getElementById("g-recaptcha-response").value = token;--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
