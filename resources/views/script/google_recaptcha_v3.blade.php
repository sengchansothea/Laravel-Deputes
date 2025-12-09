<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", { action: "form_submit" }).then(function(token) {
            document.getElementById("g-recaptcha-response").value = token;
        });
    });
</script>





