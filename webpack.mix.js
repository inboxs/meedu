let mix = require('laravel-mix');

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

mix.js('resources/assets/frontend/js/app.js', 'public/frontend/js/frontend.js').version();
mix.postCss("resources/assets/frontend/css/app.css", "public/frontend/css/app.css", [
    require("tailwindcss"),
]);

mix.js('resources/assets/h5/js/app.js', 'public/h5/js/app.js').version();
mix.sass('resources/assets/h5/sass/app.scss', 'public/h5/css/app.css').version();
