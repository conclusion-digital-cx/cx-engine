import form from './form.js'

export default [
    {
        text: 'Settings',
        path: '/settings',
        component: form, 
        props: (route) => ({ name: "media" })
    },
]