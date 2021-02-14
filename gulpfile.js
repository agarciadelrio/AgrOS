const { src, dest, watch, series } = require('gulp');

function copyPhpFiles() {
  return src(['./dev/api/**/*.json', './dev/api/**/*.php','./dev/api/**/*.json','./dev/api/**/*.ini'])
    .pipe(dest('dist/api/'));
}

exports.php = () => {
  return watch(['./dev/api/**/*.json', './dev/api/**/*.php','./dev/api/**/*.json','./dev/api/**/*.ini'],
  { events: 'all' }, copyPhpFiles);
};
