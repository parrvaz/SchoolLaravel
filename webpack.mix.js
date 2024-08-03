const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('public/js/powerbi.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
