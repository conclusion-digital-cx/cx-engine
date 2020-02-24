<template>
  <div>
    <v-btn outlined @click="toggle">
      {{ label }} <v-icon>{{ icon }}</v-icon>
    </v-btn>
    <div v-if="value || show">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: { type: String, default: 'Show more' },
    value: { type: Boolean, default: false },
    iconDown: { type: String, default: 'keyboard_arrow_down' },
    iconUp: { type: String, default: 'keyboard_arrow_up' }
  },
  data () {
    return {
      show: false
    }
  },
  computed: {
    icon () {
      return this.show ? this.iconUp : this.iconDown
    }
  },
  methods: {
    toggle () {
      this.$emit('input', !this.value)
      this.show = !this.show
    }
  }
}
</script>
