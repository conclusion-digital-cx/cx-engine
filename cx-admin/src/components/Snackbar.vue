<template>
  <div>
    <v-snackbar
      v-model="show"
      :timeout="timeout"
      :color="state.color"
    >
      {{ state.text }}
      <template v-if="state.dismissable">
        <v-btn
          dark
          text
          @click.native="show = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  props: {
    timeout: { type: Number, default: 2000 }
  },
  data () {
    return {
      show: false,
      state: {
        text: '',
        dismissable: false
      }
    }
  },
  created () {
    // this.message('Cool', true)
  },
  methods: {
    // Public
    message (message, dismissable = false) {
      if (this.show) {
        console.warn('Only one snackbar allowed, closing previous.', message)
        // return false
      }
      this.show = true
      this.state.text = message
      this.state.dismissable = dismissable
      return true
    }
  }
}
</script>
