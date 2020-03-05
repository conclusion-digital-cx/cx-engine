import List from './List.vue'
import Form from './form.vue'

export default [
  { path: '/roles', component: List, props: true },
  {
    path: '/roles/:name',
    // path: '/typebuilder/:name/:id',
    component: Form,
    props: (route) => ({
      ...route.params
      // id: route.query.id
    // type: route.query.type || 'missing'
    })
  }
]
