<template>
  <div>
    <!-- {{ value }} -->
    <input
      id="select"
      type="file"
      @change="change"
    >
    <v-img
      v-if="showPreview"
      id="preview"
      :src="value"
    />
  </div>
</template>

<script>
import ImageTools from './ImageTools'

const convertBlobToBase64 = blob => {
  return new Promise((resolve, reject) => {
    // Convert to base64
    const reader = new FileReader()
    reader.readAsDataURL(blob)
    reader.onloadend = () => {
      const base64data = reader.result
      resolve(base64data)
    }
  })
}

export default {
  props: {
    value: { type: String, default: '' },
    showPreview: { type: Boolean, default: false },
    width: { type: Number, default: 320 },
    height: { type: Number, default: 240 }
  },
  data () {
    return {
      src: this.value
    }
  },
  methods: {
    change (evt) {
      const file = evt.target.files[0]
      ImageTools.resize(file, {
        width: 320, // maximum width
        height: 240 // maximum height
      }, async (blob, didItResize) => {
        // console.log(blob)
        // didItResize will be true if it managed to resize it, otherwise false (and will return the original file as 'blob')
        // this.src = window.URL.createObjectURL(blob)

        const base64 = await convertBlobToBase64(blob)
        console.log(base64)

        this.$emit('input', base64)
        // you can also now upload this blob using an XHR.
      })
    }
  }
}
</script>
