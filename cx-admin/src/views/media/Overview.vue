<template>
  <div>
    <!-- <Dropzone /> -->

    <UploadPicture @drop="upload" />
    <!-- <InputAvatar v-model="form.avatar" /> -->
    <!-- {{ form }} -->

    <Grid
      :items="items"
    />

    Manual uploads
    <Grid
      :items="itemsManual"
    />
  </div>
</template>

<script>
export default {
  props: {
    name: { type: String, default: 'media' }
  },

  data: vm => ({
    form: {},
    items: [],
    itemsManual: []
  }),

  async created () {
    this.items = await this.$service.getAll(this.name, {})

    this.itemsManual = await this.$service.getAll('uploads', {})
  },

  methods: {
    async upload (body) {
      console.log('uploading')
      this.$snackbar('Uploading file...')
      // const resp = await this.$service.getWithTotalItems(this.name, {
      //     // 'state[!]': 'trash',
      //     // 'state': '',
      //     state: this.status === 'published' ? 'NULL' : this.status,
      //     ...options
      //   })

      // Create new media entry
      try {
        const resp = await this.$service.fetch('/upload', { // Your POST endpoint
          method: 'POST',
          headers: {

          },
          // headers: {
          //   // Content-Type may need to be completely **omitted**
          //   // or you may need something
          //   "Content-Type": "You will perhaps need to define a content-type here"
          // },
          body // This is your file object
        })
        console.log(resp)
      } catch (err) {
        console.warn(err)
        this.$snackbar('upload failed')
      }
    }
  }

}
</script>
