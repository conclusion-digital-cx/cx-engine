<script>
import { AuthService } from '@/services/AuthService'

export default {
  data () {
    return {
      valid: true,
      loading: false,
      form: {
        // email: this.$router.params.u
      },
      formErrors: {}
    }
  },

  created () {
    this.form = {
      email: this.$route.query.u,
      secret: this.$route.query.secret
    }
  },

  methods: {

    async submit (form) {
      if (!this.$refs.form.validate()) return

      this.loading = true
      this.formErrors = {}
      try {
        await this.$store.dispatch('USER_CHANGEPASSWORD', form)
      } catch (data) {
        console.warn(data)

        // const { data } = response
        const { errors = [] } = data
        this.formErrors = errors
      }
      this.loading = false

      // if (isUser) this.$router.push('/')
    }
  }
}
</script>

<template>
  <!-- <layout-login> -->
  <v-layout align-center justify-center style="min-height:100%">
    <v-flex xs12 sm8 md4>
      <v-card class="elevation-12">
        <v-toolbar dark color="primary">
          <v-toolbar-title>Reset password</v-toolbar-title>
          <v-spacer />
        </v-toolbar>
        <v-form ref="form" v-model="valid">
          <v-card-text>
            <v-text-field
              v-model="form.password"
              type="password"
              :error-messages="formErrors.password"
              :rules="[v => !!v || 'Item is required']"
              required
              prepend-icon="lock"
              label="New password"
            />
            <v-text-field
              v-model="form.password2"
              type="password"
              :error-messages="formErrors.password2"
              :rules="[v => !!v || 'Item is required']"
              required
              prepend-icon="lock"
              label="Repeat password"
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
              Change password
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-flex>
  </v-layout>
  <!-- </layout-login> -->
</template>
