/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	config.extraPlugins = 'mathjax,fastpicture,image2,onchange,autogrow,material';

    //config.mathJaxLib = MATHJAX_LIB_PATH;
    config.mathJaxLib = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML';
    config.mathJaxClass = 'mjx';

    config.toolbar = [
        [ 'Bold', 'Fastpicture', 'Mathjax', 'Subscript', 'Superscript', 'Material', 'Link'  ]
    ];

    config.autoGrow_minHeight = 350;
    config.autoGrow_onStartup = true;

};
