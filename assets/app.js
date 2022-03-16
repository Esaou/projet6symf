/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';

// start the Stimulus application
import 'jquery';
global.$ = global.jQuery = $;
import 'bootstrap/dist/js/bootstrap.min.js'

import 'tinymce/tinymce.min.js'

import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';


import '/assets/js/ScrollTop.js'
import '/assets/js/FlashTimeout.js'
import '/assets/js/ShowMedia.js'
import '/assets/js/Tinymce.js'
import '/assets/js/collectionTypeField'

