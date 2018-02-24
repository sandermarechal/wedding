var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    // Asset files
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/css/app.scss')

    // Enable Sass
    .enableSassLoader(function (options) {
        options.includePaths = [
            'node_modules/foundation-sites/scss'
        ];
    })

    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
