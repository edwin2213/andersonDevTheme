/********************************************************
 ********************* Gulp Modules *********************
 ********************************************************/

var gulp 			= require( 'gulp' ),
	livereload 		= require( 'gulp-livereload' ),
	gulpLoadPlugins = require( 'gulp-load-plugins' ),	
	plugins;
	
	plugins = gulpLoadPlugins();
	

//Error Handling
var onError = function( err ) {
  console.log( 'An error occurred:', err.message );
  this.emit( 'end' );
}

// Jshint outputs any kind of javascript problems you might have
// Only checks javascript files inside /src directory
gulp.task( 'jshint', function() {
  return gulp.src( './js/*.js' )
	.pipe(plugins.jshint())
	.pipe(plugins.jshint.reporter( 'jshint-stylish' ))
	// .pipe(jshint.reporter('gulp-jshint-html-reporter', {
	//   filename: __dirname + '/js-errors.html'
	// }));
    .pipe(livereload() );
});

//Minify JS
//gulp.task( 'scripts', function() {
  //return gulp.src( './js/scripts.js' )
    //.pipe(uglify() )
    //.pipe(rename( { suffix: '.min' } ) )
    //.pipe(gulp.dest( './min/' ) )

//} );

//Getting Sassy
gulp.task( 'sassy', function() {
  return gulp.src( './sass/**/*.scss' )
	.pipe(plugins.plumber( { errorHandler: onError }))
	.pipe(plugins.sass())
	.pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
	.pipe(gulp.dest( '.' ))
	//.pipe(minifycss())
	//.pipe(rename({suffix: '.min' }))
	//.pipe(gulp.dest('./min/'))
	.pipe(livereload());
} );

gulp.task( 'images', function() {
	
	return gulp.src('./images/**/*.{jpg,gif,png}')
		//.pipe(newer('images'))
		.pipe(plugins.imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
		.pipe(gulp.dest('images'));	
});


//NSAing your files
gulp.task( 'watch', function() {
  livereload.listen("host: 10.10.10.11");
  gulp.watch('./js/*.js', [ 'jshint' ] )
  gulp.watch('./sass/**/*.scss', [ 'sassy' ] );
  
  gulp.watch('./**/*.php' ).on( 'change', function( file ) {
	livereload.changed( file );
  } );
} );

gulp.task( 'default', ['watch'], function() {});