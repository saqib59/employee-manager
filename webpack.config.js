const path = require('path');

module.exports = {
  entry: './blocks/employee-table-block.js', // Adjust the entry path as needed
  output: {
    filename: 'employee-block.build.js', // Output filename
    path: path.resolve(__dirname, 'dist'), // Output directory
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-react'],
          },
        },
      },
    ],
  },
  devServer: {
    static: {
        directory: path.resolve(__dirname, 'dist'), // Serve from the output directory
      },
    port: 8080, // Port for the development server
    open: false, // Automatically open a browser window
  },
};
