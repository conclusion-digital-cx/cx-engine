
const html = (strings) => strings[0]

export default {
    name: 'introduction',
    props: {
        value: { type: String }
    },

    methods: {
        onEnter(e) {
            this.$emit('finish')
        },
        onInput(e) {
            // console.log(e)
            this.$emit('input', e)
            this.$emit('render', `
            <div class="introduction-text-content col-12 col-xl-8 offset-xl-2">
            <p>${this.value}</p>
            </div>`)
        },
    },

    template: html`
    <div class="introduction-text-content col-12 col-xl-8 offset-xl-2">
        <p editable="true">{{value}}
            <!-- TODO replace by slot -->
            <!-- <slot/> -->
            <!-- <textarea 
        class="editor-post-title__input" 
        placeholder="Write your story here..."
        :value="value"
        @input="onInput($event.target.value)"
        @keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%;">
    </textarea> -->
        </p>
    </div>
    `
}