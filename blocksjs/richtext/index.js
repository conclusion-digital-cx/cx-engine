const html = (strings) => strings[0]

import pell from './pell.js'

export default {
    name: 'paragraph',
    data() {
        return {
            content: ""
        }
    },

    created() {
        const element = this.$refs.element
        
        pell.init({
            // <HTMLElement>, required
            element,
          
            // <Function>, required
            // Use the output html, triggered by element's `oninput` event
            onChange: html => console.log(html),
          
            // <string>, optional, default = 'div'
            // Instructs the editor which element to inject via the return key
            defaultParagraphSeparator: 'div',
          
            // <boolean>, optional, default = false
            // Outputs <span style="font-weight: bold;"></span> instead of <b></b>
            styleWithCSS: false,
          
            // <Array[string | Object]>, string if overwriting, object if customizing/creating
            // action.name<string> (only required if overwriting)
            // action.icon<string> (optional if overwriting, required if custom action)
            // action.title<string> (optional)
            // action.result<Function> (required)
            // Specify the actions you specifically want (in order)
            actions: [
              'bold',
              {
                name: 'custom',
                icon: 'C',
                title: 'Custom Action',
                result: () => console.log('Do something!')
              },
              'underline'
            ],
          
            // classes<Array[string]> (optional)
            // Choose your custom class names
            classes: {
              actionbar: 'pell-actionbar',
              button: 'pell-button',
              content: 'pell-content',
              selected: 'pell-button-selected'
            }
          })
    },

    methods: {
        onEnter(e) {
            e.preventDefault()
            this.$emit('done', `<p>${this.content}</p>`)
        },
    },

    template: html`
    <textarea ref="element" class="editor-post-title__input" 
        placeholder="Write your story here..."
        v-model="content"
        v-on:keydown.enter.prevent="onEnter"
        style="overflow: hidden; overflow-wrap: break-word; width: 100%; resize: none;">
    </textarea>
    `
}