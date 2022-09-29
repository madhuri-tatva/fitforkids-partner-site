/* global $, window */

$(function () {
    'use strict';


    var filepathtogetimagesfrom = $('#fileupload').attr('uploadpath'); //'actions/ajax/uploader/index.php';//
    console.log(filepathtogetimagesfrom);
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        //forceIframeTransport: true,    // VERY IMPORTANT.  you will get 405 Method Not Allowed if you don't add this.
        autoUpload: true,
        maxNumberOfFiles: 7, // We allow 6 pictures + 1 custom thumbnail https://github.com/blueimp/jQuery-File-Upload/wiki/Options
        maxFileSize: 2000000, //2MB...
     //   function (data) {return data.result.files;},
          acceptFileTypes: /(\.|\/)(gif|jpe?g|png|docx|pdf)$/i,

        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: filepathtogetimagesfrom //'server/php/'
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );



        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
                console.log(result);
            //    console.log(result.files);

                var productid = $("#productid").val();

                $.each(result.files, function(index, val) {
                    console.log(val.name);

                var imgid = index;
                var imgname = val.name;
                $.ajax({
                                  url: 'actions/ajax/imagehandler.php',
                                  type: 'POST',
                                  dataType: 'json',
                                  data: {
                                    action: 'updateproductimagepath',
                                    imgid: imgid,
                                    imgname: imgname,
                                    productid: productid

                                },
                              })
                              .done(function() {
                                  console.log("success");
                              })
                              .fail(function() {
                                  console.log("error");
                              })
                              .always(function() {
                                  console.log("complete");
                              });


                     /* iterate through array or object */
                });
        });
  //  }

});
