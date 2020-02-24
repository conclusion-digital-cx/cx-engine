<script>
import { ROW_KEY } from '@/config'

export default {
  components: {
  },

  props: {
    typeSlug: { type: String, default: 'users' },
    typeId: { type: String, default: '' }
  },

  data () {
    return {
      customizedHeaders: [],
      showColumnSelector: false,
      baseUrl: '', // api.getBase(),
      type: {},
      items: [],
      totalItems: 0,
      loading: false,
      headers: [
        // { text: 'id', value: 'id' },
        // { text: 'createdAt', value: 'createdAt' }
      ],
      options: {}
    }
  },

  computed: {
    _items () {
      return this.items
    },

    _headers () {
      const { customizedHeaders } = this
      return customizedHeaders.length
        ? customizedHeaders : this._headersAll
    },

    _headersAll () {
      return [
        // { text: '#', value: '_id' },
        ...this.headers.map(elem => ({
          text: elem.name, value: elem.name
        }))
      ]
    },

    breadcrumbs () {
      return [
        {
          text: 'Content',
          disabled: false,
          href: '#/content'
        },
        {
          text: this.typeSlug,
          disabled: true
        }
      ]
    }
  },

  watch: {
    $route (to, from) {
      // console.log(to, from)
      if (to.name === 'table') {
        this.fetch()
      }
    },

    options: {
      async handler (newOptions) {
        // console.log('New fetch', newOptions)
        await this.fetch(newOptions)
      },
      deep: true
    }

  },

  methods: {
    async getHeadersAndSetType () {
      try {
        this.loading = true
        const type = await this.$serviceFactory('types').getOne({ name: this.typeSlug })
        this.type = type
        return type.attributes
      } catch (err) {
        console.warn(err)
        this.$router.push(`/`)
      } finally {
        this.loading = false
      }
    },

    rowClick (row) {
      const idKey = this.type.key || ROW_KEY
      const id = row[idKey]
      this.$router.push(`/content/${this.typeSlug}/edit/${id}`)
    },

    async fetch (options = {}) {
      // Fetch all headers
      const attributes = await this.getHeadersAndSetType()
      this.headers = attributes
      //  convertObjectToArray(attributes)

      // Map Vuetify to json-server spec
      // page=1&itemsPerPage=10&sortBy=&sortDesc=&groupBy=&groupDesc=&mustSort=false&multiSort=false
      console.log(options)
      const serverOptions = {
        // TODO
        // _sort: options.sortBy,
        // _order: options.sortDesc,
        _start: (options.page - 1) * options.itemsPerPage,
        _limit: options.itemsPerPage
      }

      // Get Content
      try {
        this.loading = true
        const resp = await this.$service.getWithTotalItems(this.typeSlug, serverOptions)
        const { items, totalItems } = resp
        this.totalItems = totalItems
        this.items = items
      } finally {
        this.loading = false
      }
    },

    onColumnInput (newHeaders) {
      this.customizedHeaders = newHeaders

      // Sync to localStorage
      // storage.save(
      //   newHeaders
      //     .map(elem => elem.value)
      //     // .filter((v, i, a) => a.indexOf(v) === i) // unique
      // )
    }
  }
}
</script>

<template>
  <v-container>
    <!-- Child routes -->
    <router-view />
    <slot />
    <!-- {{ _headers }} -->
    <!-- {{ customizedHeaders }} -->

    <v-row class="ma-0">
      <v-breadcrumbs
        :items="breadcrumbs"
        divider=">"
      />

      <v-spacer />

      <v-btn
        outlined
        :to="`/typebuilder/${typeSlug}/${typeId}`"
      >
        Edit
      </v-btn>
    </v-row>

    <v-card>
      <v-row class="ma-0 pa-3">
        <v-btn
          :to="`/content/${typeSlug}/new`"
          class="primary"
        >
          New item
        </v-btn>
        <v-btn
          icon
          class="mx-2"
          @click="fetch(options)"
        >
          <v-icon>refresh</v-icon>
        </v-btn>
        <v-spacer />

        <v-dialog
          v-model="showColumnSelector"
          :width="500"
        >
          <template #activator="{ on }">
            <v-btn
              class="mx-2"
              outlined
              v-on="on"
            >
              Columns
            </v-btn>
          </template>

          <v-card class="pa-2">
            <ColumnSelector
              v-model="customizedHeaders"
              :headers="_headersAll"
              @input="onColumnInput"
            />
          </v-card>
        </v-dialog>

        <!-- <v-btn
          target="_blank"
          outlined
          :href="`${baseUrl}/${typeSlug}`"
          class=""
        >
          Download
        </v-btn> -->
      </v-row>

      <v-data-table
        :loading="loading"
        :headers="_headers"
        :items="loading ? [] :_items"
        :server-items-length="totalItems"
        :options.sync="options"
        @click:row="rowClick"
      >
        <!-- TODO Custom table cells -->
        <!-- <template #item="{item, index, headers}">
          <td>
            {{ headers[index] && headers[index].value }}
            cool
          </td>
        </template> -->
      </v-data-table>
    </v-card>
  </v-container>
</template>
