<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

  <title>Login</title>

  <style>
    .theme--dark.v-application {
      /* background: url("url"); */
      background-color: aquamarine;
      height: 100%;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body>
  <div id="app">
    <div data-app="true" class="v-application v-application--is-ltr theme--dark" id="app">
      <div class="v-application--wrap">
        <main class="v-content" style="padding: 0px;" data-booted="true">
          <div class="v-content__wrap">
            <div class="layout align-center justify-center pa-2" style="min-height: 100%;">
              <div class="flex xs12 sm8 md4">
                <form @submit.prevent="submit(form)" novalidate="novalidate" class="v-form">
                  <!-- <form @submit.prevent="submit" novalidate="novalidate" class="v-form" method="POST" action="/Login"> -->
                  <div class="v-card v-sheet theme--dark">
                    <div class="v-card__title">
                      Login
                    </div>
                    <!---->
                    <div class="v-card__text">
                      <div class="v-input v-input--is-label-active v-input--is-dirty theme--dark v-text-field v-text-field--is-booted v-text-field--placeholder">
                        <div class="v-input__control">
                          <div class="v-input__slot">
                            <div class="v-text-field__slot">
                              <label for="input-7" class="v-label v-label--active theme--dark" style="left: 0px; right: auto; position: absolute;">Your email adress</label>
                              <input v-model="form.username" required="required" name="login" id="input-7" type="text">
                            </div>
                          </div>
                          <div class="v-text-field__details">
                            <div class="v-messages theme--dark">
                              <div class="v-messages__wrapper"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="v-input v-input--is-label-active v-input--is-dirty theme--dark v-text-field v-text-field--is-booted v-text-field--placeholder">
                        <div class="v-input__control">
                          <div class="v-input__slot">
                            <div class="v-text-field__slot">
                              <label for="password" class="v-label v-label--active theme--dark" style="left: 0px; right: auto; position: absolute;">Password</label>
                              <input v-model="form.password" required="required" name="password" id="password" type="password">
                            </div>
                          </div>
                          <div class="v-text-field__details">
                            <div class="v-messages theme--dark">
                              <div class="v-messages__wrapper"></div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- <div class="v-input v-input--is-label-active v-input--is-dirty theme--dark v-text-field v-text-field--is-booted v-text-field--placeholder">
                        <div class="v-input__control">
                          <div class="v-input__slot">
                            <div class="v-text-field__slot">
                              <label for="password2" class="v-label v-label--active theme--dark" style="left: 0px; right: auto; position: absolute;">Repeat Password</label>
                              <input required="required" name="password2" id="password2" type="password">
                            </div>
                          </div>
                          <div class="v-text-field__details">
                            <div class="v-messages theme--dark">
                              <div class="v-messages__wrapper"></div>
                            </div>
                          </div>
                        </div>
                      </div> -->

                      <button type="submit" class="v-btn v-btn--block v-btn--contained theme--dark v-size--default primary">
                        <span class="v-btn__content">
                          Login
                        </span>
                      </button>
                    </div>
                  </div>
                </form>
                <div class="text-center mt-5"><a href="/resetpassword" class="">
                    Forget your password?
                  </a>
                </div>
              </div>
            </div>
            <!---->
          </div>
        </main>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.js"></script> -->
  <script>
    Vue.mixin({
      data: vm => ({
        // snackbar: false,
        // text: ''
      }),
      created() {
        this.$snackbar = (msg) => {
          alert(msg)
          // console.log(this)
          // this.snackbar = true
          // this.text = msg
        }
      }
    })

    const app = new Vue({
      el: '#app',
      data: vm => ({
        form: {
          username: "",
          password: ""
        },
        loading: false
      }),
      // router,
      // vuetify: new Vuetify({
      //   theme: {
      //     dark: true
      //   }
      // }),
      created() {
        // this.snackbar = true
        // this.text = 'Cool'
      },
      methods: {
        async submit(form) {
          console.log(form)
          // Create account
          // TODO use BooqBooq Clientside Javascript API

          try {
            const api = ''
            const resp = await fetch(`${api}/login`, {
              method: 'POST',
              body: JSON.stringify(form)
            }).then(resp => resp.json())

            console.log(resp)
            // this.$snackbar("Account created")

            window.localStorage.setItem('access_token', resp.token)

            // Redirect?
            window.location = "/"

          } catch (err) {
            this.$snackbar("Something went wrong")
          }
        }
      }
    })
  </script>


</body>

</html>