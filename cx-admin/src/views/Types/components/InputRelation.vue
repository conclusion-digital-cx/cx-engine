<template>
  <v-row>
    <v-col>
      <v-select
        v-model="form.target"
        :loading="loading"
        label="With"
        :items="items"
        item-text="name"
      />
    </v-col>
    <v-col>
      <v-select
        v-model="form.nature"
        :loading="loading"
        label="Type"
        :items="relationTypes"
        item-text="text"
      />
    </v-col>
    <v-col>
      <!-- {{ items }} -->
      <!-- {{ _targetColumnItems }} -->
      <v-autocomplete
        v-model="form.targetColumnName"
        :loading="loading"
        label="Display key"
        :items="_targetColumnItems"
        item-text="name"
      />
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    value: { type: Object, default () { return {} } }
  },

  data () {
    return {
      form: this.value,
      loading: false,
      items: [],
      targetColumnItems: [],
      relationTypes: [
        { text: 'oneToOne' },
        { text: 'manyToOne' }
      ]
    }
  },

  computed: {
    _targetColumnItems () {
      const { target } = this.form
      const type = this.items.find(elem => elem.name === target)
      console.log(target, type)
      return type ? type.attributes.map(elem => elem.name) : []
    }
  },

  watch: {
    value (newValue) {
      this.form = newValue
    },
    form: {
      handler (newValue) {
        console.log(newValue)
        this.$emit('input', newValue)
      },
      deep: true
    }
  },

  async created () {
    try {
      this.loading = true
      const resp = await this.$service.getAll('types')
      console.log(resp)
      this.items = resp
    } finally {
      this.loading = false
    }
  }

}
</script>
