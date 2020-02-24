import index from './index.js'
// import index from './Overview.js'
import Form from './Form.js'

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
