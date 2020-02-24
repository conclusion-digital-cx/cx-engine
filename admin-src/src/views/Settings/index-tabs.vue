<template>
  <v-container>
    <v-card>
      <v-container>
        <h1>Settings</h1>
      </v-container>

      <v-tabs
        fixed-tabs
        background-color="indigo"
        dark
      >
        <v-tab :href="`#tab-1`">
          Types From server
        </v-tab>
        <v-tab :href="`#tab-2`">
          Types From JSON
        </v-tab>

        <v-tab-item
          :value="'tab-1'"
        >
          <v-card
            flat
            tile
          >
            <v-card-text>
              <v-form @submit.prevent="submit(form)">
                <v-text-field v-model="form.server" label="Server" />
                <v-text-field v-model="form.apiKey" hint="This will be send as an Authorization header like: ApiKey <thekey>" label="apiKey" />

                <!-- {{ api }} -->
                <v-btn type="submit" class="primary">
                  Save
                </v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-tab-item>

        <v-tab-item
          :value="'tab-2'"
        >
          <v-card
            flat
            tile
          >
            <v-card-text>
              <v-form @submit.prevent="submit(form)">
                <v-text-field v-model="form.server" label="Server" />

                <v-text-area v-model="form.typesServer" label="Types" />

                <v-text-field v-model="form.apiKey" hint="This will be send as an Authorization header like: ApiKey <thekey>" label="apiKey" />

                <!-- {{ api }} -->
                <v-btn type="submit" class="primary">
                  Save
                </v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs>
    </v-card>
  </v-container>
</template>

<script>
// TODO check type
import api from '@/services/api.js'

export default {
  data () {
    return {
      api,
      form: {
        server: api.getBase(),
        apiKey: api.getApiKey()
      }
    }
  },

  async created () {
    this.fetch()
  },

  methods: {
    async fetch () {

    },

    async submit (form) {
      console.log(form)
      api.setServer(form.server)
      api.setApiKey(form.apiKey)
    }
  }
}
</script>
