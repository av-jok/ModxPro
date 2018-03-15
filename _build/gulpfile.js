'use strict';

const gulp = require('gulp'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-clean-css'),
    chmod = require('gulp-chmod'),
    concat = require('gulp-concat');
const build = './assets/';
const assets = '../../../assets/components/modxpro/';

gulp.task('default', ['css', 'js']);

gulp.task('watch', function () {
    gulp.watch([build + 'scss/*.scss', build + 'scss/**/*.scss'], ['css']);
    gulp.watch([build + 'js/*.js', build + 'js/app/*.js'], ['js']);
});

gulp.task('css', function () {
    var src = build + 'scss/*.scss';
    var dst = assets + 'css/web/';
    gulp.src(src)
        .pipe(sass().on('error', sass.logError))
        .pipe(cssmin().on('error', function(e){console.log(e)}))
        .pipe(gulp.dest(dst));
});

gulp.task('js', function () {
    var src = build + 'js/*.js';
    var dst = assets + 'js/web/';
    gulp.src(src)
        /*
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        */
        .pipe(gulp.dest(dst));

    src = build + 'js/app/*.js';
    dst = assets + 'js/web/app/';
    gulp.src(src)
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        .pipe(gulp.dest(dst));
});

gulp.task('copy', function () {
    var src = [
        './node_modules/underscore/underscore-min.js',
        './node_modules/backbone/backbone-min.js',
        './node_modules/backbone.syphon/lib/backbone.syphon.min.js',
        './node_modules/backbone.epoxy/backbone.epoxy.min.js',
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/alertifyjs/build/alertify.min.js',
        './node_modules/requirejs/require.js',
        './node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        './node_modules/prismjs/prism.js',
        './node_modules/js-cookie/src/js.cookie.js',
        './node_modules/markitup/dist/markitup.min.js',
        './node_modules/jquery-form/dist/jquery.form.min.js',
        './node_modules/moment/min/moment-with-locales.min.js',
        './node_modules/numeral/min/numeral.min.js',
        './node_modules/fancybox/dist/js/jquery.fancybox.js',
        '../../../assets/components/pdotools/js/pdopage.js',
    ];
    var dst = assets + 'js/web/lib/';

    var i = 0;
    gulp.src(src)
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        .pipe(chmod({
            owner: {read: true, write: true, execute: false},
            group: {read: true, write: false, execute: false},
            others: {read: true, write: false, execute: false}
        }))
        .pipe(rename(function (path) {
            path.extname = '.min.js';
            path.basename = path.basename.replace(/([-.])min/, '').replace(/\.bundle/, '').toLowerCase();
            console.log(path.basename);
        }))
        .pipe(gulp.dest(dst));

    // Fonts
    src = [
        './node_modules/font-awesome-pro/svg-with-js/js/fontawesome.js',
        './node_modules/font-awesome-pro/svg-with-js/js/fa-brands.js',
        './node_modules/font-awesome-pro/svg-with-js/js/fa-solid.js',
        './node_modules/font-awesome-pro/svg-with-js/js/fa-regular.js',
        './node_modules/font-awesome-pro/svg-with-js/js/fa-light.js',
        //'./node_modules/font-awesome-pro/svg-with-js/js/fa-v4-shims.js',
    ];
    gulp.src(src)
        .pipe(concat('fontawesome.min.js'))
        .pipe(uglify())
        .pipe(chmod({
            owner: {read: true, write: true, execute: false},
            group: {read: true, write: false, execute: false},
            others: {read: true, write: false, execute: false}
        }))
        .pipe(gulp.dest(dst));

    //gulp.src('./node_modules/font-awesome-pro/web-fonts-with-css/webfonts/**').pipe(gulp.dest(assets + 'fonts/'));

    src = [
        './node_modules/prismjs/components/prism-smarty.min.js',
        './node_modules/prismjs/components/prism-nginx.min.js',
        './node_modules/prismjs/components/prism-php.min.js',
        './node_modules/prismjs/components/prism-sql.min.js'
    ];
    dst = assets + 'js/web/lib/prism.min/';
    gulp.src(src)
        .pipe(uglify())
        .pipe(chmod({
            owner: {read: true, write: true, execute: false},
            group: {read: true, write: false, execute: false},
            others: {read: true, write: false, execute: false}
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace(/^prism-/, '');
            path.extname = '.js';
        }))
        .pipe(gulp.dest(dst));

    gulp.src('./node_modules/fancybox/dist/img/**').pipe(gulp.dest(assets + 'img/fancybox/'))
});


/*
var concatCss = require('gulp-concat-css');
var browserSync = require('browser-sync');

gulp.task('serve', ['less'], function() {

    browserSync.init({
        server: "./src",
        notify: false
    });

    gulp.watch("src/less/*.less", ['less']);
    gulp.watch("src/*.html").on('change', browserSync.reload);
    gulp.watch("src/css/*.css").on('change', browserSync.reload);
});

gulp.task('less', function () {
    return gulp.src('src/less/*.less')
        .pipe(less())
        .pipe(concatCss("style.css"))
        .pipe(cssmin({compatibility: 'ie8'}))
        .pipe(gulp.dest('src/css'))
        .pipe(browserSync.stream());
});

gulp.task('default', ['serve']);
*/