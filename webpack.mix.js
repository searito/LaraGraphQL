const mix = require('laravel-mix');

require('laravel-mix-tailwind');
require('@pp-spaces/laravel-mix-graphql');

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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css')
    .tailwind('./tailwind.config.js')
    .graphql();

if (mix.inProduction()) {
  mix
   .version();
}
