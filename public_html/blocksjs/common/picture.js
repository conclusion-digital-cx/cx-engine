{/* <style>
.dropregion {
  min-width:100px;
  min-height:100px;
  border: 5px dashed black;
  padding:10px;
}
.dropregion__over {
  border: 5px dashed green;
}
</style> */}

// This will upload the file after having read it
const upload = (file) => {
  fetch('http://www.example.net', { // Your POST endpoint
    method: 'POST',
    headers: {
      // Content-Type may need to be completely **omitted**
      // or you may need something
      "Content-Type": "You will perhaps need to define a content-type here"
    },
    body: file // This is your file object
  }).then(
    response => response.json() // if the response is a JSON object
  ).then(
    success => console.log(success) // Handle the success response object
  ).catch(
    error => console.log(error) // Handle the error response object
  );
};


const sheet = document.createElement('style')
sheet.innerHTML = `
.dropregion {
    min-width:100px;
    min-height:100px;
    border: 5px dashed black;
    padding:10px;
  }
  .dropregion__over {
    border: 5px dashed green;
  }
`;
document.body.appendChild(sheet);

export default {
  name: "picture",

  props: {
    value: { type: String, default: ''}
  },

  data: vm => ({
    over: false,
    image: ""
  }),

  methods: {
    onDragover(ev) {
      //   console.log(ev)
    },
    onDragenter(ev) {
      this.over = true
      //   console.log(ev)
    },
    onDragleave(ev) {
      this.over = false
      //   console.log(ev)
    },
    onDrop2(e) {
      e.stopPropagation()
      e.preventDefault()

      console.log(e)

      const data = e.dataTransfer.getData('text/plain')
      //   event.target.textContent = data
      console.log(data)
    },

    async onDrop(ev) {
      const { items } = ev.dataTransfer
      if (items.length) {
        if (items[0].kind === 'file') {
          const file = items[0].getAsFile()
          console.log(file.name)
          console.log(file)

          const body = new FormData()
          body.append('fileToUpload', file)

          const resp = await fetch('/api/upload', { // Your POST endpoint
            method: 'POST',
            // headers: {
            //   // Content-Type may need to be completely **omitted**
            //   // or you may need something
            //   "Content-Type": "You will perhaps need to define a content-type here"
            // },
            body // This is your file object
          })
            .then(response => response.json())
            .catch(error => console.warn(error))

            // Use image
            this.image = resp.url

            // Tell parent our new state
            this.$emit("input", resp.url)

            // Render
            this.$emit('render', this.render())
        }
      }
    },

    render() {
      return `<img src="${this.value}"/>`
    }

  },
  template: `<div
  class="dropregion"
  :class="over ? 'dropregion__over': ''"
  @drop.stop.prevent="onDrop"
  @dragover.stop.prevent="onDragover"
  @dragenter.stop.prevent="onDragenter"
  @dragleave.stop.prevent="onDragleave"
>
{{value}}
    Drop files here or click to upload.
    <img :src="value"/>
</div>`
}
