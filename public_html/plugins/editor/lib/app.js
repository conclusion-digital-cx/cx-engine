import "./Grid.js"
import editable from './editable.js'

// Register globally
Vue.component('editable', editable)

const html = (strings) => strings[0]

// ==============
// Get globals
// ==============
// console.log(window.settings)
// TODO ?
// BE AWARE GLOBAL !!
const getPageId = () => {
  return window.settings.pageId || 1
}

const app = {
  components: {
    editable
  },

  el: '#app',
  vuetify: new Vuetify({
    icons: {
      iconfont: 'md', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
  }),

  data: {
    // blocksJs,
    message: 'You loaded this page on ' + new Date().toLocaleString(),
    blocks: [],
    page: {
      url: ''
    },
    form: {
      preview: false,
      editor: localStorage.getItem('editor') !== 'false',
    },
    loading: false,
    snackbar: false,
    // blocks: [],
    components: []
  },

  watch: {
    editor(newValue) { localStorage.setItem('editor', newValue) }
  },

  async created() {
    const id = getPageId()

    let resp
    resp = await fetch(`/api/pages/${id}`)
    this.page = await resp.json()

    // Initial (from server pageId ?)
    this.blocks = [
      ...this.page.blocks || []
    ]

    // Dynamicly load blocks
    const components = await fetch(`/api/blocksjs`).then(elem => elem.json())
    components.forEach(component => {
      import(component)
        .then((module) => {
          this.components.push(module.default)
        });
    })
  },

  methods: {
    // Save page
    async save(page) {
      const { blocks } = this

      // Render client side ;)
      const bodyRenderedBlocks = blocks.map(block => block.render)

      // Create page object
      const doc = {
        ...page,
        body: bodyRenderedBlocks.join(''),
        // blocks: JSON.stringify(blocks)
        blocks
      }

      try {
        this.loading = true
        const id = getPageId()
        const resp = await fetch(`/api/pages/${id}`, {
          method: 'PUT',
          body: JSON.stringify(doc),
          headers: {
            'Content-Type': 'application/json'
          },
        })
      } finally {
        this.loading = false
        this.snackbar = true
      }
      // const json = await resp.json()
    },

    onInput(newblocks) {
      // console.log("newblocks",newblocks)
    }
  },

  template: html`
  <v-app>
    <v-content>
    <!-- {{blocks}} -->

<!-- <v-btn icon @click="editor = !editor"><v-icon>add</v-icon></v-btn> -->
<div class="d-flex">
<!-- <v-switch class="mr-5" label="editor" v-model='form.editor'></v-switch> -->
<v-switch label="preview" v-model='form.preview'></v-switch>
<v-switch label="code" v-model='form.code'></v-switch>

<div v-if="form.code">
{{blocks}}
</div>

<v-btn
@click="save(page)"
class="ma-4"
              color="pink"
              outlined
              :loading="loading"
            >
              <v-icon>save</v-icon> Save page
            </v-btn>
<!-- <v-btn
@click="save(page)"
              dark
              fab
              bottom
              right
              color="pink"
              :loading="loading"
            >
              <v-icon>save</v-icon>
            </v-btn> -->
</div>

    <v-snackbar
      v-model="snackbar"
      :timeout="1000"
    >
      Saved
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <editable 
      :preview="form.preview" 
      :components="components" 
      v-model="blocks" 
      @input="onInput"/>
    </v-content>

  </v-app>
  `
}

const vue = new Vue(app)