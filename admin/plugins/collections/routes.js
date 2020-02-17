import index from './index.js'
// import index from './Overview.js'
import TypeBuilder from './types/TypeBuilder.js'
import Form from './Form.js'

export default [
    {
        path: '/collections', component: index, props: (route) => ({ name: "collections" }),
        children: [
            {
                path: 'new',
                component: Form,
                props: (route) => ({ 
                    showDialog: true 
                }),
            }
        ]
    },
    {
        path: '/collections/:name',
        component: TypeBuilder,
        props: true,
    },
    // {
    //     path: '/collections/new',
    //     component: TypeBuilder,
    //     props: true,
    // },
    {
        path: '/collections/edit/:name',
        component: TypeBuilder,
        props: true,
    }
]