jQuery(document).ready(function($) {
    // Login form handling
    $('#vigyapanam-login-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const $errorContainer = $form.find('.error-message');
        
        // Remove any existing error messages
        $errorContainer.remove();
        $submitButton.prop('disabled', true);

        const formData = new FormData($form[0]);
        formData.append('action', 'vigyapanam_login');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    $form.prepend('<div class="error-message">' + response.data + '</div>');
                }
            },
            error: function(xhr, status, error) {
                $form.prepend('<div class="error-message">Login failed. Please try again.</div>');
                console.error('Login error:', error);
            },
            complete: function() {
                $submitButton.prop('disabled', false);
            }
        });
    });
});