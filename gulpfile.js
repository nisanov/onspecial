require('dotenv').config();

const fancyLog = require('fancy-log');
const watchify = require('watchify');
const tsify = require('tsify');
const del = require('delete');
const gulp = require('gulp');
const sass = require('gulp-sass');
const revision = require('gulp-rev');
const sourcemaps = require('gulp-sourcemaps');
const resolveUrl = require('gulp-resolve-url');
const buffer = require('gulp-buffer');
const concat = require('gulp-concat');
const source = require('vinyl-source-stream');
const envify = require('envify/custom');
const browserify = require('browserify');

sass.compiler = require('dart-sass');

function clean(cb) {
  del(
    [
      './public/css/*.css',
      './public/css/*.css.map',
      './public/js/*.js',
      './public/js/*.js.map',
      './public/img/*.*',
      './manifest.json',
    ],
    cb,
  );
}

function bundleCss(cb) {
  gulp
    .src('./public_src/css/app.scss')
    .pipe(sourcemaps.init())
    .pipe(
      sass
        .sync({ includePaths: './node_modules/', outputStyle: 'expanded' })
        .on('error', sass.logError),
    )
    .pipe(resolveUrl())
    .pipe(revision())
    .pipe(sourcemaps.write('.'))
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
}

function vendorCss(cb) {
  gulp
    .src([
      './node_modules/notyf/notyf.min.css',
    ])
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
}

function watchCss() {
  gulp.watch('./public_src/css/**/*.scss', bundleCss);
}

const watchedBrowserify = watchify(browserify(
  {
    basedir: '.',
    debug: true,
    entries: ['public_src/js/main.ts'],
    cache: {},
    packageCache: {},
  },
).plugin(tsify).transform(envify({
  APP_BASE_URL: process.env.APP_BASE_URL,
})));

function bundleJs() {
  return watchedBrowserify
    .bundle()
    .on('error', fancyLog)
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(revision())
    .pipe(gulp.dest('public/js'))
    .pipe(
      revision.manifest({
        base: './public',
        path: './manifest.json',
        merge: true,
      }),
    )
    .pipe(gulp.dest('./public'));
}

function vendorJs(cb) {
  gulp
    .src([
      './node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js',
    ])
    .pipe(concat({ path: 'vendor.js', cwd: '' }))
    .pipe(revision())
    .pipe(gulp.dest('./public/js'))
    .pipe(
      revision.manifest({
        base: './public',
        path: './manifest.json',
        merge: true,
      }),
    )
    .pipe(gulp.dest('./public'))
    .on('end', cb);
}

watchedBrowserify.on('update', bundleJs);
watchedBrowserify.on('log', fancyLog);

function images(cb) {
  gulp
    .src('./public_src/img/**/*.*')
    .pipe(revision())
    .pipe(gulp.dest('./public/img'))
    .pipe(
      revision.manifest({
        base: './public',
        path: './manifest.json',
        merge: true,
      }),
    )
    .pipe(gulp.dest('./public'))
    .on('end', cb);
}

gulp.task('default', gulp.series(
  clean,
  bundleCss,
  vendorCss,
  bundleJs,
  vendorJs,
  images,
  watchCss,
));

gulp.task('build', gulp.series(
  clean,
  bundleCss,
  vendorCss,
  bundleJs,
  vendorJs,
  images,
));
