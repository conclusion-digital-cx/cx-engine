<script>
export default {
  props: {
    value: { type: Boolean, default: false },
    persistent: { type: Boolean, default: false },
    width: { type: String, default: "" }
  }
};
</script>

<template>
  <v-dialog :width="width" :value="value" :persistent="persistent" @input="$emit('input',$event)">
    <Card class="theme--light mx-auto">
      <span class="card-title">
        <slot name="title" />
      </span>
      <span class="card-content">
        <slot />
      </span>
      <slot name="actions-container">
        <v-divider />
        <v-row class="n2-pa-2">
          <slot name="actions" />
        </v-row>
      </slot>
    </Card>
  </v-dialog>
</template>