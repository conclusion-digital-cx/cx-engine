
<script>
import TYPES from './TYPES'
import { ROW_KEY } from '@/config'

export default {
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
    },
    permissions: [],
    users: []
  }),

  computed: {
    _id () {
      return this.form[ROW_KEY]
    },

    breadcrumbs () {
      return [
        {
          text: 'roles',
          disabled: false,
          href: '#/roles'
        },
        {
          text: this.name,
          disabled: true
        }
      ]
    }
  },

  async created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      try {
        this.loading = true
        const resp = await this.$serviceFactory('roles').getOne({ name: this.name })
        this.users = await this.$serviceFactory('users').getAll({ role: resp.id })
        this.form = resp
      } catch (err) {
        console.warn(err)
        this.snackbar = true
      } finally {
        this.loading = false
      }
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
        const item = await this.$serviceFactory('roles').putById(this._id, form)
        console.log(item)
      } finally {
        this.loading = false
      }
    },

    async deleteType (form) {
      try {
        this.loading = true
        const item = await this.$serviceFactory('roles').deleteById(this._id)
        console.log(item)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<template>
  <v-container>
    <v-snackbar
      v-model="snackbar"
      :timeout="5000"
    >
      Something went wrong
      <v-btn
        dark
        text
        @click.native="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <v-row>
      <v-breadcrumbs
        :items="breadcrumbs"
        divider=">"
      />
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
    </v-row>

    <v-data-table
      :headers="[{value:'email', text:'Email'}]"
      :items="users"
    />

    <template v-for="(role,index) in permissions || []">
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

    {{ form.permissions }}

    <!-- <v-card class="mt-5">
      <ToggleJson label="View json">
        {{ form }}
      </ToggleJson>
    </v-card> -->
  </v-container>
</template>
