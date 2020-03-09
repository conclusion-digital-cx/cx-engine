// Globally register all base components for convenience, because they
// will be used very frequently. Components are registered using the filename

import Vue from 'vue'

// https://webpack.js.org/guides/dependency-management/#require-context
const requireComponent = require.context(
  './', // Look for files in the @/components directory
  true, // include subdirectories
  // Only include "_base-" prefixed .vue files
  //   /_base-[\w-]+\.vue$/
  //   /[\w-]+\.vue$/
  /\.vue$/
)

// For each matching file name...
requireComponent.keys().forEach(fileName => {
  // Get the component config
  const componentConfig = requireComponent(fileName)
  const componentName =
      fileName
        .replace(/^.*[\\/]/, '') // Remove path
        .replace(/\.\w+$/, '') // Remove extension

  // Globally register the component
  Vue.component(componentName, componentConfig.default || componentConfig)
})
