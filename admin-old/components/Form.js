const html = (strings) => strings[0]

import BaseDialog from './BaseDialog.js'

export default {
  components: {
    BaseDialog
  },

  props: {
    name: { type: String, default: 'collections' },

    title: { type: String, default: 'No title' },
    value: { type: Boolean },
    form: { type: Object, default() { return {} } },
    props: { type: Object, default() { return {} } }
  },
  data() {
    return {
      loading: false,
      // _form: { ...this.form }
    }
  },
  computed: {
    options() { return this.form }
  },

  methods: {
    validate(form) {
      console.log('cool')
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async submit(form) {
      console.log(form)

      try {
        this.loading = true

        if (form.id) {
          const resp = await fetch(`/api/${this.name}/${form.id}`, {
            method: 'PUT',
            body: JSON.stringify(form)
          })
        } else {
          const resp = await fetch(`/api/${this.name}`, {
            method: 'POST',
            body: JSON.stringify(form)
          })
        }

        this.$emit('success')

      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
      // Send to server?
      // const resp = await service.saveNode(form)
      // console.log(resp)

      // this.$emit('submit', form)
    }
  },

  template: html`
    <BaseDialog
      :title="title"
      :value="value"
      @input="$emit('input',$event)"
    >
  <v-form ref="form" @submit="validate(form)">

      <!-- <v-text-field v-model='value.file' type="file" label="Load from a file" />
      - or - -->
      <!-- <v-text-field v-model="form.name" label="Name" placeholder="e.g. products, news or menus" /> -->

      <!-- {{props}} -->
      <!-- <h2>Options</h2> -->
        <div v-for="([key,value2],index) in Object.entries(props)" :key="index">
          <!-- String -->
          <template v-if="value2.type === 'string'">
            <v-text-field v-model="options[key]" :label="key" v-bind="value2"/>
          </template>

          <!-- TextArea -->
          <template v-else-if="value2.type === 'textarea'">
            <v-textarea v-model="options[key]" :label="key" v-bind="value2" />
          </template>

          <!-- Boolean -->
          <template v-else-if="value2.type === 'boolean'">
            <v-switch v-model="options[key]" :label="key" />
          </template>

          <!-- Image -->
          <template v-else-if="value2.type === 'image'">
            <v-text-field v-model="options[key]" :label="key" v-bind="value2" type="text"/>
          </template>

          <!-- JSON (TODO) -->
          <template v-else-if="value2.type === 'json'">
            <v-textarea v-model="options[key]" :label="key" v-bind="value2"/>
          </template>

          <!-- Enum -->
          <template v-else-if="value2.type === 'enum'">
            <v-select 
            v-model="options[key]" 
            v-bind="value2"
            :items="value2.enum.map(elem=> ({value:elem, text:elem}))" 
            :label="key" />
          </template>

          <!-- Default -->
          <template v-else>
            <v-text-field 
            v-model="options[key]" 
            :label="key" 
            v-bind="value2"/>
          </template>
        </div>

        <!-- Hidden submit button to support onenter -->
        <v-btn
          class="d-none"
          type="submit"
        >
          Apply
        </v-btn>
        
      <!-- <v-textarea v-model="value.flat" label="Paste exported data" /> -->
      </v-form>

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
 `
}
