const browsersync = require("browser-sync");
const gulp = require("gulp");
const phpConnect = require('gulp-connect-php');
const plumber = require("gulp-plumber");
const sass = require("gulp-sass");

function connectsync() {
    phpConnect.server({
        port: 8000,
        keepalive: true,
        base: "."
    }, function (){
        browsersync({
            proxy: '127.0.0.1:8000',
            notify: false
        });
    });
}

function browserSyncReload(done) {
    browsersync.reload();
    done();
}

function watchFiles() {
    gulp.watch("./**/*.php", browserSyncReload);
    gulp.watch("./**/*.html", browserSyncReload);
    gulp.watch("./assets/scss/**/*.scss", css);
}

function css() {
    return gulp
    .src("./assets/scss/**/*.scss")
    .pipe(plumber())
    .pipe(sass({ outputStyle: "expanded" }))
    .pipe(gulp.dest("./assets/css/"))
    .pipe(browsersync.stream());
 }

const watch = gulp.parallel([watchFiles, connectsync]);

exports.default = watch;