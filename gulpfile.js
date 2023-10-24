'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require("gulp-rename");
var cssnano = require('cssnano');
var autoprefixer = require('autoprefixer');
var notify = require("gulp-notify");
var postcss      = require('gulp-postcss');
var sourcemaps   = require('gulp-sourcemaps');
var terser = require('gulp-terser');
var babel = require('gulp-babel');
var pump = require('pump');
var converter = require('sass-convert');
var sassdoc = require('sassdoc');

var reportError = function (error) {
	notify({
		title: 'Gulp Task Error',
		message: 'Line '+error.line+"\n"+error.formatted
	}).write(error);
 
	this.emit('end');
}

//--------------------------------------------------------------------MINIFY
// minify CSS
gulp.task('minify:css', function(err) {
	return gulp.src([
		'./dev/sass/**/*.scss'
	])
	.pipe(sourcemaps.init())
	.pipe(sass().on('error', reportError))
	.pipe(postcss([
		autoprefixer(),
		cssnano()
	]))
	.pipe(rename({
		suffix: '.min'
	}))
	.pipe(sourcemaps.write('./maps'))
	.pipe(gulp.dest('./css'));
});

gulp.task('minify:gutenberg-css', function(err) {
	return gulp.src([
		'./dev/js/**/**/*.scss'
	])
	.pipe(sourcemaps.init())
	.pipe(sass().on('error', reportError))
	.pipe(postcss([
		autoprefixer(),
		cssnano()
	]))
	.pipe(rename({
		suffix: '.min'
	}))
	.pipe(sourcemaps.write('./maps'))
	.pipe(gulp.dest('./js'));
});

// Minify JavaScript
gulp.task('minify:js', function(err) {
	return gulp.src([
		'./dev/js/**/*.js',
		'./dev/js/**/**/*.js',
		'!./dev/js/**/*min.js'
	])
	.pipe(sourcemaps.init())
	.pipe(babel({'plugins': ['@babel/plugin-transform-react-jsx']}))
	.pipe(terser())
	.pipe(rename({
		suffix: '.min'
	}))
	.pipe(sourcemaps.write('./maps'))
	.pipe(gulp.dest('./js'));
});


//--------------------------------------------------------------------WATCHERS
// watch for sass file change and compile on save.
gulp.task('sass:watch', gulp.series(['minify:css'], function () {
	gulp.watch('./dev/sass/**/*.scss', gulp.series([ 'minify:css']));
}));
// watch for js file change and compile on save.
gulp.task('js:watch', gulp.series(['minify:js','minify:gutenberg-css'], function () {
	gulp.watch([
		'./dev/js/**/*.js',
		'./dev/js/**/**/*.scss',
		'!./dev/js/**/*min.js'
	], gulp.series(['minify:js','minify:gutenberg-css']));
}));


//--------------------------------------------------------------------SERIES
// Watch SASS and JS files for changes and compile them.
gulp.task('all:watch', gulp.parallel(['sass:watch', 'js:watch']));
// Minify all files
gulp.task('minify', gulp.parallel(['minify:css', 'minify:js','minify:gutenberg-css']));
// Execute everything needed for the live version
gulp.task('compile', gulp.series(['minify']));

// generate SASS documentation
gulp.task('sassdoc', function () {
	var options = {
		dest: 'docs/sass',
		verbose: true,
		display: {
			access: ['public', 'private'],
			alias: true,
			watermark: false,
		},
		theme: "doc-templates/sassdoc/index.js"
	};
  
	pump([
		gulp.src('./dev/sass/*.+(sass|scss)'),
		converter({
			from: 'sass',
			to: 'scss',
		}),
		sassdoc(options)
	]);
});


// Generate all the documentations.
gulp.task('generatedoc', gulp.series(['sassdoc']));