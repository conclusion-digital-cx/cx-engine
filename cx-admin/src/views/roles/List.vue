
<script>
import Form from './components/Form'
import { ROW_KEY } from '@/config'

export default {
  components: {
    Form
  },

  data () {
    return {
      ROW_KEY,
      items: [],
      loading: false
    }
  },

  computed: {
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
        const resp = await this.$service.getAll('roles')
        console.log(resp)
        this.items = resp
      } finally {
        this.loading = false
      }
    },

    async submit (form) {
      try {
        this.loading = true

        await this.$service.create('roles', {
          attributes: [],
          ...form
        })
      } finally {
        this.loading = false
      }

      // TODO for now reload
      this.fetch()
    },

    getAttributes (item) {
      const obj = item.attributes || []
      // return Object.entries(obj).map(([key, value]) => ({
      //   name: key,
      //   ...value
      // }))
      return obj
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
        {{ items.length }} roles are available
        <v-spacer />

        <Toggle label="Add new role">
          <Form @submit="submit" />
        </Toggle>
      </v-card-title>

      <!-- {{ items }} -->
      <v-list-item
        v-for="(item,key) in items"
        :key="key"
        :to="`/roles/${item.name}`"
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
      </v-list-item>
    </v-card>
  </v-container>
</template>
