// load gulp & plugins

var gulp         = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var plumber      = require('gulp-plumber');
var sass         = require('gulp-sass');
var concat       = require('gulp-concat');
var gutil        = require('gulp-util');
var uglify       = require('gulp-uglify');
var cmq          = require('gulp-group-css-media-queries');
var livereload   = require('gulp-livereload');
var source       = "source/media/plg_system_nrframework/";

// define the default task and add the watch task to it
gulp.task('default', ['watch']);

/* compile scss files */
gulp.task('build-css', function () {
    // Individual Files
    gulp.src(source+'scss/*.scss')
        .pipe(plumber())
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(source+'css'))
        .pipe(livereload());
});

/* concat javascript files & minify */
gulp.task('build-js', function() {
    // Individual Files
    gulp.src(source+'js/dev/*.js')
        .pipe(plumber())
        .pipe(uglify())
        .pipe(gulp.dest(source+'js'))
        .pipe(livereload());
});

/* Watch these files for changes and run the task on update */
gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(source+'scss/**/*.scss', ['build-css']);
    gulp.watch(source+'js/dev/**/*.js', ['build-js']);
});