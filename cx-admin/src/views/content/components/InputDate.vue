<script>
export default {
  props: {
    value: { type: String, default: '' }
  },
  data () {
    return {
      date: this.value,
      menu: false
    }
  },
  watch: {
    date (newValue) {
      this.$emit('input', newValue)
    },
    value (newValue) {
      this.date = newValue
    }
  }
  // TODO emit propper datetime
}
</script>

<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        v-model="date"
        v-bind="$props"
        prepend-icon="event"
        readonly
        v-on="on"
      />
    </template>
    <v-date-picker v-model="date" @input="menu = false" />
  </v-menu>
</template>
