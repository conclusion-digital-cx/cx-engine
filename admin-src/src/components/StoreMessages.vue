<template>
  <div>
    <v-snackbar v-model="showSnackbar" :timeout="state.timeout" :color="state.color">
      {{ state.text }}
      <!-- <v-btn dark text @click.native="showSnackbar = false">Close</v-btn> -->
    </v-snackbar>

    <v-dialog v-model="showDialog" width="500">
      <v-card>
        <v-card-title class="headline" primary-title>
          {{ state.title }}
        </v-card-title>

        <v-card-text>
          <p v-if="state.text">
            {{ state.text }}
          </p>
          <p v-else>
            We are very sorry but at the moment the server can't be reached, please try it later.
          </p>
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn color="primary" text @click="showDialog = false">
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  computed: {
    state () {
      return this.$store.state.ui.snackbar
    },
    // showDialog () { return this.state.show && this.state.modal },
    // showSnackbar () { return this.state.show && !this.state.modal }

    showSnackbar: {
      get () {
        return this.state.show && !this.state.modal
      },
      set () {
        this.state.show = false
      }
    },
    showDialog: {
      get () {
        return this.state.show && this.state.modal
      },
      set () {
        this.state.show = false
      }
    }
  }
}
</script>
