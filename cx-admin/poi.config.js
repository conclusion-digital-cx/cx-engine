module.exports = {
  output: {
    fileNames:
    {
      js: 'assets/[name].js',
      css: 'assets/[name].css',
      font: 'assets/[path][name].[ext]',
      image: 'assets/[path][name].[ext]'
    },
    dir: '../public_html/admin',
    publicUrl: './' // Make sure it works on GH pages
  },
  // merged using `webpack-merge` module
  configureWebpack: {

    resolve: {
      extensions: ['.vue']
    },
    externals: {
      vue: 'Vue',
      vuetify: 'Vuetify',
      'vuetify/dist/vuetify.min.css': 'null'
    }
  }
}
