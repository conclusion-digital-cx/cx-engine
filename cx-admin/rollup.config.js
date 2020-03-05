// import commonjs from 'rollup-plugin-commonjs' // Convert CommonJS modules to ES6
import vue from 'rollup-plugin-vue' // Handle .vue SFC files
import alias from '@rollup/plugin-alias'
import requireContext from 'rollup-plugin-require-context'

// import buble from 'rollup-plugin-buble' // Transpile/polyfill with reasonable browser support
export default {
  input: 'src/main.js', // Path relative to package.json
  output: {
    name: 'CxAdmin',
    exports: 'named',
    // Also note 'es' not 'iife', since a library exports something, unlike an application.
    format: 'es'
    // format: 'umd'
  },
  external: [
    'vue',
    'vue-router',
    'vuetify',
    'vuex',
    'vuex-persistedstate',
    'axios'
  ],
  // globals: {
  //   vue: 'Vue',
  //   'vue-router': 'VueRouter',
  //   vuetify: 'Vuetify',
  //   vuex: 'Vuex',
  //   'vuex-persistedstate': 'VuexPersistedstate',
  //   axios: 'Axios'
  // },
  //   extensions: ['.js', '.vue'], // NOT WORKING
  plugins: [
    requireContext(),
    alias({
      entries: [
        { find: '@', replacement: __dirname + '/src' }
      ]
    }),
    // commonjs(),
    vue({
      css: true, // Dynamically inject css as a <style> tag
      compileTemplate: true // Explicitly convert template to render function
    })
    // buble() // Transpile to ES5
  ]
}
