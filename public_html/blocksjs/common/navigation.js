const html = (strings) => strings[0]

export default {
    name: 'navigation',

    data() {
        return {
            items: [],
        }
    },
    async created() {
        const items = await fetch(`/api/menus`).then(elem => elem.json())
        this.items = items

        // Set html
        this.$emit('done', this.$refs.content)
    },
    methods: {
        onEnter() {
            this.$emit('done', this.$refs.content)
        },
    },

    template: html`
    <div>
        <!-- {{items}} -->
        <div class="topnav" ref="content">
            <a class="active" href="/home">Home</a>
            <div v-for="i in items">
                <a :href="i.url">{{i.title}}</a>
            </div>
        </div>

        <!-- <div v-html="content"/> -->
    </div>
    `
}