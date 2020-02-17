const html = (strings) => strings[0]

export default {
    name: 'heading',
    props: {
        value: { type: String }
    },

    methods: {
        onEnter(e) {
            this.$emit('render', `<h1>${this.value}</h1>`)
        },
        onInput(e) {
            this.$emit('input', e)
        },
    },

    template: html`
    <textarea 
        class="editor-post-title__input" 
        placeholder="your heading here..."
        :value="value"
        @input="onInput($event.target.value)"
        @keydown.enter.prevent="onEnter"
        rows="1"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none; font-size:30px;">
    </textarea>
    `
}