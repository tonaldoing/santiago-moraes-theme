// Minifica cada assets/js/**/*.js (excepto *.min.js) a su par .min.js con esbuild.
import { build } from 'esbuild';
import { readdirSync, statSync } from 'node:fs';
import { join } from 'node:path';

const root = 'assets/js';

function walk( dir ) {
	return readdirSync( dir ).flatMap( ( name ) => {
		const full = join( dir, name );
		if ( statSync( full ).isDirectory() ) return walk( full );
		return name.endsWith( '.js' ) && ! name.endsWith( '.min.js' ) ? [ full ] : [];
	} );
}

const entryPoints = walk( root );

await build( {
	entryPoints,
	outdir: root,
	outbase: root,
	outExtension: { '.js': '.min.js' },
	minify: true,
	target: 'es2018',
	logLevel: 'warning',
} );

console.log( `${ entryPoints.length } archivos minificados` );
