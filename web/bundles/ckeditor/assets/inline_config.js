/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

    config.extraPlugins = 'mathjax,image2,onchange';

    config.mathJaxClass = 'mjx';

    config.toolbar = [
        { name: 'document', items: [ 'Fastpicture', 'Mathjax', 'Subscript', 'Superscript' ] }
    ];

    config.allowedContent = true;

};
