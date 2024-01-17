const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
//dist/js/bootstrap.bundle.min.js
let pluginsDir = "public/plugins";
let vendorDir = "public/vendor";
mix.sass('resources/assets/sass/site.scss', 'public/assets/css').sass('resources/assets/sass/admin.scss', 'public/assets/css').combine([
    `${pluginsDir}/jquery/jquery.min.js`,
    `${pluginsDir}/bootstrap/js/bootstrap.bundle.min.js`,
    `public/dist/js/adminlte.min.js`,
    `${pluginsDir}/summernote/summernote-bs4.min.js`,
    `${pluginsDir}/codemirror/codemirror.js`,
    `${pluginsDir}/codemirror/mode/css/css.js`,
    `${pluginsDir}/codemirror/mode/xml/xml.js`,
    `${pluginsDir}/codemirror/mode/htmlmixed/htmlmixed.js`,
    `${pluginsDir}/moment/moment.min.js`,
    `${pluginsDir}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js`,
    `${vendorDir}/jquery.dataTables.min.js`,
    `${vendorDir}/dataTables.buttons.min.js`,
    `${vendorDir}/jszip.min.js`,
    `${vendorDir}/pdfmake.min.js`,
    `${vendorDir}/vfs_fonts.js`,
    `${vendorDir}/buttons.html5.min.js`,
    `${vendorDir}/buttons.print.min.js`,
    `${vendorDir}/popper.min.js`,
    `${vendorDir}/sweetalert2.js`,
    `public/js/functions-admin.js`,
], `public/assets/js/admin-bundle.min.js`).combine([
    `${vendorDir}/jquery/jquery-3.7.0.min.js`,
    `${vendorDir}/bootstrap-5.0.2/dist/js/bootstrap.bundle.js`,
    `${pluginsDir}/bootstrap/js/bootstrap.bundle.min.js`,
    `${vendorDir}/anime.min.js`,
    `${vendorDir}/fotorama-4.6.4/fotorama.js`,
    `public/js/site-functions.js`,
    `public/sw.js`
], `public/assets/js/site-bundle.min.js`)
