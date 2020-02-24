<script>

export default {
  props: {
    items: { type: Array, default () { return [] } },
    loading: { type: Boolean, default: false }
  }
}
</script>

<template>
  <v-container grid-list-xl>
    <v-layout wrap>
      <v-flex v-if="loading">
        <v-progress-circular
          indeterminate
          color="primary"
        />
      </v-flex>

      <!-- <v-flex v-if="!loading && !items.length">
          <v-alert
            border="left"
            color="indigo"
            dark
          >
            No items
          </v-alert>
        </v-flex> -->

      <!-- Grid -->
      <template v-if="!loading">
        <template v-for="(item,index) in items">
          <v-flex :key="index" xs3>
            <slot name="card" :item="item">
              <!-- Fallback content -->
              {{ item }}
            </slot>
          </v-flex>
        </template>
      </template>
    </v-layout>
  </v-container>
</template>
