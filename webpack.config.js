const  path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    
    //watch: true,
    mode: 'development',
    //mode: 'production',
    devtool:"source-map",
    module:{
        rules: [
          {
            test: /\.(gif|png|jpg|jpeg|svg)$/i,
            use: [
              'file-loader',
              {
                loader: 'image-webpack-loader',
                options: {
                  bypassOnDebug: true, // webpack@1.x
                  disable: true, // webpack@2.x and newer
                },
              },
            ],
          },
            {
              test: /\.vue$/,
              loader: 'vue-loader'
            },
            {
              test: /\.scss$/,
              loader: 'style-loader!css-loader!sass-loader'
            },
            {
              test: /\.css$/,
              use: [
                'vue-style-loader',
                'css-loader'
              ]
            },
            {
              test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
              use: [
                {
                  loader: 'file-loader',
                  options: {
                    name: '[name].[ext]',
                    outputPath: 'fonts/'
                  }
                }
              ]
            },
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: [{
                  loader: 'babel-loader',
                  options: {
                    presets: ['@babel/preset-env']
                  }
                },"eslint-loader"]
              }
        ]
    },
    
    entry: {
        main: "./src/main.js",
        login:"./src/login.js"
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'dist'),
    },
    
    plugins: [
        new VueLoaderPlugin(),
        new CopyPlugin({patterns:[
            { from: './src/lib', to: 'lib' },          
            { from: './src/*.html', to: "[name].[ext]" },
            { from: './src/*.php', to: "[name].[ext]" },
			      { from: './src/assets', to: 'assets' },
        ]})
    ]
};