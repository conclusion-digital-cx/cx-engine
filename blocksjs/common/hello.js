const html = (strings) => strings[0]

export default {
    name: 'hello',

    data() {
        return {
        }
    },

    methods: {
        onEnter() {
            this.$emit('done', this.$refs.content)
        },
    },

    template: html`
    <div ref="content">
       Hello
    </div>
    `
}