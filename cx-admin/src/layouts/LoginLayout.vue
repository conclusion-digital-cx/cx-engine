<script>
export default {
}
</script>

<template>
  <v-app
    id="inspire"
    dark
  >
    <v-content>
      <v-layout
        align-center
        justify-center
        style="min-height:100%"
        pa-2
      >
        <slot />
      </v-layout>
    </v-content>
  </v-app>
</template>

<style>
    body,
    html {
        height: 100%;
        margin: 0;
        font: 400 15px/1.8 "Lato", sans-serif;
        color: #777;
    }

    #inspire {
        background-image: url("https://booqbooq.com/global/logout.jpg");
        height: 100%;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
