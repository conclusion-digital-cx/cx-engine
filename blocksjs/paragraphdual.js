const html = (strings) => strings[0]

export default {
    name: 'paragraphdual',
    data() {
        return {
            content1: "",
            content2: ""
        }
    },
    methods: {
        onEnter(e) {
            this.$emit('input', this.$data)
            this.$emit('render', `<p>${this.content1}</p><p>${this.content2}</p>`)
        },
    },

    template: html`
    <div>
    <textarea 
        class="editor-post-title__input" 
        placeholder="Write your story here..."
        v-model="content1"
        @keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    <textarea 
        class="editor-post-title__input" 
        placeholder="Write your story here..."
        v-model="content2"
        @keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    </div>
    `
}