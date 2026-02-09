/**
 * Configure webpack for development
 */
const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );

const webpackConfig = ( env ) => {
  return {
    entry: {
      admin: './src/admin/main.js',
      store: './src/store/main.js',
    },
    externals: {
      jquery: 'jQuery'
    },
    mode: env.production ? 'production' : 'development',
    module: {
      rules: [
        {
          test: /\.js$/,
          use: 'babel-loader'
        },
        {
          test: /\.styl$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                sourceMap: true,
              },
            },
            {
              loader: 'stylus-loader',
              options: {
                sourceMap: true,
              },
            },
          ],
        }
      ],
    },
    plugins: [
      new MiniCssExtractPlugin( {
        filename: 'woo-asaas-[name].css',
      } ),
    ],
    output: {
      path: path.resolve(__dirname, 'dist'),
      filename: 'woo-asaas-[name].js'
    }
  }
};

module.exports = ( env ) => {
  return [
    webpackConfig( env ),
  ];
};
