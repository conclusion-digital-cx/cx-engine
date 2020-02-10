const html = (strings) => strings[0]

export default {
    name: 'phuc',

    data() {
        return {
        }
    },
    async created() {
        // const items = await fetch(`/api/menus`).then(elem => elem.json())
        // this.items = items
    },
    methods: {
        onEnter() {
            this.$emit('done', this.$refs.content)
        },
    },

    template: html`
    <div ref="content">
       Phuc
    </div>
    `
}