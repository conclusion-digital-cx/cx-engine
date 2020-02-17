const html = (strings) => strings[0]

export default {
  props: {
    redirectTo: { type: String, default: '/' }
  },

  data () {
    return {
      valid: true,
      loading: false,
      form: {
        email: 'jellehak@gmail.com',
        password: '123'
      },
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
      } catch (err) {
        this.loading = false
        console.warn(err)
        this.generalError = true
        // getGeneralError(err.data.errors)
      }
      this.loading = false
    }
  },

  template: html`
  <v-flex
  xs12
  sm8
  md4
  >
      <v-form
  ref="form"
  v-model="valid"
  @submit.prevent="validate(form)"
  >
        <v-card>
          <v-card-title>
            Login
          </v-card-title>
  
          <v-card-text v-if="generalError">
            <v-alert
              dismissible
              color="error"
            >
              Something went wrong
            <!-- {{ generalError }} -->
            </v-alert>
          </v-card-text>
  
          <v-card-text>
            <v-text-field
              v-model="form.email"
              label="Login"
              placeholder="Login"
              :error-messages="formErrors.email"
              :rules="[v => !!v || 'Item is required']"
              required
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
              name="password"
              type="password"
            />
  
            <v-btn
  block
  type="submit"
  :loading="loading"
  :disabled="loading"
  color="primary"
  >
              Login
            </v-btn>
          </v-card-text>
        </v-card>
      </v-form>
  
      <div class="text-center mt-5">
        <router-link to="/password/recover">
          Forgot your password?
        </router-link>
      </div>
    </v-flex>
    `
}
