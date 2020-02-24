module.exports = {
  output: {
    dir: '../admin',
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
