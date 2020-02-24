<template>
  <v-form @submit.prevent="submit(form)">
    <v-text-field v-model="form.name" label="Name" />
    <v-btn type="submit" class="primary">
      Add
    </v-btn>
  </v-form>
</template>

<script>
export default {

  data () {
    return {
      form: {}
    }
  },
  methods: {
    submit (form) {
      console.log(form)
      this.$emit('submit', form)
    }
  }
}
</script>
