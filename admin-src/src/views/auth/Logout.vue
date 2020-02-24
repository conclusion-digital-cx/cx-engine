<script>
// import auth from '@/services/auth'

export default {
  mounted () {
    this.$store.dispatch('logoutUser')
  }
}
</script>

<template>
  <v-card class="elevation-5 text-xs-center">
    <span class="headline">Bye Bye</span><br>
    <span class="subheading">Till next time</span><br>
    <!-- <router-link to='/login'>Go back to login</router-link> -->
    <v-btn outlined to="/login">
      Go back to login
    </v-btn>
  </v-card>
</template>
