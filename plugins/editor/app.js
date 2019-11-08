import "./Grid.js"
import './utils.js'

// Get blocks
import paragraph from './blocks/paragraph.js'
import list from './blocks/list.js'
const blocksJs = {
  paragraph,
  list
}

const html = (strings) => strings[0]

// ==============
// Get globals
// ==============
console.log(window.settings)
// TODO ?
// BE AWARE GLOBAL !!
const getPageId = () => {
  return window.settings.pageId || 1
}

const blocksBaseUrl = `/blocks`

var app = new Vue({
  components: {
    ...blocksJs
  },

  el: '#app',
  vuetify: new Vuetify({
    icons: {
      iconfont: 'md', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
  }),

  data: {
    blocksJs,
    message: 'You loaded this page on ' + new Date().toLocaleString(),
    blocks: [],
    page: {
      url: ''
    },
    loading: false,
    snackbar: false,
    pageblocks: [],
    editor: localStorage.getItem('editor') !== 'false'
  },

  watch: {
    editor(newValue) { localStorage.setItem('editor', newValue) }
  },

  async created() {
    const id = getPageId()

    let resp
    resp = await fetch(`/api/pages/${id}`)
    this.page = await resp.json()
    this.pageblocks = JSON.parse(this.page.pageblocks) || []

    // TEST
    this.pageblocks = [
      { component: 'paragraph', name: 'paragraph' }
    ]

    resp = await fetch('/api/blocks')
    this.blocks = await resp.json()
  },

  methods: {

    // Serverside PHP block
    async addBlock(item) {
      // Render
      // const url = `${blocksBaseUrl}/${item.name}.php` // || '/blocks/image.php'
      const url = `/api/blocks/${item.name}/render` // || '/blocks/image.php'
      const resp = await fetch(url, { method: 'GET' })
      item.render = await resp.text()

      this.pageblocks.push(item)
    },

    // Clientside JS block
    async addBlockJs(item) {
      console.log(item)
      this.pageblocks.push({ component: item.name, name: item.name, render: '' })
      // this.pageblocks.push(item)
    },

    upBlock(item) {
      const array = this.pageblocks
      const index = array.indexOf(item)
      array.move(index, 1, index - 1)
    },

    downBlock(item) {
      const array = this.pageblocks
      const index = array.indexOf(item)
      array.move(index, 1, index + 2)
    },

    deleteBlock(item) {
      const array = this.pageblocks
      array.splice(array.indexOf(item), 1);
    },

    onEnter(item, $event) {
      console.log('enter', item, $event)
      // this.addBlock({ name: 'richtext' })
      this.pageblocks.push({ component: 'paragraph', name: 'paragraph', render: $event })

    },

    async save(page) {
      const { pageblocks } = this

      // Render client side ;)
      const bodyRenderedBlocks = pageblocks.map(block => block.render)

      // Create page object
      const doc = {
        ...page,
        body: bodyRenderedBlocks.join(''),
        pageblocks: JSON.stringify(pageblocks)
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
    }
  },
  template: html`
  <v-app>
    <v-container fluid>
  
    <div>
    <!-- {{pageblocks}} -->



<!-- <v-btn icon @click="editor = !editor"><v-icon>add</v-icon></v-btn> -->
<!-- <v-switch label="editor" v-model='editor'></v-switch> -->
<v-btn
@click="save(page)"
              absolute
              dark
              fab
              top
              right
              color="pink"
              :loading="loading"
            >
              <v-icon>save</v-icon>
            </v-btn>
            
    <!-- Block library -->
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      :nudge-width="200"
      offset-x
    >
      <template v-slot:activator="{ on }">
        <v-btn
          color="indigo"
          dark
          v-on="on"
        >
          Add block
        </v-btn>
      </template>

      <v-card>
      <grid :items="blocks">
          <template #card="{item}">
            <v-card @click="addBlock(item)" class="show-actions-on-hover">
              <v-card-title>
                <div class="text-truncate" style="width:60%">
                  {{ item.name || 'No title' }}
                </div>
                <v-spacer />
              </v-card-title>
            </v-card>
          </template>
        </grid>

        <grid :items="blocksJs">
          <template #card="{item}">
            <v-card @click="addBlockJs(item)" class="show-actions-on-hover">
              <v-card-title>
                <div class="text-truncate" style="width:60%">
                  {{ item.name || 'No title' }}
                </div>
                <v-spacer />
              </v-card-title>
            </v-card>
          </template>
        </grid>

      </v-card>
    </v-menu>



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


    <div v-for='(item,index) in pageblocks'>
      <div class="pageblocks">
        <div class="pageblocks__heading">
          <div style="width:60%;float:left; ">
            <strong>{{item.name}} </strong>
            <!-- ({{index}}) -->
          </div>

          <div class="actions text-right">
            <v-btn icon text class="pull-right" @click.prevent="upBlock(item)">
              <v-icon>keyboard_arrow_up</v-icon>
            </v-btn>

            <v-btn icon text class="pull-right" @click.prevent="downBlock(item)">
              <v-icon>keyboard_arrow_down</v-icon>
            </v-btn>

            <v-btn icon text class="pull-right" @click.prevent="deleteBlock(item)">
              X
            </v-btn>
          </div>
        </div>
        
           <!-- JS Edit modus -->
        <component :is="item.component"  @done="onEnter(item, $event)"/>

          <!-- Pre rendered block -->
          <div v-html="item.render"></div>

        </div>

       
      </div>
    </div>
  

    </v-container>
  
  </v-app>`
})