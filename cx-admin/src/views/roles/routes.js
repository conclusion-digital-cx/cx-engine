import List from './List'
import Form from './form'

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
