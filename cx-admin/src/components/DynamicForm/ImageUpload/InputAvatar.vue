<script>
export default {
  props: {
    value: { type: String, default: '' }
  },
  methods: {
    onInput (value) {
    //   console.log(value)
      this.$emit('input', value)
    }
  }
}
</script>

<template>
  <div>
    <v-avatar
      :size="120"
      color="grey lighten-4"
    >
      <v-img
        :src="value"
        contain
        alt="avatar"
      />
    </v-avatar>
    <ImageResize
      :value="value"
      @input="onInput"
    />
    <!-- <p>
      A Profile image of the person, it's best if the picture has the same length and height
    </p> -->
  </div>
</template>
