jQuery(document).ready(function($) {

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imgraw').empty();

                $.sgplmodal.beforeOpen(function() {
                        Webcam.reset();
                });

                $.sgplmodal.open('#local');

                var uploadCrop = $('#imgraw').croppie({
                    viewport: {
                        width: 150,
                        height: 150,
                    },
                    boundary: {
                        width: 150,
                        height: 150
                    }
                });

                uploadCrop.croppie('bind', {
                    url: e.target.result
                });

                $('#crop-button').on('click', function(){
                    uploadCrop.croppie('result', {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function(base64Image) {
                        $('.avatar-result').attr('src', base64Image);

                        $('input[name="avatar"]').val(base64Image);

                        $.sgplmodal.close();
                    });
                });
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('body').on('click', 'a.upload-photo', function(e) {
        e.preventDefault();
        $('.file-field').click();
    })

    $("#imgInp").change(function(){
        readURL(this);
    });

    $('body').on('click', 'a.webcam', function(e) {
        e.preventDefault();
        $.sgplmodal.afterOpen(function() {
            Webcam.set({
                width: 300,
                height: 230,
                image_format: 'png',
                force_flash: false
            });
            Webcam.attach('.webcam-stream');
        });
        $.sgplmodal.beforeClose(function() {
            Webcam.reset();
        });
        $.sgplmodal.open('#webcam');
    });

    $('body').on('click', '#webcam-snapshot-button', function() {
        Webcam.snap( function(webcamBase64Image) {
            $('#webcam-snapshot-button').hide();
            $('#webcam-crop-button').show();

            Webcam.reset();
            $('.webcam-stream').css('height', 0);

            var uploadCrop = $('.webcam-result').croppie({
                // enableExif: true,
                viewport: {
                    width: 150,
                    height: 150,
                },
                boundary: {
                    width: 150,
                    height: 150
                }
            });

            uploadCrop.croppie('bind', {
                url: webcamBase64Image
            });

            $('#webcam-crop-button').on('click', function(){
                uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(base64Image) {
                    $('.avatar-result').attr('src', base64Image);

                    $('input[name="avatar"]').val(base64Image);

                    $('#webcam-snapshot-button').show();
                    $('#webcam-crop-button').hide();

                    uploadCrop.croppie('destroy');
                    $.sgplmodal.close();
                });
            });
        });
    });
});
