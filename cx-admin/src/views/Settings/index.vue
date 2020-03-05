<template>
  <v-container>
    <v-card>
      <v-container>
        <h1>Settings</h1>
      </v-container>
      <!-- {{ this.$store.state.settings }} -->
      <!-- {{ form }} -->
      <v-card-text>
        <v-form @submit.prevent="submit(form)">
          <v-text-field
            v-model="form.server"
            label="Server"
          />

          <v-text-field
            v-model="form.headers.Authorization"
            hint="Specify here the Authorization headers E.G: ApiKey <thekey> or Bearer <token>"
            label="Authorization header"
          />

          <v-text-field
            v-model="form.defaultPrimaryKey"
            placeholder="E.g. for sql 'id' or mongodb '_id'"
            label="Default primary key"
          />

          <template v-if="form.useTypesFromJson">
            <v-textarea
              v-model="form.types"
              placeholder="Paste here your types"
              label="Types"
            />
          </template>

          <!-- {{ api }} -->
          <v-row class="ma-0">
            <v-btn
              type="submit"
              class="primary"
            >
              Save
            </v-btn>

            <v-spacer />
            <!-- <v-btn class="" outlined @click="exportProfile">
              Export profile
            </v-btn> -->
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>

    <v-card
      class="mt-5"
    >
      <v-container>
        <div class="d-flex">
          <h1>Import / export</h1>
          <v-spacer />
          <v-btn
            class=""
            outlined
            @click="exportProfile"
          >
            Export settings
          </v-btn>
        </div>
      </v-container>
      <v-card-text>
        <v-form @submit.prevent="submit(form)">
          <!-- {{ api }} -->
          <v-row class="ma-0">
            <Dropzone @input="onInput" />
            <v-spacer />
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
function downloadFile (data, fileName, type = 'text/plain') {
  // Create an invisible A element
  const a = document.createElement('a')
  a.style.display = 'none'
  document.body.appendChild(a)

  // Set the HREF to a Blob representation of the data to be downloaded
  a.href = window.URL.createObjectURL(
    new Blob([data], { type })
  )

  // Use download attribute to set set desired file name
  a.setAttribute('download', fileName)

  // Trigger the download by simulating click
  a.click()

  // Cleanup
  window.URL.revokeObjectURL(a.href)
  document.body.removeChild(a)
}

export default {
  data () {
    return {
      // api,
      form: this.$store.state.settings
    }
  },

  async created () {
    this.fetch()
  },

  methods: {
    onInput ($event) {
      console.log('Drop', $event)
      this.form = $event
    },

    async fetch () {

    },

    async exportProfile () {
      const profile = {
        settings: this.$store.state.settings,
        types: this.$store.state.types
      }
      const data = JSON.stringify(profile, true, 2)
      downloadFile(data, 'superadmin.json', 'text/json')
    },

    async submit (form) {
      console.log(form)
      // this.$store.dispatch('config', form)
      // api.setServer(form.server)

      this.$store.commit('settings/set', form)

      // window.localStorage.setItem('profile', JSON.stringify(form))
    }

  }
}
</script>
