const path = require("path");

module.exports = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  entry: [__dirname + "/sass/jankx.scss"],
  output: {
    path: path.resolve(__dirname, "resources"),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [],
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
          {
            loader: "file-loader",
            options: {
              outputPath: "css/",
              name: (process.env.NODE_ENV === 'production' ? "[name].min.css" : "[name].css")
            },
          },
          "sass-loader",
        ],
      },
    ],
  },
};
