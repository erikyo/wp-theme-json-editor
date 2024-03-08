const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
  ...defaultConfig,
  entry: {
    'theme-json-editor': path.resolve(
      process.cwd(),
      `src/index.tsx`
    ),
  },
  output: {
    path: path.join( __dirname, './build' ),
  },
  resolve: {
    extensions: ['.ts', '.tsx', '.js'],
    alias: {
      'markdown-it': 'markdown-it/dist/markdown-it.min.js'
    }
  },
}
