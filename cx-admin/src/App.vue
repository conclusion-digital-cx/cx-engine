<template>
  <DefaultLayout>
    <router-view />

    <!-- <v-btn @click="$snackbar('he')" /> -->
    <AppSnackbar />
  </DefaultLayout>
</template>

<script>
// Register all layouts
import './layouts'

export default {
  data: vm => ({
  }),
  // computed: {
  //   layout () {
  //     return `${this.$route.meta.layout || 'DefaultLayout'}`
  //   }
  // },
  async created () {
    const token = this.$route.query.token
    if (token) {
      window.localStorage.setItem('token', token)
    }

    // this.$service = $service.setBearerToken(token)
  }
}
</script>
