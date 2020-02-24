// Globally register all base components for convenience, because they
// will be used very frequently. Components are registered using the
// PascalCased version of their file name.

import Vue from 'vue'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

// https://webpack.js.org/guides/dependency-management/#require-context
const requireComponent = require.context(
  './', // Look for files in the current directory
  true, // include subdirectories
  // Only include "_base-" prefixed .vue files
  //   /_base-[\w-]+\.vue$/
  //   /[\w-]+\.vue$/
  /\.vue$/
)

// console.log(requireComponent.keys())
// console.log('./Layout/RowFlex.vue'.replace(/^.*[\\/]/, ''))

// For each matching file name...
requireComponent.keys().forEach((fileName) => {
  // Get the component config
  const componentConfig = requireComponent(fileName)
  // Get the PascalCase version of the component name
  const componentName = upperFirst(
    camelCase(
      fileName
        .replace(/^.*[\\/]/, '')
        .replace(/\.\w+$/, '')
    )
  )
  // Globally register the component
  Vue.component(componentName, componentConfig.default || componentConfig)
})
