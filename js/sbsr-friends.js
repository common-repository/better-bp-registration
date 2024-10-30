jQuery('document').ready(function($) {
    var options = {
        callback: function (value) { 
            var container = $('.search-result');
            container.empty();
            container.addClass('active');
            container.append('<div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>');

            $('.search-title').html('Perform another search');

            $.post(
                ajaxurl,
                {
                    'action' : 'sbsr_search_friend_by_name',
                    'search_word' : value,
                    userID: user_object.userID,
                    userEmail: user_object.userEmail
                },
                function(response) {
                    response = JSON.parse(response);

                    var searchWordSpan = $('span.search-word');
                    var searchNotice = $('.search-notice');

                    searchNotice.show();
                    searchWordSpan.empty();
                    container.empty();
                    searchWordSpan.empty();
                    container.removeClass('active');

                    if (response.length > 0) {
                        searchWordSpan.append('"' + value + '"');

                        response.forEach(function(item, i, arr) {
                            var out = '<div class="item-wrap"> <div class="img-wrap"><img src="' + item.avatar_url + '" alt=""></div><div class="name">' + item.name + '</div><div class="button wrap"><button class="friend-request" data-id="' + item.user_id + '" type="button">Add friend</button></div></div>';
                            container.append(out);
                        });
                    } else {
                        searchNotice.hide();
                    }
                }
            );
        },
        wait: 750,
        highlight: true,
        allowSubmit: false,
        captureLength: 2
    }

    $('input#search-by-name').typeWatch( options );

    $('body').on('click', 'button.friend-request', function() {
        var user_id = $(this).data('id'); 
        var parentBlock = $(this).parent()

        parentBlock.empty();

        $.post(
            ajaxurl,
            {
                'action' : 'sbsr_send_friend_request',
                'userID' :user_id
                //'userEmail' : user_object.userEmail
            },
            function(response) {
                response = JSON.parse(response);

                if (response == true) {
                    parentBlock.append('Request send');
                } else {
                    parentBlock.append('Error');
                    console.log('Error send friend request');
                }
            }
        );
    });

});
