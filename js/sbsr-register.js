jQuery(document).ready(function($) {
    $('body').on('submit', 'form.standard-form', function(e) {
        var emailField = $('input#signup_email', this);
        var confirmEmailField = $('input#confirm_email', this);

        var email = emailField.val();
        var confirmEmail = confirmEmailField.val();

        if (email.length > 0 && confirmEmail.length > 0) {
            emailField.removeClass('warning');
            confirmEmailField.removeClass('warning');

            if (email !== confirmEmail) {
                emailField.addClass('warning');
                confirmEmailField.addClass('warning');
                e.preventDefault();
            } else {
                emailField.removeClass('warning');
                confirmEmailField.removeClass('warning');
            }
        } else {
            emailField.addClass('warning');
            confirmEmailField.addClass('warning');

            e.preventDefault();
        }
    });
});
