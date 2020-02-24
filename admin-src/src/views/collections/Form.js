const html = (strings) => strings[0]

export default {
    props: {
        name: { type: String, default: "" },
        id: { type: String, default: "" }
    },

    data() {
        return {
            form: {}
        }
    },
    methods: {
      submit (form) {
        console.log(form)
        this.$emit('submit', form)
      }
    },

    template: html`
  <div>
    <v-form ref="form" @submit.prevent="submit(form)">
  
      <v-text-field v-model="form.name" label="Name" />

      <v-row>
        <v-btn
          :loading="loading"
          color="primary"
          text
          type="submit"
        >
          Create
        </v-btn>
      </v-row>
  </v-form>  
  </div>
  `
}