<script>

export default {
  data () {
    return {
      valid: true,
      loading: false,
      form: {},
      formErrors: {}
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

      try {
        const isUserCreated = await this.$store.dispatch('USER_CREATE', form)
        const isUser = await this.$store.dispatch('USER_LOGIN', form)

        if (isUser) this.$router.push('/createcompany')
      } catch (err) {
        console.warn(err)
        this.loading = false
      }
      this.loading = false
    }
  }
}
</script>
<template>
  <v-flex
    xs12
    sm8
    md4
  >
    <v-card class="elevation-12">
      <v-toolbar
        dark
        color="primary"
      >
        <v-toolbar-title>Create</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <!-- {{formErrors}} -->
      <v-form
        ref="form"
        v-model="valid"
        @submit.prevent="validate(form)"
      >
        <v-card-text>
          <v-text-field
            v-model="form.email"
            label="Your email"
            placeholder="e.g. abc@abc.com"
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
            :error-messages="formErrors.password || formErrors.body"
            placeholder="Your secret password"
            :rules="[v => !!v || 'Item is required']"
            required
            prepend-icon="lock"
            name="password"
            type="password"
          />
        </v-card-text>
        <v-card-actions>
          <router-link :to="`/login`">
            Already an account?
          </router-link>
          <v-spacer />
          <v-btn
            type="submit"
            :loading="loading"
            :disabled="loading"
            color="primary"
          >
            Create account
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-flex>
</template>
