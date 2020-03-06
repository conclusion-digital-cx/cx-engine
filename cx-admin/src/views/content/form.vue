
<script>
import BaseDialog from '@/components/BaseDialog.vue'
// import DynamicForm from '@/components/DynamicForm.vue'
// import { ROW_KEY } from '@/config'

export default {
  components: {
    // DynamicForm,
    BaseDialog
  },

  props: {
    id: { type: String, default: null },
    // type: { type: String, default: 'users' },
    typeSlug: { type: String, default: 'notset' },
    typeId: { type: String, default: null }
    // props: { type: Array, default () { return [] } }
  },

  data () {
    return {
      loading: false,
      fields: [],
      form: {},
      type: {}
      // collectionName: '' // <= TEMPFIX
    }
  },

  computed: {
    _primaryKey () {
      return this.$store.state.settings.defaultPrimaryKey
    },

    _name () {
      return this.typeSlug
    },
    _id () {
      // return this.form[this.type.key || ROW_KEY]
      return this.id
    },
    //= ===========
    // Flatten strapi structure
    //= ===========
    _fields () {
      return this.type.attributes ? this.type.attributes.map(elem => ({
        name: elem.name,
        ...elem
        // ...elem.params
      })) : []
    }
  },

  watch: {
    $route (to, from) {
      this.fetch()
    }
  },

  async created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      // Fetch fields
      await this.fetchFields()

      // Fetch data
      if (this.id) {
        this.fetchData(this.id)
      }
    },

    async fetchFields () {
      try {
        this.loading = true
        const type = await this.$serviceFactory('types').getOne({ name: this.typeSlug })
        this.type = type

        return type
      } finally {
        this.loading = false
      }
    },

    async fetchData (id) {
      try {
        this.loading = true

        const data = await this.$serviceFactory(this.typeSlug).getById(id, {
          params: {}
        // { populate: ['company', 'user'] }
        })
        console.log(data)

        this.form = data
      } finally {
        this.loading = false
      }
    },

    validate (form) {
      if (this.$refs.form.validate()) {
        this.submit(form)
      }
    },

    async deleteItem (form) {
      try {
        await this.$serviceFactory(this.typeSlug)
          .deleteById(form[this.type.key || this._defaultPrimaryKey], form)
        this.$router.push(`/content/${this.typeSlug}`)
      } catch (err) {
        console.warn(err)
      }
    },

    async submit (form) {
      // const name = this.type // this.collectionName

      try {
        this.loading = true

        // Update or create
        const resp = await this.$serviceFactory(this.typeSlug)
          .createOrUpdateById(this._id, form)

        console.log(resp)
        // const { data } = resp
        // this.form = data

        this.$emit('success')
      } catch (err) {
        this.$snackbar('Ai, something went wrong', err)
      } finally {
        this.loading = false
        // Close form
        // this.$emit('input', false)

        this.$snackbar('Item saved')
        // Return to listing
        // this.$router.push(`/content/${this.typeSlug}/${this.typeId}`)
      }
    }

  }
}
</script>

<template>
  <div>
    <!-- {{ props }} -->
    <BaseDialog
      :title="`Add / edit ${_name}`"
      :value="true"
      @input="$router.push(`/content/${_name}`)"
    >
      <small>#${{ _id }}</small>
      <v-form
        ref="form"
        @submit.prevent="validate(form)"
      >
        <!-- {{ _fields }} -->
        <DynamicForm
          v-model="form"
          :fields="_fields"
          :primary-key="_primaryKey"
        />
      </v-form>
      <!-- {{ form }} -->

      <template #actions>
        <v-btn
          :loading="loading"
          color="error"
          text
          @click="deleteItem(form)"
        >
          Delete
        </v-btn>
        <v-spacer />
        <v-btn
          :loading="loading"
          color="primary"
          text
          type="submit"
          @click="validate(form)"
        >
          Apply
        </v-btn>
      </template>
    </BaseDialog>
  </div>
</template>
