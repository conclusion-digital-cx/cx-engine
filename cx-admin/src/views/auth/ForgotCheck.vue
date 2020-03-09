<script>
export default {

}
</script>

<template>
  <v-flex xs12 sm8 md4>
    <v-card class="elevation-12">
      <v-toolbar dark color="primary">
        <v-toolbar-title>Forgot password</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <v-card-text>
        Check your email.
      </v-card-text>
      <v-card-actions>
        <router-link :to="`/login`">
          Back to login?
        </router-link>
        <v-spacer />
        <!-- <v-btn
          :loading="loading"
          :disabled="loading"
          color="primary"
          @click.stop="submit(form)"
        >
          Send it now, please!
        </v-btn> -->
      </v-card-actions>
    </v-card>
  </v-flex>
</template>
