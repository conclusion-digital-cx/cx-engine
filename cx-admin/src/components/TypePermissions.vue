<script>
export default {
  props: {
    value: { type: Object, default: () => ({}) }
  },
  data: vm => ({
    // form: {},

    permissions: [
      // { text: 'count' },
      { text: 'find' },
      { text: 'create' },
      { text: 'findone' },
      { text: 'delete' },
      { text: 'update' }
    ]
  }),
  watch: {
    form (newValue) { this.$emit('input', newValue) }
  }
}
</script>

<template>
  <div>
    {{ value }}

    <template v-for="(item,index) in permissions || []">
      <div :key="index">
        <v-list-item class="reveal-on-hover">
          <!-- {{ item.text }} -->
          <v-switch
            v-model="value[item.text]"
            :label="item.text "
          />
        </v-list-item>

        <v-divider />
      </div>
    </template>
  </div>
</template>
