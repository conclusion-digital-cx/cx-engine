import List from './List.vue'
import Grid from './Grid.vue'
import Form from './form'

export default [
  {
    path: '/content',
    component: Grid,
    props: true
  },
  {
    name: 'table',
    path: '/content/:typeSlug',
    // path: '/content/:typeSlug/:typeId',
    component: List,
    props: (route) => ({
      ...route.params
    }),
    children: [
      {
        path: 'edit/:id',
        component: Form,
        props: (route) => ({
          ...route.params
        })
      },
      {
        path: 'new',
        component: Form,
        props: (route) => ({
          ...route.params
        })
      }
    ]
  }
]
