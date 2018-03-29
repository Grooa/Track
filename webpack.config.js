const path = require('path');
const webpack = require('webpack');

const BUILD_DIR = path.join(__dirname, 'assets/dist');
const SRC_DIR = path.join(__dirname, 'assets/src/');

const config = {
  target: 'web',
  entry: SRC_DIR + '/index.jsx',
  context: __dirname,
  output: {
    path: BUILD_DIR,
    filename: 'bundle.js'
  },
  devtool: 'source-map',
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        include: SRC_DIR,
        exclude: path.join(__dirname, 'node_modules/'),
        use: ["babel-loader"]
      },
      {
        test: /\.jsx$/,
        include: SRC_DIR,
        exclude: path.resolve(__dirname, 'node_modules/'),
        use: ['babel-loader', 'eslint-loader']
      }
    ]
  },
  resolve: {
    modules: [
        SRC_DIR, // IMPORTANT! Include SRC_DIR or webpack cannot import our src-files.
        "node_modules"
    ],
    extensions: ['.js', '.jsx', '.json']
  },

  plugins: [
    new webpack.ProvidePlugin({
      React: 'react' // ReactJS module name in node_modules folder
    })
  ]
};

module.exports = config;
