
<script>
import Form from './components/Form.vue'
import { ROW_KEY } from '@/config'

export default {
  components: {
    Form
  },

  data () {
    return {
      ROW_KEY,
      items: [],
      loading: false,
      selection: [],
      headers: [
        { value: 'name' },
        { value: 'description' },
        { value: 'fields' }
        // { value: 'actions', text: 'Show in menu' }
      ]
    }
  },

  computed: {
    _defaultPrimaryKey () {
      return this.$store.state.settings.defaultPrimaryKey
    },
    breadcrumbs () {
      return [
        {
          text: 'Typebuilder'
        }
      ]
    }
  },

  created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      try {
        this.loading = true
        const resp = await this.$service.getAll('types')
        // console.log(resp)
        this.items = resp
        return resp
      } finally {
        this.loading = false
      }
    },

    // Import multiple
    async clickImport (arr = []) {
      try {
        this.loading = true

        const promises = arr.map(async elem => {
          return this.$service.create('types', {
            attributes: [],
            name: elem
          })
        })

        await Promise.all(promises)
      } finally {
        this.loading = false
      }

      // TODO for now reload
      this.fetch()
    },

    async clickAutodetect (arr = []) {
      try {
        this.loading = true

        const promises = arr.map(async elem => {
          return this.autoDetect(elem.name)
        })

        await Promise.all(promises)
      } finally {
        this.loading = false
      }

      // TODO for now reload
      this.fetch()
    },

    async submit (form) {
      try {
        this.loading = true

        await this.$service.create('types', {
          attributes: [],
          ...form
        })
      } finally {
        this.loading = false
      }

      // TODO for now reload
      this.fetch()
    },

    async autoDetect (name) {
      try {
        this.loading = true

        const items = await this.$serviceFactory(name).getOne()

        const first = items
        const fields = Object.keys(first)

        // Mutate
        const form = {
          attributes: fields.map(key => ({
            name: key,
            editable: true,
            type: 'string'
          }))
        }

        // Save
        const _id = await this.$serviceFactory('types')
          .getOne({ name })
          .then(elem => elem._id)
        return await this.$serviceFactory('types').putById(_id, form)
      } catch (err) {
        console.warn(err)
        this.$snackbar('Something went wrong')
      } finally {
        this.loading = false
      }
    },

    /** Selection actions */
    async showInNav (selection) {
      this.setToAll(elem => ({
        ...elem,
        showInNavigation: true
      }), selection)
    },

    async hideInNav (selection) {
      this.setToAll(elem => ({
        ...elem,
        showInNavigation: false
      }), selection)
    },

    async setToAll (map, selection = []) {
      try {
        this.loading = true
        const service = this.$serviceFactory('types')

        const promises = selection.map(async elem => {
          const newItem = map(elem)
          await service.putById(elem[this._defaultPrimaryKey], newItem)
        })

        await Promise.all(promises)
        // TODO for now reload
        const resp = await this.fetch()
        // Update selection state
        this.$store.commit('types/set', resp)
        console.log(resp)
      } catch (err) {
        console.warn(err)
        this.$snackbar('Something went wrong')
      } finally {
        this.loading = false
      }
    },

    getAttributes (item) {
      const obj = item.attributes || []
      // return Object.entries(obj).map(([key, value]) => ({
      //   name: key,
      //   ...value
      // }))
      return obj
    },

    rowClick (item) {
      this.$router.push(`/typebuilder/${item.name}`)
    }
  }
}
</script>

<template>
  <v-container>
    <v-breadcrumbs
      :items="breadcrumbs"
      divider=">"
    />
    <v-card>
      <v-card-title class="title">
        {{ items.length }} content types are available
        <v-spacer />

        <div v-if="selection.length">
          <v-btn
            outlined
            class="mx-1"
            :loading="loading"
            @click="showInNav(selection)"
          >
            Show in menu
          </v-btn>
          <v-btn
            outlined
            class="mx-1"
            :loading="loading"
            @click="hideInNav(selection)"
          >
            Hide in menu
          </v-btn>
          <v-btn
            outlined
            class="mx-1"
            :loading="loading"
            @click="clickAutodetect(selection)"
          >
            Autodetect
          </v-btn>
        </div>

        <Toggle label="Add new type">
          <Form @submit="submit" />
          <TypesAddMultiple @submit="clickImport" />
        </Toggle>
      </v-card-title>

      <v-data-table
        v-model="selection"
        show-select
        :items="items"
        :headers="headers"
        item-key="name"
        @click:row="rowClick"
      >
        <template
          #item.fields="{value, item}"
        >
          {{ item.attributes ? item.attributes.length : '?' }} field(s)
        </template>
        <!-- <template #item.actions="{value, item}">
          <v-switch hide-details />
        </template> -->
      </v-data-table>

      <!-- {{ items }} -->
      <!-- <v-list-item
        v-for="(item,key) in items"
        :key="key"
        :to="`/typebuilder/${item.name}`"
      >
        <v-layout row>
          <v-flex xs4>
            {{ item.name }}
          </v-flex>
          <v-flex xs4>
            {{ item.description || '-' }}
          </v-flex>
          <v-flex xs4>
            {{ item.attributes ? item.attributes.length : '?' }} field(s)
          </v-flex>
        </v-layout>
      </v-list-item>-->
    </v-card>
  </v-container>
</template>
