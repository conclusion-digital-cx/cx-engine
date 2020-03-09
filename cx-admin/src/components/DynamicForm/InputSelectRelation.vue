<script>
export default {
  props: {
    value: { type: String, default: '' },
    nature: { type: String, default: '' },
    target: { type: String, default: '' },
    targetColumnName: { type: String, default: 'name' },
    primaryKey: { type: String, default: '_id' }
  },
  data () {
    return {
      loading: false,
      items: []
    }
  },

  async created () {
    try {
      this.loading = true
      // TEMP FIX
      // console.log(this.$attrs)

      const collectionName = this.target
      // this.$attrs.plugin
      //   ? `${this.$attrs.plugin}_${this.target}`
      //   : this.target

      // TODO
      const resp = await this.$service.getAll(collectionName)

      this.items = resp
    } finally {
      this.loading = false
    }
  }
}
</script>

<template>
  <div>
    <!-- {{ items }} -->
    <!-- {{ target }} -->
    <!-- {{ value }} -->
    <!-- {{ primaryKey }} -->
    <!-- {{ nature }} -->
    <!-- {{ $attrs }} -->
    <v-autocomplete
      :value="value"
      :loading="loading"
      v-bind="$attrs"
      :items="items"
      clearable
      :multiple="nature === 'manyToOne'"
      :item-value="primaryKey"
      :item-text="targetColumnName || 'name'"
      @input="$emit('input',$event)"
    />
  </div>
</template>
