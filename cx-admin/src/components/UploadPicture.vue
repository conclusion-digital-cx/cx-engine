<style>
.dropregion {
  min-width:100px;
  min-height:100px;
  border: 5px dashed black;
  padding:10px;
}
.dropregion__over {
  border: 5px dashed green;
}
</style>

<script>

export default {
  props: {
    value: { type: String, default: '' }
  },

  data: vm => ({
    over: false,
    image: ''
  }),

  methods: {
    onDragover (ev) {
      //   console.log(ev)
    },
    onDragenter (ev) {
      this.over = true
      //   console.log(ev)
    },
    onDragleave (ev) {
      this.over = false
      //   console.log(ev)
    },

    async onDrop (ev) {
      const { items } = ev.dataTransfer
      if (items.length) {
        if (items[0].kind === 'file') {
          const file = items[0].getAsFile()
          console.log(file.name)
          console.log(file)

          const body = new FormData()
          body.append('fileToUpload', file)

          // const resp = await fetch('/api/upload', { // Your POST endpoint
          //   method: 'POST',
          //   // headers: {
          //   //   // Content-Type may need to be completely **omitted**
          //   //   // or you may need something
          //   //   "Content-Type": "You will perhaps need to define a content-type here"
          //   // },
          //   body // This is your file object
          // })
          //   .then(response => response.json())
          //   .catch(error => console.warn(error))

          // Use image
          // this.image = file // resp.url

          // Tell parent our new state
          this.$emit('drop', body)

          // Render
          // this.$emit('render', this.render())
        }
      }
    },

    render () {
      return `<img src="${this.value}"/>`
    }

  }
}

</script>

<template>
  <div
    class="dropregion"
    :class="over ? 'dropregion__over': ''"
    @drop.stop.prevent="onDrop"
    @dragover.stop.prevent="onDragover"
    @dragenter.stop.prevent="onDragenter"
    @dragleave.stop.prevent="onDragleave"
  >
    <slot>
      {{ value }}
      Drop files here or click to upload.
      <input type="file">
      <img :src="value">
    </slot>
  </div>
</template>
