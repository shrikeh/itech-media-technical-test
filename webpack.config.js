'use strict';

const path = require('path');
const HtmlWebpackPlugin = require("html-webpack-plugin");

module.exports = {
  mode: "development",
  entry: path.resolve(__dirname, "public/src/App.tsx"),
  output: {
    path: path.resolve(__dirname, "dist"),
    filename: "js/bundle.js",
    publicPath: "/", // string
  },
  // Enable sourcemaps for debugging webpack's output.
  devtool: "source-map",

  resolve: {
    alias: {
      "@app/components": path.resolve(__dirname, "public/src/components")
    },
    // Add '.ts' and '.tsx' as resolvable extensions.
    extensions: [".ts", ".tsx", ".js", ".jsx"]
  },

  module: {
    rules: [
      {
        test: /\.html$/,
        use: [
          // apply multiple loaders and options
          "htmllint-loader",
          {
            loader: "html-loader"
          }
        ]
      },
      {
        test: /\.tsx$/,
        loader: 'babel-loader!ts-loader',
      },
      {
        test: /\.ts$/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'awesome-typescript-loader'
          }
        ]
      },
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            cacheDirectory: true,
            cacheCompression: false,
            envName: "development"
          }
        }
      },
      // All output '.js' files will have any sourcemaps re-processed by 'source-map-loader'.
      {
        enforce: "pre",
        test: /\.js$/,
        loader: "source-map-loader"
      }
    ]
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: path.resolve(__dirname, "public/index.html"),
      inject: false
    })
  ]
};
