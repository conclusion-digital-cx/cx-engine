<script>

const getGeneralError = arr => {
  return arr[0].constructor === String ? arr[0] : ''
}

export default {
  props: {
    redirectTo: { type: String, default: '/' }
  },

  data () {
    return {
      valid: true,
      loading: false,
      form: {},
      formErrors: {},
      generalError: ''
    }
  },

  methods: {
    validate (form) {
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async submit (form) {
      this.loading = true
      this.formErrors = {}
      this.generalError = ''

      try {
        const isUser = await this.$store.dispatch('USER_LOGIN', form)
        if (isUser) this.$router.push(this.redirectTo)
      } catch (err) {
        this.loading = false
        console.warn(err)
        this.generalError = getGeneralError(err.data.errors)
      }
      this.loading = false
    }
  }
}
</script>
<template>
  <v-flex xs12 sm8 md4>
    <v-card class="elevation-12">
      <v-toolbar dark color="primary">
        <v-toolbar-title>Login</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <v-card-text>
        <v-alert
          :value="generalError"
          dismissible
          color="error"
        >
          {{ generalError }}
        </v-alert>
      </v-card-text>

      <v-form ref="form" v-model="valid" @submit.prevent="validate(form)">
        <v-card-text>
          <v-text-field
            v-model="form.email"
            label="Login"
            placeholder="Login"
            :error-messages="formErrors.email"
            :rules="[v => !!v || 'Item is required']"
            required
            prepend-icon="person"
            name="login"
            type="text"
          />
          <v-text-field
            id="password"
            ref="input"
            v-model="form.password"
            label="Password"
            :error-messages="formErrors.password || formErrors[0]"
            placeholder="Password"
            :rules="[v => !!v || 'Item is required']"
            required
            prepend-icon="lock"
            name="password"
            type="password"
          />
        </v-card-text>
        <v-card-actions>
          <router-link :to="`/forgot`">
            Forgot password?
          </router-link>
          <v-spacer />
          <v-btn type="submit" :loading="loading" :disabled="loading" color="primary">
            Login
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-flex>
</template>
