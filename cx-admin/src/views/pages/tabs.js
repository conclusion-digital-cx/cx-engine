import Table from './components/Table.vue'

export default [
  {
    tab: 'Published',
    path: '/pages/published',
    component: Table,
    props: (route) => ({
      name: 'pages',
      value: route.query
    })
  },
  {
    tab: 'Draft',
    path: '/pages/draft',
    component: Table,
    props: (route) => ({
      name: 'pages',
      value: route.query
    })
  },
  {
    tab: 'Trash',
    path: '/pages/trash',
    component: Table,
    props: (route) => ({
      name: 'pages',
      value: route.query
    })
  }
]
