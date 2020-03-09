export default [
  {
    path: '/media',
    component: {
      template: `<CollectionList v-bind="$attrs"/>`
    },
    props: (route) => ({ name: 'media' })
  }
]
