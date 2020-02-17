const html = (strings) => strings[0]

export default {
    name: 'repeater',

    data() {
        return {
            content: ""
        }
    },
    methods: {
        onEnter() {
            this.$emit('done', this.content)
        },
    },

    template: html`
    <div>
        Collection: 
        <input v-model="content" type="text" placeholder="e.g. news"/>
        <v-btn @click="$emit('save',content)">save</v-btn>
    </div>
    `
}