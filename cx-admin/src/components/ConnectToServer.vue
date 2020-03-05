<template>
  <v-container>
    <form @submit.prevent="submit(form)">
      <v-text-field
        v-model="form.server"
        label="Server"
      />
      <v-btn
        :loading="loading"
        class="primary"
        type="submit"
      >
        Connect
      </v-btn>
    </form>

    <!-- <v-snackbar
      v-model="snackbar"
    >
      Not a valid server
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar> -->
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  components: {
  },
  data () {
    return {
      form: {
        server: 'http://localhost:8888/superadmin'
      },
      loading: false
    }
  },
  async created () {

  },
  methods: {
    async submit (form) {
      try {
        this.loading = true
        const resp = await axios.get(form.server)
        console.log(resp)
        const { data } = resp
        this.items = data
      } catch (err) {
        console.warn(err)
        alert('Not a valid server')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
