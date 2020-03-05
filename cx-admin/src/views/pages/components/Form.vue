<script>

export default {
  props: {
    id: { type: String },
    title: { type: String },
    value: { type: Object, default () { return {} } },
    props: { type: Object, default () { return {} } }
  },

  data () {
    return {
      loading: false,
      form: {
        ...this.value
      }
      // _form: { ...this.form }
    }
  },

  computed: {
    // props () { return this.props }
  },

  async created () {
    if (this.id) {
      const resp = await this.$service.getById('pages', this.id)
      this.form = resp
    }
  },

  methods: {
    validate (form) {
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async submit (form) {
      console.log(form, this.id)

      try {
        this.loading = true
        await this.$service.createOrUpdateById('pages', this.id, form)
        this.$emit('success')
      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    },

    async onClickDelete (form) {
      try {
        this.loading = true
        await this.$service.deleteById('pages', this.id)
        this.$emit('success')
      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    }
  }

}

</script>

<template>
  <v-form
    ref="form"
    @submit.prevent="validate(form)"
  >
    <v-row>
      <v-col cols="8">
        <div
          contenteditable
          @blur="form.body = $event.target.innerHTML"
          v-html="form.body"
        >
          loading...
        </div>
      </v-col>
      <v-col>
        <v-text-field
          v-model="form.title"
          label="Page title"
          placeholder="e.g. Homepage"
        />
        <v-text-field
          v-model="form.url"
          label="Url"
          placeholder="e.g. /about/us"
        />
        <v-textarea
          v-model="form.body"
          label="Page content"
        />
        <!-- <v-textarea v-model="form.blocks" label="Blocks" /> -->
        {{ form.blocks }}
      </v-col>
    </v-row>

    <v-row>
      <v-btn
        :loading="loading"
        color="primary"
        text
        type="submit"
      >
        Apply
      </v-btn>
      <v-spacer />

      <v-btn
        :loading="loading"
        color="error"
        text
        @click="onClickDelete(form)"
      >
        Delete
      </v-btn>
    </v-row>
  </v-form>
</template>
