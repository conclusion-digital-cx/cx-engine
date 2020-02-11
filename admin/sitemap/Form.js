const html = (strings) => strings[0]

import BaseDialog from './BaseDialog.js'

export default {
  components: {
    BaseDialog
  },

  props: {
    title: { type: String },
    value: { type: Boolean },
    form: { type: Object, default () { return {} } },
    props: { type: Object, default () { return {} } }
  },
  data () {
    return {
      loading: false,
      // _form: { ...this.form }
    }
  },
  computed: {
    // props () { return this.props }
  },

  methods: {
    validate (form) {
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async submit (form) {
      console.log(form)

      try {
        this.loading = true
        const resp = await fetch(`/api/collections`, { 
          method: 'POST',
          body: JSON.stringify(form) 
        })
        this.$emit('success')

      } finally {
        this.loading = false
        // Close form
        this.$emit('input',false)
      }
      // Send to server?
      // const resp = await service.saveNode(form)
      // console.log(resp)

      // this.$emit('submit', form)
    }
  },

  template: html`
  <v-form ref="form" @submit.prevent="validate(form)">
    <BaseDialog
      :title="title"
      :value="value"
      @input="$emit('input',$event)"
    >
      <!-- <v-text-field v-model='value.file' type="file" label="Load from a file" />
      - or - -->
      <v-text-field v-model="form.name" label="Name" placeholder="e.g. products, news or menus" />

      <h2>Options</h2>
        <div v-for="([key,value2],index) in Object.entries(props)" :key="index">
          <!-- String -->
          <template v-if="value2.type === 'string'">
            <v-text-field v-model="options[key]" :label="key" />
          </template>

          <!-- Enum -->
          <template v-else-if="value2.type === 'enum'">
            <v-select v-model="options[key]" :items="value2.enum.map(elem=> ({value:elem, text:elem}))" :label="key" />
          </template>

          <!-- Default -->
          <template v-else>
            <v-text-field v-model="options[key]" :label="key" />
          </template>
        </div>

      <!-- <v-textarea v-model="value.flat" label="Paste exported data" /> -->

      <template #actions>
        <v-spacer />
        <v-btn
          :loading="loading"
          color="primary"
          text
          @click="validate(form)"
          type="submit"
        >
          Apply
        </v-btn>
      </template>
    </BaseDialog>
  </v-form>`
}
