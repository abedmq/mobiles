/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.toolbar = 'Basic';

    config.extraPlugins = 'iah-uploader';
    config.toolbar_Basic =
        // [
        //     ['Bold', 'Italic','strikethrough','Code']
        // ];


    [
        // { name: 'snddocument', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        // { name: 'clipboard', items : [ 'Undo','Redo' ] },
        // { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        // { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
        //         'HiddenField' ] },
        // '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Strike','SpecialChar'] },
        // { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
        //         '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        // { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        // { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        // '/',
        // { name: 'styles', items : [ 'Styles','Format','FontSize' ] },
        // { name: 'colors', items : [ 'TextColor','BGColor' ] },
        // { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] }
    ];
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
};
