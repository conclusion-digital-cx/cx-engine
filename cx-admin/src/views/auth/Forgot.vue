<script>

import { AuthService } from '@/services/AuthService'

export default {
  data: () => ({
    valid: true,
    loading: false,
    form: {},
    formErrors: {}
  }),

  methods: {
    validate (form) {
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async submit (form) {
      if (!this.$refs.form.validate()) return

      this.loading = true
      this.formErrors = {}
      try {
        const isUser = await AuthService.sendResetLink(form.email)
        console.log(isUser)
        if (isUser) this.$router.push('/forgot/check')
      } catch ({ response }) {
        const { data } = response
        const { errors = [] } = data
        console.log(errors)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<template>
  <v-flex xs12 sm8 md4>
    <v-card class="elevation-12">
      <v-toolbar dark color="primary">
        <v-toolbar-title>Forgot password</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <v-form ref="form" v-model="valid" @submit.prevent="validate(form)">
        <v-card-text>
          <v-text-field
            v-model="form.email"
            :error-messages="formErrors.email"
            :rules="[v => !!v || 'Item is required']"
            required
            prepend-icon="person"
            label="Email"
          />
          <!-- <app-form-errors :errors='errors'/> -->
        </v-card-text>
        <v-card-actions>
          <router-link :to="`/login`">
            Back to login?
          </router-link>
          <v-spacer />
          <v-btn
            :loading="loading"
            :disabled="loading"
            color="primary"
            @click.stop="submit(form)"
          >
            Send it now, please!
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-flex>
</template>
