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

//gulp.task('vendor_scripts', function() {
//  return gulp.src([
//          "gulp_files/js/vendor/jquery.min.js",
//          "gulp_files/js/vendor/jquery.jquery-ui.js",
//          "gulp_files/js/vendor/jquery.validate.min.js",
//          "gulp_files/js/vendor/foundation.js",
//          "gulp_files/js/vendor/flipclock.min.js",
//		  "gulp_files/js/vendor/additional-methods.min.js",
//          "gulp_files/js/vendor/dynamic_audio.js",
//          "gulp_files/js/vendor/jsnes.js",
//	  ])
//    .pipe(concat('vendor.js'))
//    .pipe(gulp.dest('js/'))
//    .pipe(rename({suffix: '.min'}))
//    .pipe(uglify())
//    .pipe(gulp.dest('js/'))
//    .pipe(notify({ message: 'Vendor Scripts task complete' }));
//});
gulp.task('xtreme_scripts', function() {
  return gulp.src([
		  "gulp_files/js/Xtreme/utilities.js",
          "gulp_files/js/Xtreme/data/XSM.js",
          "gulp_files/js/Xtreme/data/XCL.js",
          "gulp_files/js/Xtreme/data/xtreme_data.js",
          "gulp_files/js/Xtreme/routing/collections/layout_api.js",
          "gulp_files/js/Xtreme/routing/collections/menu_ui.js",
          "gulp_files/js/Xtreme/routing/collections/orders_api.js",
          "gulp_files/js/Xtreme/routing/collections/pos_api.js",
          "gulp_files/js/Xtreme/routing/collections/user_accounts.js",
          "gulp_files/js/Xtreme/routing/collections/vendor_ui.js",
          "gulp_files/js/Xtreme/routing/XtremeRoute.js",
          "gulp_files/js/Xtreme/routing/XtremeRouter.js",
          "gulp_files/js/Xtreme/structure/OrbcardMenu.js",
          "gulp_files/js/Xtreme/structure/Orbcard.js",
          "gulp_files/js/Xtreme/structure/XtremeLayout.js",
          "gulp_files/js/Xtreme/structure/XtremeMenu.js",
          "gulp_files/js/Xtreme/model/XtremeCart.js",
          "gulp_files/js/Xtreme/model/Orbopt.js",
          "gulp_files/js/Xtreme/model/Optflag.js",
          "gulp_files/js/Xtreme/model/Orb.js",
          "gulp_files/js/Xtreme/structure/Modal.js",
          "gulp_files/js/Xtreme/structure/xbs_splash.js",
          "gulp_files/js/Xtreme/structure/XtremePOS.js",
          "gulp_files/js/Xtreme/structure/xbs_vendor_ui.js",
          "gulp_files/js/Xtreme/xbs_validation.js",
          "gulp_files/js/Xtreme/Printer.js",
          "gulp_files/js/Xtreme/XBS.js",
          "gulp_files/js/Xtreme/exceptions.js",
          "gulp_files/js/Xtreme/app"
	  ])
    .pipe(concat('xtreme.js'))
    .pipe(gulp.dest('js/'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('js/'))
    .pipe(notify({ message: 'Xtreme Scripts task complete' }));
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

gulp.task('clean', function(cb) { del(['js/xtreme.min.js', 'js/xtreme.js'], cb) });

gulp.task('default', function() { gulp.start(['xtreme_scripts', 'css_prefix']); });

gulp.task('watch', function() { gulp.watch('gulp_files/js/Xtreme/**/*.js', ['xtreme_scripts', 'css_prefix']); });