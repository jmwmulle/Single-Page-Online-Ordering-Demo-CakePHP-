/**
 * Created by jono on 8/12/15.
 */
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    del = require('del');

gulp.task('scripts', function() {
  return gulp.src([
		  "gulp_files/js/Xtreme/utilities.js",
          "gulp_files/js/Xtreme/XSM.js",
          "gulp_files/js/Xtreme/XCL.js",
          "gulp_files/js/Xtreme/xtreme_data.js",
          "gulp_files/js/Xtreme/routing/collections/form_validation.js",
          "gulp_files/js/Xtreme/routing/collections/layout_api.js",
          "gulp_files/js/Xtreme/routing/collections/menu_ui.js",
          "gulp_files/js/Xtreme/routing/collections/modal_api.js",
          "gulp_files/js/Xtreme/routing/collections/orders_api.js",
          "gulp_files/js/Xtreme/routing/collections/tablet_ui.js",
          "gulp_files/js/Xtreme/routing/collections/user_accounts.js",
          "gulp_files/js/Xtreme/routing/collections/vendor_ui.js",
          "gulp_files/js/Xtreme/routing/XtremeRoute.js",
          "gulp_files/js/Xtreme/routing/XtremeRouter.js",
          "gulp_files/js/Xtreme/Printer.js",
          "gulp_files/js/Xtreme/EffectChain.js",
          "gulp_files/js/Xtreme/Orbcard.js",
          "gulp_files/js/Xtreme/XtremeLayout.js",
          "gulp_files/js/Xtreme/XtremeMenu.js",
          "gulp_files/js/Xtreme/XtremeCart.js",
          "gulp_files/js/Xtreme/Orbopt.js",
          "gulp_files/js/Xtreme/Optflag.js",
          "gulp_files/js/Xtreme/Orb.js",
          "gulp_files/js/Xtreme/xbs_modal.js",
          "gulp_files/js/Xtreme/xbs_splash.js",
          "gulp_files/js/Xtreme/xbs_validation.js",
          "gulp_files/js/Xtreme/xbs_vendor.js",
          "gulp_files/js/Xtreme/xbs_vendor_ui.js",
          "gulp_files/js/Xtreme/XBS.js",
          "gulp_files/js/Xtreme/application"
	  ])
    .pipe(concat('app.js'))
    .pipe(gulp.dest('js/'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('js/'))
    .pipe(notify({ message: 'Scripts task complete' }));
});
gulp.task('css_prefix', function () {
    return gulp.src('css/app.css')
        .pipe(autoprefixer({
            browsers: ['> 1%'],
            cascade: false
        }))
	    .pipe(rename({suffix: '.pref'}))
        .pipe(gulp.dest('css/'));
});

gulp.task('clean', function(cb) { del(['js/app.min.js', 'js/app.js'], cb) });

gulp.task('default', function() { gulp.start(['scripts', 'css_prefix']); });

gulp.task('watch', function() { gulp.watch('gulp_files/js/Xtreme/**/*.js', ['scripts', 'css_prefix']); });