jQuery(document).ready(function($) {
    $('#vigyapanam-combined-register-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        
        // Validate passwords match
        const password = $form.find('input[name="password"]').val();
        const confirmPassword = $form.find('input[name="confirm_password"]').val();
        
        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        $submitButton.prop('disabled', true);

        const formData = new FormData($form[0]);
        formData.append('action', 'vigyapanam_combined_register');

        $.ajax({
            url: vigyapanamAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    alert(response.data);
                }
            },
            error: function() {
                alert('Registration failed. Please try again.');
            },
            complete: function() {
                $submitButton.prop('disabled', false);
            }
        });
    });
});