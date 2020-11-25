"use strict";

const del = require("delete");
const gulp = require("gulp");
const sass = require("gulp-sass");
const babel = require("gulp-babel");
const concat = require("gulp-concat");
const rollup = require("gulp-better-rollup");
const sourcemaps = require("gulp-sourcemaps");
const resolveUrl = require("gulp-resolve-url");
const revision = require("gulp-rev");

sass.compiler = require("dart-sass");

function clean(cb) {
  del(
    [
      "./public/css/*.css",
      "./public/css/*.css.map",
      "./public/js/*.js",
      "./public/js/*.js.map",
      "./public/img/*.*",
      "./manifest.json",
    ],
    cb
  );
}

function cssTranspileApplication(cb) {
  gulp
    .src("./public_src/css/app.scss")
    .pipe(sourcemaps.init())
    .pipe(
      sass
        .sync({ includePaths: "./node_modules/", outputStyle: "expanded" })
        .on("error", sass.logError)
    )
    .pipe(resolveUrl())
    .pipe(revision())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./public/css"))
    .pipe(
      revision.manifest({
        base: "./public",
        path: "./manifest.json",
        merge: true,
      })
    )
    .pipe(gulp.dest("./public"))
    .on("end", cb);
}

function cssTranspileVendor(cb) {
  gulp
    .src([
      "./node_modules/notyf/notyf.min.css",
      "./node_modules/basiclightbox/dist/basicLightbox.min.css",
    ])
    .pipe(concat({ path: "vendor.css", cwd: "" }))
    .pipe(revision())
    .pipe(gulp.dest("./public/css"))
    .pipe(
      revision.manifest({
        base: "./public",
        path: "./manifest.json",
        merge: true,
      })
    )
    .pipe(gulp.dest("./public"))
    .on("end", cb);
}

function jsTranspileApplication(cb) {
  gulp
    .src("./public_src/js/app.js")
    .pipe(sourcemaps.init())
    .pipe(rollup({ plugins: [babel()] }, "umd"))
    .pipe(revision())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./public/js"))
    .pipe(
      revision.manifest({
        base: "./public",
        path: "./manifest.json",
        merge: true,
      })
    )
    .pipe(gulp.dest("./public"))
    .on("end", cb);
}

function jsTranspileVendor(cb) {
  gulp
    .src([
      "./node_modules/notyf/notyf.min.js",
      "./node_modules/axios/dist/axios.min.js",
      "./node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js",
      "./node_modules/basiclightbox/dist/basicLightbox.min.js",
    ])
    .pipe(concat({ path: "vendor.js", cwd: "" }))
    .pipe(revision())
    .pipe(gulp.dest("./public/js"))
    .pipe(
      revision.manifest({
        base: "./public",
        path: "./manifest.json",
        merge: true,
      })
    )
    .pipe(gulp.dest("./public"))
    .on("end", cb);
}

function processImages(cb) {
  gulp
    .src("./public_src/img/**/*.*")
    .pipe(revision())
    .pipe(gulp.dest("./public/img"))
    .pipe(
      revision.manifest({
        base: "./public",
        path: "./manifest.json",
        merge: true,
      })
    )
    .pipe(gulp.dest("./public"))
    .on("end", cb);
}

function watch() {
  gulp.watch("./public_src/css/**/*.scss", cssTranspileApplication);
  gulp.watch("./public_src/js/**/*.js", jsTranspileApplication);
}

function publish(cb) {
  cb();
}

exports.watch = watch;
exports.publish = publish;
exports.clean = clean;
exports.build = gulp.series(
  clean,
  cssTranspileApplication,
  cssTranspileVendor,
  jsTranspileApplication,
  jsTranspileVendor,
  processImages
);
