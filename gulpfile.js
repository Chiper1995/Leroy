var gulp = require('gulp');
var gutil = require('gulp-util');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var cleanCss = require('gulp-clean-css');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var del = require('del');
//var merge = require('merge-stream');

var vendor_path = 'vendor/';
var bower_path = vendor_path+'bower/';

var paths = {
    app_sass: ['frontend/scss/*.scss', '!frontend/scss/_*.scss', 'frontend/js/enjoyhint/enjoyhint.scss'],
    app_js: ['frontend/js/*.js', 'frontend/js/enjoyhint/enjoyhint.js',
        'node_modules/pdfjs-dist/web/pdf_viewer.js', 'node_modules/pdfjs-dist/build/pdf.js', 'node_modules/pdfjs-dist/build/pdf.worker.js'],

    lib_css: [
        bower_path+'jquery-ui/themes/smoothness/jquery-ui.css',
        bower_path+'bootstrap/dist/css/bootstrap.css',
        bower_path+'bootstrap-treeview/dist/bootstrap-treeview.min.css',
        '/node_modules/pdfjs-dist/web/pdf_viewer.css'

    ],
    lib_js: [
        bower_path+'jquery/dist/jquery.js',
        bower_path+'jquery-ui/jquery-ui.js',
        vendor_path+'yiisoft/yii2/assets/*.js',
        bower_path+'yii2-pjax/jquery.pjax.js',
        bower_path+'bootstrap/dist/js/bootstrap.js',
        bower_path+'bootbox/bootbox.js',
        bower_path+'bootstrap-treeview/dist/bootstrap-treeview.min.js',
        vendor_path+'webcreate/jquery-ias/src/*.js',
        vendor_path+'webcreate/jquery-ias/src/extension/*.js',
        bower_path+'js-cookie/src/js.cookie.js'
    ],

    inviteApp_js: [
        'frontend/web/js/vue/*.js', '!frontend/web/js/vue/*.min.js',
        'frontend/web/js/invite/*.js'
    ],
    inviteApp_scss: [
        'frontend/scss/invite/invite.app.scss'
    ],
    inviteApp_css: [
        bower_path+'bootstrap/dist/css/bootstrap.css',
    ]
};

gulp.task('default', ['app_sass', 'app_js', 'app.banners.copy']);

/* Стили приложения */
gulp.task('app_sass', function(done) {
    gulp.src(paths.app_sass)
        .pipe(sass())
        .on('error', sass.logError)
        .pipe(autoprefixer({
            browsers: ['ie >= 8', 'last 3 versions', '> 1%'],
            cascade: false
        }))
        .pipe(cleanCss({
            keepSpecialComments: 0
        }))
        .pipe(rename({extname: '.min.css'}))
        .pipe(gulp.dest('frontend/web/css/'))
        .on('end', done);
});

/* Скрипты приложения */
gulp.task('app_js', function(done) {
    gulp.src(paths.app_js)
        .pipe(uglify().on('error', gutil.log))
        .pipe(rename({extname: '.min.js'}))
        .pipe(gulp.dest('frontend/web/js'))
        .on('end', done);
});

/* Стили библиотек */
gulp.task('lib_css', function(done) {
    gulp.src(paths.lib_css)
        .pipe(concat('lib.min.css'))
        .pipe(autoprefixer({
            browsers: ['ie >= 8', 'last 3 versions', '> 1%'],
            cascade: false
        }))
        .pipe(cleanCss({
            keepSpecialComments: 0
        }))
        .pipe(gulp.dest('frontend/web/css/'))
        .on('end', done);
});

/* Скрипты библиотек */
gulp.task('lib_js', function(done) {
    gulp.src(paths.lib_js)
        .pipe(concat('lib.min.js'))
        .pipe(uglify().on('error', gutil.log))
        .pipe(gulp.dest('frontend/web/js/'))
        .on('end', done);
});

gulp.task('app.banners.delete', [], function(done) {
	return del(['frontend/web/js/banners/']);
});

gulp.task('app.banners.copy', ['app.banners.delete'], function(done) {
	return gulp.src('frontend/js/banners/**').pipe(gulp.dest('frontend/web/js/banners/'));
});

// Invite App
gulp.task('inviteApp.js', function() {
    return gulp.src(paths.inviteApp_js)
        .pipe(concat('app-invite.min.js'))
        .pipe(gulp.dest('frontend/web/js/'));
});

gulp.task('inviteApp.sass', function(done) {
    return gulp.src(paths.inviteApp_scss)
        .pipe(sass())
        .on('error', sass.logError)
        .pipe(concat('invite.app.scss.css'))
        .pipe(gulp.dest('frontend/web/css/'))
});

gulp.task('inviteApp.css', ['inviteApp.sass'], function(done) {
    var csss = paths.inviteApp_css;
    //csss.push('frontend/web/css/style.scss.css');
    csss.push('frontend/web/css/invite.app.scss.css');

    return gulp.src(csss)
        .pipe(concat('app-invite.min.css'))
        .pipe(autoprefixer({
            browsers: ['ie >= 8', 'last 3 versions', '> 1%'],
            cascade: false
        }))
        .pipe(cleanCss({
            keepSpecialComments: 0
        }))
        .pipe(gulp.dest('frontend/web/css/'));
});

gulp.task('watch', function() {
    gulp.watch(['frontend/scss/*.scss', 'frontend/js/*.js'], ['default'])
        .on('error', function(error) {
            // silently catch 'ENOENT' error typically caused by renaming watched folders
            if (error.code === 'ENOENT') {
                return;
            }
        });
});
