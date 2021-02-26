const { src, dest, watch, series } = require('gulp');

const files = [
  'dev/**/*.php',
  'dev/**/*.js',
  'dev/**/*.json',
  'dev/**/*.png',
  'dev/**/*.jpg',
  'dev/**/*.svg',
  'dev/**/*.ini',
];

function copyPhpFiles() {
  return src(files)
    .pipe(dest('dist'));
}

exports.php = () => {
  return watch(files,
  { events: 'all' }, copyPhpFiles);
};
