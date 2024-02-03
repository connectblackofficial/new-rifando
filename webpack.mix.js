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
let resourceCdnDir = "resources/cdn";
let resourceCdnDirVendor = `${resourceCdnDir}/vendor`
let publicCdnDir = "public/cdn";
let nodeDir = "node_modules";

let sitesScriptsHeader = [
    `${resourceCdnDirVendor}/jquery/jquery-3.7.0.min.js`,
    `${nodeDir}/bootstrap_5_0_2/dist/js/bootstrap.bundle.js`,
    `${nodeDir}/bootstrap_4_6_1/dist/js/bootstrap.bundle.js`,
];
/*
    `${resourceCdnDirVendor}/jquery/jquery-3.7.0.min.js`,
    `${nodeDir}/bootstrap_5_0_2/dist/js/bootstrap.bundle.js`,
    `${nodeDir}/bootstrap_4_6_1/dist/js/bootstrap.bundle.js`,
    `${resourceCdnDirVendor}/others/anime.min.js`,
    `${resourceCdnDirVendor}/fotorama-4.6.4/fotorama.js`,
    `${resourceCdnDir}/js/sw.js`,
    `${resourceCdnDir}/js/site-functions.js`
 */
let siteScriptsFooter = [
    `${resourceCdnDirVendor}/others/anime.min.js`,
    `${resourceCdnDirVendor}/fotorama-4.6.4/fotorama.js`,
    `${resourceCdnDir}/js/sw.js`,
    `${nodeDir}/sweetalert2/dist/sweetalert2.all.js`,
    `${resourceCdnDir}/js/shared-functions.js`,
    `${resourceCdnDir}/js/site-functions.js`
];
///home/caique/sites/new-rifando/resources/cdn/js/intlTelInput.min.js
///home/caique/sites/new-rifando/resources/cdn/js/jquery/jquery.maskMoney.min.js
//resources/assets/cdn/css/admin.scss
mix.sass('resources/cdn/saas/site.scss', 'public/cdn/build').sass('resources/cdn/saas/admin.scss', 'public/cdn/build').combine([
    `${resourceCdnDirVendor}/jquery/jquery-3.7.0.min.js`,
    `${nodeDir}/bootstrap_4_6_1/dist/js/bootstrap.bundle.js`,
    `${resourceCdnDir}/dist/js/adminlte.min.js`,
    `${resourceCdnDirVendor}//jquery/jquery.maskMoney.min.js`,
    `${resourceCdnDirVendor}/moment/moment.min.js`,
    `${resourceCdnDirVendor}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js`,
    `${resourceCdnDirVendor}/jquery_dataTables_v1.11.5/js/jquery.dataTables.min.js`,
    `${resourceCdnDirVendor}/jquery_dataTables_v1.11.5/js/dataTables.buttons.min.js`,
    `${resourceCdnDirVendor}/others/jszip.min.js`,
    `${resourceCdnDirVendor}/others/pdfmake.min.js`,
    `${resourceCdnDirVendor}/others/vfs_fonts.js`,
    `${resourceCdnDirVendor}/others/buttons.html5.min.js`,
    `${resourceCdnDirVendor}/others/buttons.print.min.js`,
    `${nodeDir}/bootstrap_5_0_2/dist/js/bootstrap.bundle.js`,
    `${nodeDir}/bootstrap_5_0_2/dist/js/bootstrap.bundle.js`,
    `${nodeDir}/@popperjs/core/dist/umd/popper.min.js`,
    `${nodeDir}/sweetalert2/dist/sweetalert2.all.js`,
    `${resourceCdnDir}/js/shared-functions.js`,
    `${resourceCdnDir}/js/functions-admin.js`,
], `${publicCdnDir}/js/admin-bundle.min.js`).combine(sitesScriptsHeader, `${publicCdnDir}/js/site-header-bundle.min.js`).combine(sitesScriptsHeader, `${publicCdnDir}/js/site-header-bundle.min.js`).combine(siteScriptsFooter, `${publicCdnDir}/js/site-footer-bundle.min.js`).copyDirectory('resources/cdn', 'public/cdn').version();

///home/caique/sites/new-rifando/resources/cdn/vendor/jquery/jquery.maskMoney.min.js

