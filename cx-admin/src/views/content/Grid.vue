
<script>
export default {
  components: {
  },

  props: {
    typeSlug: { type: String, default: 'users' }
  },

  data () {
    return {
      baseUrl: '', // api.getBase(),
      type: {},
      items: [],
      loading: false,
      headers: [
        // { text: 'id', value: 'id' },
        // { text: 'createdAt', value: 'createdAt' }
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
        const resp = await this.$service.get('types')
        this.items = resp
      } catch (err) {
        console.warn(err)
        this.$router.push(`/`)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<template>
  <v-container>
    <h1>All content</h1>
    <!-- {{ items }} -->
    <Grid
      :items="items"
      :loading="loading"
    >
      <template #card="{item}">
        <v-card :to="`/content/${item.name}`">
          <v-card-title>
            {{ item.name }}
          </v-card-title>
        </v-card>
      </template>
    </Grid>
  </v-container>
</template>
