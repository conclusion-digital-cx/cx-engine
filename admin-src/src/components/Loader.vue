<script>
export default {
  props: {
    loading: { type: Boolean, default: true }
  }
}
</script>

<template>
  <div>
    <v-layout v-if="loading" justify-center align-center class="pa-4">
      <v-progress-circular
        indeterminate
        :size="20"
        :width="2"
        color="primary"
      />

      <div class="ml-2">
        Loading...
      </div>
    </v-layout>
    <slot v-if="!loading" />
  </div>
</template>
