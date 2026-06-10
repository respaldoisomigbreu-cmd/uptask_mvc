import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js'
}

function css(done) {
    src('src/scss/**/*.scss', { sourcemaps: true })
        .pipe(sass.sync({ // Cambia 'sass' por 'sass.sync'
            api: 'modern',
            silenceDeprecations: ['legacy-js-api'] // Esto fuerza el silencio del aviso
        }).on('error', sass.logError))
        .pipe(dest('public/build/css', { sourcemaps: '.' }));
    done();
}



export function js( done ) {
    src(paths.js)
        .pipe(terser())
        .pipe(dest('./public/build/js'))
    done()
}

export function dev() {
    watch( paths.scss, css );
    watch( paths.js, js );
}

export default series( js, css, dev );
export const build = series( js, css );