/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

'use strict';

(function () {
    CKEDITOR.plug
    CKEDITOR.plugins.add('monospace',
        {
            init: function (editor) {
                var pluginName = 'monospace';
                editor.ui.addButton('monospace-btn',
                    {
                        label: 'test',
                        command: 'monospace',
                        icon: CKEDITOR.plugins.getPath('monospace') + 'icon.gif'
                    });
                var cmd = editor.addCommand('uploadImage', {exec: showUploadDialog});
            }
        });
    var thisPath = CKEDITOR.plugins.getPath('monospace');

    function showUploadDialog(e) {
        var editor = e;
        var process_url = window.ck_img_upload_url;
        var img_path = window.ck_img_path;
        if (!process_url.length) console.log('EIS Error: Upload URL');
        var input = document.createElement('input');
        input.type = "file";
        input.name = "file";
        setTimeout(function () {
            $(input).click();
        }, 200);
        $(input).change(function (e) {
            $('.cke_button__monospace-btn_icon').css('backgroundImage', 'url(' + thisPath + 'source.gif)');
            var fileuploaddata = new FormData();
            fileuploaddata.append('file', $(this)[0].files[0]);
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            if (percentComplete === 100) {
                                $('.cke_button__monospace-btn_icon').css('backgroundImage', 'url(' + thisPath + 'icon.gif)');
                            }
                        }
                    }, false);

                    return xhr;
                },
                url: process_url,
                type: "POST",
                data: fileuploaddata,
                contentType: false,
                processData: false,
                success: function (result) {

                    editor.insertHtml(`
<figure class="easyimage easyimage-full">
<img src="` + img_path + result.filename + `">
<figcaption></figcaption>
</figure>
`);

                }
            });
        });
//                });
    }

})();
