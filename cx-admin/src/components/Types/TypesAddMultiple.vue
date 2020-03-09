<template>
  <v-form @submit.prevent="submit(form)">
    <v-textarea
      v-model="form.name"
      label="Name"
      placeholder="['users','resourcetypes']"
    />
    <v-btn
      type="submit"
      class="primary"
    >
      Add multiple
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
      this.$emit('submit', JSON.parse(form.name))
    }
  }
}
</script>
