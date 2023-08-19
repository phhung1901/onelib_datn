const mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/v4/js/app.js', 'public/assets_v4/js')

/** themes **/
mix.sass('resources/v4/scss/themes/default.scss', 'public/assets_v4/css/themes/default.css')
    .sass('resources/v4/scss/themes/1library.net.scss', 'public/assets_v4/css/themes/1library.net.css');

/** tailwindcss **/
mix.postCss('resources/v4/scss/app.css', 'public/assets_v4/css', [
    tailwindcss('./tailwind.config.js'),
    require('autoprefixer'),
]);

mix.copyDirectory('resources/v4/packages/', 'public/assets_v4/packages/')
    .copyDirectory('resources/v4/images/', 'public/assets_v4/images/');

