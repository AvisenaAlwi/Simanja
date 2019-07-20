const mix = require('laravel-mix');

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
    .sass('resources/sass/app.scss', 'public/css');
// mix.browserSync('simanja.test');
mix.browserSync({
    proxy: 'https://simanja.test',
    https: {
        key: "C:/Users/Sena/.config/valet/Certificates/Simanja.test.key",
        cert: "C:/Users/Sena/.config/valet/Certificates/Simanja.test.crt"
    }
    // host: 'simanja.test',
    // open: 'external'
});