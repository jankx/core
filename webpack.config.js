const path = require("path");

module.exports = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  entry: [__dirname + "/assets/sass/jankx.scss", __dirname + "/assets/sass/editor.scss"],
  output: {
    path: path.resolve(__dirname, "assets"),
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
