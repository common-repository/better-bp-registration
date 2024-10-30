jQuery('document').ready(function($) {
    function logIn() {
        $.post( ajaxurl,
            {
                action: 'sbsr_login_user',
                userID: user_object.userID,
                userEmail: user_object.userEmail
            },
            function(response) {
                response = JSON.parse(response);
                window.location.href = response;
            }
        );
    }

    function nextStep(activator) {

        if ($(activator).closest('.screen').hasClass('finish')) {
            logIn();
        } else {
            var activeScreen = $($('.screen').not('.display-none')).data('screen');
            var nextScreen = activeScreen + 1;

            $('.screen').addClass('display-none');
            $('.screen-' + nextScreen).removeClass('display-none');

            $('.step-panel div').removeClass('active');
            $('.tab-index-' + nextScreen).addClass('active');
        }

    }

    $('body').on('click', '.skip', function(e) {
        e.preventDefault();
        nextStep(this);
    });
    $('body').on('click', '.friends .skip', function(event) {
        event.preventDefault();
        logIn();
    });

    var isValidUserName = false;

    function checkUserName(userName) {
        $.post( ajaxurl,
            {
                'action' : 'sbsr_check_user_name',
                'user_name' : userName
            },
            function(response) {
                response = JSON.parse(response);

                if (response == false) {
                    $('input#signup_user_name').addClass('warning');
                    isValidUserName = false;
                } else {
                    $('input#signup_user_name').removeClass('warning');
                    isValidUserName = true;
                }
            }
        );
    }


    // Handlers
    // Account details
    $('body').on('submit', '.account-details form', function(event) {
        event.preventDefault();

        var password_1 = $('input#signup_password', this).val();
        var password_2 = $('input#signup_confirm_password', this).val();
        var userName = $('input#signup_user_name', this).val();

        var displayName = $('select#signup_display_name option:selected', this).text();
        $('input#field_1').val(displayName);

        if (isValidUserName) {
            $('input#signup_user_name', this).removeClass('warning');

            if (password_1 === password_2 && password_1.length > 0) {
                $('input#signup_password', this).removeClass('warning');
                $('input#signup_confirm_password', this).removeClass('warning');

                var forma = $(this);

                var data = $(this).serializeArray();
                data = JSON.stringify(data);
                $.post( ajaxurl,
                    {
                        action: 'sbsr_update_user_profile',
                        data: data,
                        userID: user_object.userID,
                        userEmail: user_object.userEmail
                    },
                    function(response) {
                        response = JSON.parse(response);
                        if (response === true) {
                            nextStep(forma);
                        }
                    }
                );
            } else {
                $('input#signup_password', this).addClass('warning');
                $('input#signup_confirm_password', this).addClass('warning');
            }

        } else {
            $('input#signup_user_name', this).addClass('warning');
        }

    });

    // Create avatar
    $('body').on('submit', '.create-avatar form', function(event) {
        event.preventDefault();
        var data = $(this).serializeArray();
        var forma = $(this);
        data = JSON.stringify(data);
        $.post( ajaxurl,
            {
                action: 'sbsr_set_avatar',
                data: data,
                userID: user_object.userID,
                userEmail: user_object.userEmail
            },
            function(response) {
                response = JSON.parse(response);
                if (response === true) {
                    nextStep(forma);
                }
            }
        );
    });
    
    // Xprofile fields
    $('body').on('submit', '.xprofile-fields form', function(event) {
        event.preventDefault();
        var data = $(this).serializeArray();
        var forma = $(this);
        data = JSON.stringify(data);
        $.post( ajaxurl,
            {
                action: 'sbsr_set_xprofile_fields',
                data: data,
                userID: user_object.userID,
                userEmail: user_object.userEmail
            },
            function(response) {
                response = JSON.parse(response);
                if (response === true) {
                    nextStep(forma);
                }
            }
        );
    });

    // Groups
    $('body').on('submit', '.groups form', function(event) {
        event.preventDefault();
        var data = $(this).serializeArray();
        var forma = $(this);
        data = JSON.stringify(data);
        $.post( ajaxurl,
            {
                action: 'sbsr_set_groups',
                data: data,
                userID: user_object.userID,
                userEmail: user_object.userEmail
            },
            function(response) {
                response = JSON.parse(response);
                if (response === true) {
                    nextStep(forma);
                }
            }
        );
    });

    // Friends
    $('body').on('click', '.friends input[type="submit"]', function(event) {
        event.preventDefault();
        if ($(this).hasClass('finish')) {
            logIn();
        }
    });

    // Accaunt detail
    function displayName() {
        var firstName = $('input[name="signup_first_name"]').val();
        var secondName = $('input[name="signup_second_name"]').val();
        var userName = $('input[name="signup_user_name"]').val();

        if (userName != '') {
            if (firstName == '') {
                var myOptions = {
                    secondName : secondName,
                    userName : userName
                };
            }

            if (secondName == '') {
                var myOptions = {
                    firstName : firstName,
                    userName : userName
                };
            }

            if (firstName != '' && secondName != '') {
                var myOptions = {
                    firstName : firstName,
                    secondName : secondName,
                    fs : firstName + ' ' + secondName,
                    sf : secondName + ' ' + firstName,
                    userName : userName
                };
            }

            if (firstName == '' && secondName == '') {
                var myOptions = {
                    userName : userName
                }
            }


            var mySelect = $('#signup_display_name');
            mySelect.find('option').remove();

            $.each(myOptions, function(val, text) {
                if (val == 'userName') {
                    mySelect.append(
                        $('<option></option>').val(text).html(text).attr('selected', 'selected')
                    );
                } else {
                    mySelect.append(
                        $('<option></option>').val(text).html(text)
                    );
                }
            });
        }

    }

    $('input[name="signup_first_name"]').on('change', function() {
        displayName();
    });

    $('input[name="signup_second_name"]').on('change', function() {
        displayName();
    });

    $('input[name="signup_user_name"]').on('change', function() {
        displayName();
    });

    // Check user name
    var userNameOptions = {
        callback: function (value) { 
            checkUserName(value);
        },
        wait: 750,
        highlight: true,
        allowSubmit: false,
        captureLength: 2
    }
	checkUserName($('#signup_user_name').val());
    $('input#signup_user_name').typeWatch( userNameOptions );
});
