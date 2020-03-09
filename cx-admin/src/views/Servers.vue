<template>
  <v-container>
    <h1>Servers</h1>
    <v-btn>Add new server</v-btn>
    <v-card>
      <v-card-title class="title">
        Server 1 - status: running
        <v-btn icon>
          <v-icon>play_arrow</v-icon>
        </v-btn>
        <v-btn icon>
          <v-icon>replay</v-icon>
        </v-btn>
        <v-btn>open</v-btn>
      </v-card-title>
    </v-card>
  </v-container>
</template>

<script>
import api from 'api'

export default {
  components: {
  },
  data () {
    return {
      items: [],
      loading: false
    }
  },
  async created () {
    try {
      this.loading = true
      const resp = await api.get(`/types`)
      console.log(resp)
      this.items = resp
    } finally {
      this.loading = false
    }
  }
}
</script>
