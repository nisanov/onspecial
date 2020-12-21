require('dotenv').config();

const fancyLog = require('fancy-log');
const tsify = require('tsify');
const del = require('delete');
const gulp = require('gulp');
const sass = require('gulp-sass');
const revision = require('gulp-rev');
const sourcemaps = require('gulp-sourcemaps');
const buffer = require('gulp-buffer');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const source = require('vinyl-source-stream');
const envify = require('envify/custom');
const browserify = require('browserify');

sass.compiler = require('dart-sass');

fancyLog.info(`Build environment: ${process.env.APP_ENV}`);

const clean = (cb) => del([
  './public/css/*.css',
  './public/css/*.css.map',
  './public/js/*.js',
  './public/js/*.js.map',
  './public/img/*.*',
  './manifest.json',
], cb);

const bundleCssApp = (cb) => (process.env.APP_ENV === 'production'
  ? gulp
    .src('./public_src/css/app.scss')
    .pipe(sass.sync({ includePaths: './node_modules/', outputStyle: 'compressed' }).on('error', fancyLog))
    .pipe(revision())
    .pipe(gulp.dest('./public/css'))
    .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
    .pipe(gulp.dest('./public'))
    .on('end', cb)
  : gulp.src('./public_src/css/app.scss')
    .pipe(sourcemaps.init())
    .pipe(sass.sync({ includePaths: './node_modules/', outputStyle: 'expanded' }).on('error', fancyLog))
    .pipe(revision())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./public/css'))
    .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
    .pipe(gulp.dest('./public'))
    .on('end', cb)
);

const bundleCssVendor = (cb) => gulp
  .src('./node_modules/notyf/notyf.min.css')
  .pipe(concat({ path: 'vendor.css', cwd: '' }))
  .pipe(revision())
  .pipe(gulp.dest('./public/css'))
  .pipe(
    revision.manifest({
      base: './public',
      path: './manifest.json',
      merge: true,
    }),
  )
  .pipe(gulp.dest('./public'))
  .on('end', cb);

const browserifyTask = browserify({ entries: 'public_src/js/main.ts', debug: process.env.APP_ENV !== 'production' });
const bundleJsApp = (cb) => (process.env.APP_ENV === 'production'
  ? browserifyTask
    .plugin(tsify).transform(envify({ APP_BASE_URL: process.env.APP_BASE_URL }))
    .bundle()
    .on('error', fancyLog)
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(revision())
    .pipe(gulp.dest('public/js'))
    .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
    .pipe(gulp.dest('./public'))
    .on('end', cb)
  : browserifyTask
    .plugin(tsify).transform(envify({ APP_BASE_URL: process.env.APP_BASE_URL }))
    .bundle()
    .on('error', fancyLog)
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(revision())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('public/js'))
    .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
    .pipe(gulp.dest('./public'))
    .on('end', cb)
);

const bundleJsVendor = (cb) => gulp
  .src('./node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js')
  .pipe(concat({ path: 'vendor.js', cwd: '' }))
  .pipe(revision())
  .pipe(gulp.dest('./public/js'))
  .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
  .pipe(gulp.dest('./public'))
  .on('end', cb);

const processImages = (cb) => gulp
  .src('./public_src/img/**/*.*')
  .pipe(revision())
  .pipe(gulp.dest('./public/img'))
  .pipe(revision.manifest({ base: './public', path: './manifest.json', merge: true }))
  .pipe(gulp.dest('./public'))
  .on('end', cb);

const watchApp = () => {
  gulp.watch('./public_src/js/**/*.ts', bundleJsApp);
  gulp.watch('./public_src/css/**/*.scss', bundleCssApp);
};

clean.displayName = 'clean:all';
bundleCssApp.displayName = 'bundle:css:app';
bundleCssVendor.displayName = 'bundle:css:vendor';
bundleJsApp.displayName = 'bundle:js:app';
bundleJsVendor.displayName = 'bundle:js:vendor';
processImages.displayName = 'images:process';
watchApp.displayName = 'watch:app';

gulp.task(clean);
gulp.task(bundleCssApp);
gulp.task(bundleCssVendor);
gulp.task(bundleJsApp);
gulp.task(bundleJsVendor);
gulp.task(processImages);
gulp.task(watchApp);

gulp.task('bundle:css', gulp.series('bundle:css:app', 'bundle:css:vendor'));
gulp.task('bundle:js', gulp.series('bundle:js:app', 'bundle:js:vendor'));

gulp.task('build', gulp.series('clean:all', 'bundle:css', 'bundle:js', 'images:process'));
gulp.task('watch', gulp.series('build', 'watch:app'));
