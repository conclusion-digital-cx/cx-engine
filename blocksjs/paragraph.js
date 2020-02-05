const html = (strings) => strings[0]

export default {
    name: 'paragraph',
    props: {
        value: { type: String }
    },
    // data() {
    //     return {
    //         content: ""
    //     }
    // },
    methods: {
        onEnter(e) {
            // this.$emit('input', this.$data)
            this.$emit('render', `<p>${this.value}</p>`)
        },
        onInput(e) {
            // console.log(e)
            this.$emit('input', e)
        },
    },

    template: html`
    <textarea 
        class="editor-post-title__input" 
        placeholder="Write your story here..."
        :value="value"
        @input="onInput($event.target.value)"
        @keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    `
}