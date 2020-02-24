<template>
  <v-snackbar
    v-model="$snackbarState.visible"
    :color="$snackbarState.color"
  >
    {{ $snackbarState.text }}
    <v-btn
      text
      @click="$snackbarState.visible = false"
    >
      Close
    </v-btn>
  </v-snackbar>
</template>
