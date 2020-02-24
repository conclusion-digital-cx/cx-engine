<script>
export default {
  props: {
    value: { type: String, default: '' },
    nature: { type: String, default: '' },
    target: { type: String, default: '' },
    targetColumnName: { type: String, default: 'name' }
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
    <!-- {{ nature }} -->
    <!-- {{ $attrs }} -->
    <v-select
      :value="value"
      :loading="loading"
      v-bind="$attrs"
      :items="items"
      :multiple="nature === 'manyToOne'"
      item-value="id"
      :item-text="targetColumnName || 'name'"
      @input="$emit('input',$event)"
    />
  </div>
</template>
