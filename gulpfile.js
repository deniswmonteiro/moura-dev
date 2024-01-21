var gulp = require("gulp");
var minifyCSS = require("gulp-minify-css");
var svgstore = require("gulp-svgstore");
var svgmin = require("gulp-svgmin");
var path = require("path");
var rename = require("gulp-rename");
var htmlmin = require("gulp-htmlmin");
var fileinclude = require("gulp-file-include");
var myip = require("quick-local-ip");
var connect = require("gulp-connect");
var clean = require("gulp-clean");
var fs = require("fs");
var minify = require("gulp-minify");
var replace = require("gulp-replace");
var sass = require("gulp-sass")(require("sass"));
var production = false;
var root = production
  ? "https://www.moura.com.br/"
  : "http://localhost/moura-dev/";

function swallowError(error) {
  //If you want details of the error in the console
  console.log(error.toString());

  this.emit("end");
}

// GERAR SVG
// limpando svg
gulp.task("clean-svg", (done) => {
  fs.readFile("assets/images/svg/svg.svg", (_, data) => {
    if (data) {
      gulp.src("assets/images/svg/svg.svg", { read: false }).pipe(clean());
    }
  });

  done();
});

// gerando svg
gulp.task(
  "svg",
  gulp.series("clean-svg", function (done) {
    gulp
      .src("assets/images/svg/**/*.svg")
      .pipe(
        svgmin(function (file) {
          var prefix = path.basename(
            file.relative,
            path.extname(file.relative)
          );
          return {
            plugins: [
              {
                cleanupIDs: {
                  prefix: prefix + "-",
                  minify: true,
                },
              },
            ],
          };
        })
      )
      .pipe(svgstore())
      .pipe(gulp.dest("assets/images/svg"));

    done();
  })
);

gulp.task("fileinclude", function (done) {
  gulp
    .src(["_matriz/html/**/index.html"])
    .pipe(
      fileinclude({
        prefix: "@@",
        basepath: "@root",
      })
    )
    .pipe(gulp.dest("_matriz/build/"))
    .pipe(connect.reload());

  done();
});

gulp.task("htmlreload", function (done) {
  gulp.src("_matriz/html/**/*.html").pipe(connect.reload());

  done();
});

gulp.task("connect", function (done) {
  connect.server({
    host: myip.getLocalIP4(),
    livereload: true,
  });

  done();
});

// MINIFICAR HTML
gulp.task("html", function (done) {
  gulp
    .src([
      "assets/html/**/*.html",
      "!assets/html/**/*min.html",
      "!assets/html/email/*.html",
    ])
    .pipe(htmlmin({ collapseWhitespace: true, minifyJS: true }))
    .pipe(
      rename(function (path) {
        path.basename += ".min";
        path.extname = ".html";
      })
    )
    .pipe(gulp.dest("assets/html/"))
    .pipe(connect.reload());

  done();
});

//Minificar JS
gulp.task("js", function (done) {
  gulp
    .src(["assets/js/**/*.js", "!assets/js/**/*.min.js"])
    .pipe(
      minify({
        ext: {
          src: ".js",
          min: ".min.js",
        },
      })
    )
    .pipe(gulp.dest("assets/js"));
  done();
});

// Sass
gulp.task("sass", function (done) {
  gulp
    .src("assets/sass/main.scss")
    .pipe(sass({ outputStyle: "compressed" }))
    .on("error", swallowError)
    .pipe(minifyCSS())
    .pipe(replace("../", root + "/assets/"))
    .pipe(gulp.dest("./assets/css"))
    .pipe(connect.reload());

  done();
});

// // WATCH SASS, SCRIPTS E LIVERELOAD
gulp.task("watch", function (done) {
  gulp.watch(
    ["_matriz/html/**/*.html", "!_matriz/html/**/*min.html"],
    gulp.series(["htmlreload", "fileinclude"])
  );
  // gulp.watch(["assets/html/**/*.html", "!assets/html/**/*min.html"], ["html"]);
  gulp.watch("assets/sass/**/*.scss", gulp.series("sass"));

  done();
});

gulp.task(
  "default",
  gulp.series(["sass", "fileinclude", "svg", "connect", "watch"])
);
