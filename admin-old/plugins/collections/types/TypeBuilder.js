const html = (strings) => strings[0]

// import InputRelation from './components/InputRelation'
// import this.$service from '@/services/this.$service.js'
// import api from '@/services/api.js'
// import { createCollectionService, evalMapperFunctions } from '@/services/createCollectionService.js'
import TYPES from './TYPES.js'
// import { ROW_KEY } from '@/config'

export default {
//   components: {
//     InputRelation
//   },

  props: {
    name: { type: String, default: '' },
    id: { type: String, default: '' }
  },

  data () {
    return {
      snackbar: false,
      loading: false,
      dialog: false,
      TYPES,
      form: {
        key: '_id',
        collectionName: '',
        attributes: [],
        get: { },
        getOne: {},
        post: {},
        put: {},
        delete: {}
      }
    }
  },

  computed: {
    _breadcrumbs () {
      return [
        {
          text: 'Typebuilder',
          disabled: false,
          href: '#/typebuilder'
        },
        {
          text: this.name,
          disabled: true
        }
      ]
    },

    _attributes () {
      return this.form.attributes
    },

    relations () { return this._attributes.filter(elem => elem.target) }
  },

  async created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      try {
        this.loading = true
        const attributes = await this.$service.getCollectionSchemaByName(this.name)
        console.log(attributes)

        // SET FORM
        this.form = {
          ...this.form,
          attributes,
          // ...resp
        }
      } catch (err) {
        console.warn(err)
        this.snackbar = true
      } finally {
        this.loading = false
      }
    },

    toggleEdit (item) {
      console.log(item)
      // item.editable = !item.editable
      this.dialog = true
    },

    deleteItem (item) {
      const removeElement = (array, elem) => {
        var index = array.indexOf(elem)
        if (index > -1) {
          array.splice(index, 1)
        }
      }

      removeElement(this._attributes, item)
    },

    addField () {
      this._attributes.push({
        name: '',
        editable: true,
        params: {
          type: 'string'
          // minLength: 3,
          // unique: true,
          // configurable: false,
          // required: true
        }
      })
    },

    edit (item) {
      this.dialog = true
    },

    async saveChanges (form) {
      // Save to server
      try {
        this.loading = true
        const item = await this.$service.put(this.form[ROW_KEY], form)
        console.log(item)
      } finally {
        this.loading = false
      }
    },

    async deleteType (form) {
      try {
        this.loading = true
        const item = await this.$service.deleteById(this.id)
        console.log(item)
      } finally {
        this.loading = false
      }
    },

    async autoDetect () {
      try {
        this.loading = true

        // Non REST API?
        const items = await this.$service.get()

        const first = items[0]
        const fields = Object.keys(first)

        // Mutate
        this.form.attributes = fields.map(key => ({
          name: key,
          editable: true,
          // TO DEPRECATE (compatiblity with STRthis.$service)
          params: {
            type: 'string'
          }
        }))

        console.log(items)
      } catch (err) {
        console.warn(err)
        this.snackbar = true
      } finally {
        this.loading = false
      }
    }
  },
  template: html`
  <v-container>
  <v-snackbar v-model="snackbar" :timeout="5000">
    Something went wrong
    <v-btn dark text @click.native="snackbar = false">
      Close
    </v-btn>
  </v-snackbar>

  <v-breadcrumbs :items="_breadcrumbs" divider=">" />

  <!-- <small>
    collectionName: {{ form.collectionName || form.name }} / name: {{ form.name }}
    #: {{ id }}
  </small> -->

  <v-card>
    <v-card-title class="title">
      <!-- {{ form }} -->
      <div>
        {{ _attributes && _attributes.length }} fields including {{ relations.length }} relationships
        <v-text-field v-model="form.key" label="Primary Key" />
      </div>
      <v-spacer />

      <v-btn class="primary" @click="saveChanges(form)">
        Save changes
      </v-btn>
      <v-btn class="mx-2 error" @click="deleteType(form)">
        Delete type
      </v-btn>
    </v-card-title>

    <!-- Show fields -->
    <Loader v-if="loading" />
    <template v-if="!_attributes.length">
      <center>
        <p>No fields have been added.</p>
        <p>If the table has already data in it use autodetect.</p>

        <v-btn class="ma-2" outlined @click="autoDetect">
          Autodetect
        </v-btn>
      </center>
    </template>

    <template v-for="(item,index) in _attributes || []">
      <div :key="index">
        <v-list-item class="reveal-on-hover">
          <!-- {{ item }} -->
          <v-row class="ma-0">
            <!-- Read only -->
            <template v-if="!item.editable">
              <v-col xs4>
                {{ item.name }}
              </v-col>
              <v-col cols="6">
                <div v-if="item.type">
                  {{ item.type || '-' }}
                </div>
                <div v-else>
                  Relation with {{ item.target }} ({{ item.key }})
                </div>
              </v-col>
            </template>

            <!-- Editable -->
            <template v-else>
              <v-col xs4>
                <v-text-field v-model="item.name" label="Name" placeholder="Add new field" hide-details />
              </v-col>
              <v-col cols="6">
                <v-select v-model="item.type" label="Type" :items="TYPES" />
                <!-- {{ item }} -->
                <template v-if="item.type === 'relation'">
                  <InputRelation v-model="_attributes[index]" />
                  <!-- <InputRelation v-model="item.relation" /> -->
                </template>
              </v-col>
            </template>

            <v-col xs2>
              <!-- {{ item.attributes.length }} -->
              <!-- <v-btn icon class="hide-by-default" @click="toggleEdit(item)">
                <v-icon>edit</v-icon>
              </v-btn> -->
              <v-btn icon class="hide-by-default" @click="deleteItem(item)">
                <v-icon>close</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-list-item>

        <v-divider />
      </div>
    </template>

    <v-btn class="ma-2" outlined @click="addField">
      Add another field
    </v-btn>
  </v-card>

  <!-- <v-card class="mt-5">
    <ToggleJson label="View json">
      {{ form }}
    </ToggleJson>
  </v-card> -->
</v-container>
`
}


{/* <style>
.reveal-on-hover .hide-by-default {
visibility: hidden;
}
.reveal-on-hover:hover .hide-by-default {
visibility: unset;
}
</style> */}
