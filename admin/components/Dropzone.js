
{/* <style>
.Dropzone {
  min-width:100px;
  min-height:100px;
  border: 5px dashed black;
  padding:10px;
}
.Dropzone__over {
  border: 5px dashed green;
}
</style> */}

export default {
  data: vm => ({
    over: false
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
    onDrop2 (e) {
      e.stopPropagation()
      e.preventDefault()

      console.log(e)

      const data = e.dataTransfer.getData('text/plain')
      //   event.target.textContent = data
      console.log(data)
    },

    onDrop (ev) {
      // const mixed = ev.dataTransfer.getData('text')
      console.log(ev.dataTransfer)

      // Drop of component
      const text = ev.dataTransfer.getData('text')
      console.log('Drop of text', text)
      if (text) {
        console.log(text)
        return
      }

      // Drop of file
      const { items } = ev.dataTransfer
      if (items.length) {
        if (items[0].kind === 'file') {
          const file = items[0].getAsFile()
          console.log(file.name)
          console.log(file)

          // var file = e.dataTransfer.files[0],
          const reader = new FileReader()
          reader.onload = (event) => {
            console.log(event.target)
            const response = event.target.result
            console.log(response)

            // Add to canvas
            // this.project = project
            // const newNodes = mergeNodesWithComponentBlueprints(project.nodes, this.components)
            const data = JSON.parse(response)
            this.$emit('input', data)
          }
          console.log(file)
          reader.readAsText(file)
        }
      }

      // designService.addNodeFromComponentId(componentName, newNode)
    }
  },
  template: `  <div
  class="Dropzone"
  :class="over ? 'Dropzone__over': ''"
  @drop.stop.prevent="onDrop"
  @dragover.stop.prevent="onDragover"
  @dragenter.stop.prevent="onDragenter"
  @dragleave.stop.prevent="onDragleave"
>
  <slot>Drop files here or click to upload.</slot>
</div>`
}
