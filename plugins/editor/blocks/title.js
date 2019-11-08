const html = (strings) => strings[0]

export default {
    methods: {
        onEnter() {
            this.$emit('done')
        },
    },

    template: html`
    <textarea class="editor-post-title__input" 
        placeholder="Titel toevoegen" rows="1" 
        v-on:keyup.enter="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    `
}