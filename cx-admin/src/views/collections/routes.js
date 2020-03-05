import index from './index.vue'
// import index from './Overview.vue'
import Form from './Form.vue'

export default [
  {
    path: '/collections',
    component: index,
    props: (route) => ({ name: 'collections' }),
    children: [
      {
        path: 'new',
        component: Form,
        props: (route) => ({
          showDialog: true
        })
      }
    ]
  }
]
