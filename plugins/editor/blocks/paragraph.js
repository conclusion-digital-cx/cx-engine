const html = (strings) => strings[0]

export default {
    name: 'paragraph',
    data() {
        return {
            content: ""
        }
    },
    methods: {
        onEnter(e) {
            e.preventDefault()
            this.$emit('done', `<p>${this.content}</p>`)
        },
    },

    template: html`
    <textarea class="editor-post-title__input" 
        placeholder="Write your story here..."
        v-model="content"
        v-on:keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    `
}