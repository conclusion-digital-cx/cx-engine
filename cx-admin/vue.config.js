module.exports = {
  productionSourceMap: false,
  publicPath: process.env.NODE_ENV === 'production'
    ? ''
    : '',
  configureWebpack: config => {
    // const isCdn = process.env.VUE_APP_CDN === 'true'
    const isExternals = process.env.VUE_APP_EXTERNALS === 'true' || true

    if (isExternals) {
      console.log('Building for CDN')
      // Mock the import statement
      config.externals = {
        vue: 'Vue',
        vuetify: 'Vuetify',
        'vuetify/lib': 'Vuetify',
        'vuetify/dist/vuetify.min.css': 'null'
      }
    } else {
      console.log('Building for Offline')
    }
  }
}
