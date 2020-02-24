
<script>
import InputRelation from './components/InputRelation'
import TYPES from './TYPES'
import { ROW_KEY } from '@/config'

export default {
  components: {
    // Form,
    InputRelation
  },

  props: {
    name: { type: String, default: '' }
    // id: { type: String, default: '' }
  },

  data: vm => ({
    snackbar: false,
    loading: false,
    dialog: false,
    TYPES,
    form: {
      // key: ROW_KEY,
      // collectionName: '',
      attributes: []
      // get: { },
      // getOne: {},
      // post: {},
      // put: {},
      // delete: {}
    }
  }),

  computed: {
    _id () {
      return this.form[ROW_KEY] || this.form['_id']
    },

    breadcrumbs () {
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

    _relations () {
      return this._attributes.filter(elem => elem.target)
    }
  },

  async created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      try {
        this.loading = true
        const resp = await this.$serviceFactory('types').getOne({ name: this.name })
        console.log(resp)

        // SET FORM
        this.form = {
          // Defaults
          permissions: {
            public: [],
            authenticated: []
          },
          ...this.form,
          // attributes: [],
          ...resp,
          // Fix
          // attributes: convertObjectToArray(resp.attributes)
          attributes: resp.attributes || []
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

      // // Set store
      // this.$store.commit('types/set', resp)
    },

    addField () {
      this._attributes.push({
        name: '',
        editable: true,
        type: 'string'
        // params: {
        //   type: 'string'
        //   // minLength: 3,
        //   // unique: true,
        //   // configurable: false,
        //   // required: true
        // }
      })
    },

    edit (item) {
      this.dialog = true
    },

    async saveChanges (form) {
      console.log(form)

      // Save to server
      try {
        this.loading = true
        const item = await this.$serviceFactory('types').putById(this._id, form)
        console.log(item)
      } catch (err) {
        console.warn(err)
        this.$snackbar('Something went wrong', 'error')
      } finally {
        this.loading = false
      }
    },

    async deleteType (form) {
      try {
        this.loading = true
        const item = await this.$serviceFactory('types').deleteById(this._id)
        console.log(item)
      } finally {
        this.loading = false
      }
    },

    async autoDetect () {
      try {
        this.loading = true

        const items = await this.$serviceFactory(this.name).getOne()

        const first = items
        const fields = Object.keys(first)

        // Mutate
        this.form.attributes = fields.map(key => ({
          name: key,
          editable: true,
          // TO DEPRECATE (compatiblity with STRtypesService)
          // params: {
          type: 'string'
          // }
        }))

        console.log(items)
      } catch (err) {
        console.warn(err)
        this.$snackbar('Something went wrong')
      } finally {
        this.loading = false
      }
    },

    // ===================
    // Special Admin TODO make more save
    // ===================

    // Force recreate table
    async createTable () {
      try {
        this.loading = true
        // const resp = await fetch(`/api/collections/${this.name}/create`, {
        const resp = await this.$service.fetch(`/_tasks/create/${this.name}`, {
          method: 'GET'
          // body: JSON.stringify(form)
        })
        this.$emit('success')
      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    },

    // Force recreate table
    async dropTable () {
      try {
        this.loading = true
        // const resp = await fetch(`/api/collections/${this.name}/create`, {
        const resp = await this.$service.fetch(`/_tasks/drop/${this.name}`, {
          method: 'GET'
          // body: JSON.stringify(form)
        })
        this.$emit('success')
      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    }
  }
}
</script>

<template>
  <v-container>
    <v-row>
      <v-breadcrumbs
        :items="breadcrumbs"
        divider=">"
      />
      <v-spacer />

      <span class="ma-1">Admin</span>
      <v-btn
        class="mx-2"
        :loading="loading"
        @click="createTable"
      >
        create table
      </v-btn>
      <v-btn
        class="mx-2 error"
        :loading="loading"
        @click="dropTable"
      >
        drop table
      </v-btn>
    </v-row>

    <small>
      name: {{ form.name }} /
      #{{ _id }}
    </small>

    <v-card>
      <v-card-title>
        <!-- {{ form }} -->
        <div>
          <!-- {{ _attributes }} -->
          {{ _attributes && _attributes.length }} fields including {{ _relations.length }} relationships
          <!-- <v-text-field
            v-model="form.key"
            label="Primary Key"
          /> -->
        </div>
        <v-spacer />
        <v-btn
          class="primary"
          @click="saveChanges(form)"
        >
          Save changes
        </v-btn>
        <v-btn
          class="mx-2 error"
          @click="deleteType(form)"
        >
          Delete type
        </v-btn>
      </v-card-title>

      <!-- Show fields -->
      <Loader v-if="loading" />
      <template v-if="!_attributes.length">
        <center>
          <p>No fields have been added.</p>
          <p>If the table has already data in it use autodetect.</p>

          <v-btn
            class="ma-2"
            outlined
            @click="autoDetect"
          >
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
                  <v-text-field
                    v-model="item.name"
                    label="Name"
                    placeholder="Add new field"
                    hide-details
                  />
                </v-col>
                <v-col cols="6">
                  <div class="d-flex">
                    <v-select
                      v-model="item.type"
                      label="Type"
                      :items="TYPES"
                    />

                    <!-- Form only options -->
                    <!-- <v-select
                      v-model="item.formType"
                      class="ml-3"
                      label="Form type"
                      :items="[
                        { text: 'number', value: 'number' },
                        { text: 'date', value: 'date' }
                      ]"
                    /> -->
                  </div>

                  <!-- {{ item.params }} -->
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
                <v-btn
                  icon
                  class="hide-by-default"
                  @click="deleteItem(item)"
                >
                  <v-icon>close</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-list-item>

          <v-divider />
        </div>
      </template>

      <v-btn
        class="ma-2"
        outlined
        @click="addField"
      >
        Add another field
      </v-btn>
    </v-card>

    <!-- <template v-for="(role,index) in roles || []">
      <v-card class="mt-5">
        <v-card-title>
          <div>
            Roles & permissions ({{ role.text }})
          </div>
          <v-spacer />
        </v-card-title>
        {{ form.permissions[role.text] }}
        <TypePermissions v-model="form.permissions[role.text]" />
      </v-card>
    </template>

    {{ form.permissions }} -->

    <!-- <v-card class="mt-5">
      <ToggleJson label="View json">
        {{ form }}
      </ToggleJson>
    </v-card> -->
  </v-container>
</template>

<style>
.reveal-on-hover .hide-by-default {
visibility: hidden;
}
.reveal-on-hover:hover .hide-by-default {
visibility: unset;
}
</style>
