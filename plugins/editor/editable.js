import "./Grid.js"

// IMPROVE
Array.prototype.move || Object.defineProperty(Array.prototype, "move", {
  value: function (index, howMany, toIndex) {
    var
      array = this,
      index = parseInt(index) || 0,
      index = index < 0 ? array.length + index : index,
      toIndex = parseInt(toIndex) || 0,
      toIndex = toIndex < 0 ? array.length + toIndex : toIndex,
      toIndex = toIndex <= index ? toIndex : toIndex <= index + howMany ? index : toIndex - howMany,
      moved;

    array.splice.apply(array, [toIndex, 0].concat(moved = array.splice(index, howMany)));

    return moved;
  }
});

const html = (strings) => strings[0]

export default {
  props: {
    value: { type: Array, default: () => ([]) },
    components: { type: Array, default: () => ([]) }, 
    preview: { type: Boolean, default: false }, 
  },

  data: vm => ({
    message: 'You loaded this page on ' + new Date().toLocaleString(),
    page: {
      url: ''
    },
    loading: false,
    snackbar: false,
    blocks: vm.value,
    editor: localStorage.getItem('editor') !== 'false',
    // components: []
  }),

  computed: {
    _componentsLookup() {
      const lookup = {}
      this.components.forEach(elem => {
        lookup[elem.name] = elem
      })
      return lookup
    }
  },
  
  watch: {
    editor(newValue) { localStorage.setItem('editor', newValue) },

    // Sync blocks = blocks
    // blocks(newValue) {
    //   this.$emit('input', newValue)
    // },
    value(newValue) {
      console.log("Set new blocks")
      this.blocks = newValue
    }
  },

  async created() {
  
  },

  methods: {
    // Clientside JS block
    async addBlockJs(item) {
      console.log(item)
      this.blocks.push({
        // component: item, 
        name: item.name,
        render: '',
        // data: {},
        props: {}
      })
      // this.blocks.push(item)

      this.$emit('input', this.blocks)
    },

    upBlock(item) {
      const array = this.blocks
      const index = array.indexOf(item)
      array.move(index, 1, index - 1)
    },

    downBlock(item) {
      const array = this.blocks
      const index = array.indexOf(item)
      array.move(index, 1, index + 2)
    },

    deleteBlock(item) {
      const array = this.blocks
      array.splice(array.indexOf(item), 1);
    },

    // Auto add new block ?
    onEnter(item, $event) {
      console.log('enter', item, $event)
      item.render = $event
    },
    // New render
    onRender(item, $event) {
      console.log('render', item, $event)
      item.render = $event
    },
    // Data change of block
    onInput(item, $event) {
      // console.log('onInput', item, $event)
      // item.data.value = $event
      item.props = {
        value: $event
      }
      // item.data = $event

      // Tell parent
      // console.log('tell parent')
      this.$emit("input", this.blocks)
    },

  },

  template: html`
  <div>
    <!-- Total {{components.length}} js components available -->

    <!-- Render blocks -->
    <!-- blocks: {{blocks}} -->

    <template v-if="preview">
      <div v-for="(item,index) in blocks" :key="index">
        <div v-html="item.render"></div>
      </div>
    </template>

    <template v-else>
      <!-- Blocks -->
      <div v-for="(item,index) in blocks" :key="index">
        <div class="block">
          <div class="block__heading">
            <div class="block__title" >
              {{item.name}}
              <!-- ({{index}}) -->
            </div>

            <div class="actions text-right">
              <v-btn icon text class="pull-right" @click.prevent="upBlock(item)">
                <v-icon>keyboard_arrow_up</v-icon>
              </v-btn>

              <v-btn icon text class="pull-right" @click.prevent="downBlock(item)">
                <v-icon>keyboard_arrow_down</v-icon>
              </v-btn>

              <v-btn icon text class="pull-right" @click.prevent="deleteBlock(item)">X</v-btn>
            </div>
          </div>

          <!-- JS Edit modus -->
          <!-- <small>data: {{item.data}}</small> -->
          <component
            :components="components"
            v-bind="item.props"
            :is="_componentsLookup[item.name]"
            @done="onEnter(item, $event)"
            @input="onInput(item, $event)"
            @render="onRender(item, $event)"
          />
        </div>
      </div>

      <!-- Add Block -->
      <v-menu :close-on-content-click="false" :nudge-width="200" offset-x>
        <template #activator="{ on }">
          <v-btn color="indigo" dark v-on="on" outlined>Add block</v-btn>
        </template>

        <v-card>
          <grid :items="components">
            <template #card="{item}">
              <v-card @click="addBlockJs(item)" class="show-actions-on-hover">
                <v-card-title>
                  <div class="text-truncate" style="width:60%">{{ item.name || 'No title' }}</div>
                  <v-spacer />
                </v-card-title>
              </v-card>
            </template>
          </grid>
        </v-card>
      </v-menu>
    </template>
  </div>
 `
}