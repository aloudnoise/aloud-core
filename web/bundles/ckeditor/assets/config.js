/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

    config.extraPlugins = 'mathjax,fastpicture,image2,onchange';

    config.mathJaxClass = 'mjx';

    config.toolbar = [
        [ 'Bold', 'Fastpicture', 'Mathjax', 'Subscript', 'Superscript'  ]
    ];

};
