const html = (strings) => strings[0]

import Form from './Form.js'

const ROW_KEY = 'id'

export default {
  components: {
    Form
  },
  props: {
    name: { type: String, default: "" },
    id: { type: String, default: "" },
    title: { type: String, default: "" },

    titleKey: { type: String, default: "title" },
  },

  data() {
    return {
      loading: true,
      items: [],
      dialog: false,
      form: {}
    }
  },

  watch: {
    $route(to, from) {
      // react to route changes...
      this.fetch()
    }
  },

  async mounted() {
    this.fetch()
  },

  methods: {
    async fetch() {
      try {
        this.loading = true
        const resp = await fetch(`/api/${this.name}`)
        this.items = await resp.json()
      } finally {
        this.loading = false
      }
    },

    async deleteItem(item) {
      try {
        this.loading = true
        const resp = await fetch(`/api/${this.name}/${item.id}`, {
          method: 'DELETE',
        })

        // Remove item from UI
        this.items.splice(this.items.indexOf(item), 1);

      } finally {
        this.loading = false
      }
    },

    editItem(item) {
      this.$emit('click:edit', item)
    },

    itemClick(item) {
      this.$emit('click:item', item)
    },
    to(item) {
      return `/collections/edit/${item.name}`
    },

    async submit (form) {
      console.log(form)
      // this.items.push(form)
      try {
        this.loading = true

        await typesService.post({
          attributes: [],
          ...form
        })
      } finally {
        this.loading = false
      }

      // TODO for now reload
      this.fetch()
    }
  },

  template: html`
    <v-container>
    <!-- <v-breadcrumbs :items="breadcrumbs" divider=">" /> -->

    <!-- <v-card>
      <DynamicList :items="items" />
    </v-card> -->

    <v-card>
      <v-card-title class="title">
        {{ items.length }} content types are available
        <v-spacer />

        <Toggle label="Add new type">
          <Form @submit="submit" />
        </Toggle>
      </v-card-title>

      <!-- {{ items }} -->
      <v-list-item v-for="(item,key) in items" :key="key" :to="to(item)">
        <v-layout row>
          <v-flex xs4>
            {{ item.name }}
          </v-flex>
          <v-flex xs4>
            {{ item.description || '-' }}
          </v-flex>
          <v-flex xs4>
            <!-- {{ item.attributes.length }} field(s) -->
          </v-flex>
        </v-layout>
      </v-list-item>
    </v-card>
  </v-container>`
}