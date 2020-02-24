
<script>

export default {
  props: {
    items: { type: Array, default () { return [] } }
  },

  data () {
    return {
      dialog: false,
      form: {},
      value: {
        attributes: []
      }
      // item: sample[1]
    }
  },

  methods: {
    toggleEdit (item) {
      console.log(item)
      // item.editable = !item.editable
      this.dialog = true
    },

    deleteItem (item) {
      const removeElement = (array, elem) => {
        var index = array.indexOf(elem)
        if (index > -1) {
          array.splice(index, 1)
        }
      }

      removeElement(this.items, item)
    },

    addField () {
      this.items.push({
        name: '',
        editable: true,
        params: {
          type: 'string'
          // minLength: 3,
          // unique: true,
          // configurable: false,
          // required: true
        }
      })
    }
  }
}
</script>

<template>
  <div>
    <template v-for="(item,index) in items || []">
      <div :key="index">
        <v-list-item class="reveal-on-hover">
          <!-- {{ item }} -->
          <!-- <v-list-item-content> -->
          <v-row class="ma-0">
            <!-- Read only -->
            <template v-if="!item.editable">
              <v-col xs4>
                {{ item.name }}
              </v-col>
              <v-col cols="6">
                ??
              </v-col>
            </template>

            <!-- Editable -->
            <template v-else>
              <v-col xs4>
                <v-text-field v-model="item.name" label="Name" placeholder="Add new field" hide-details />
              </v-col>
              <v-col cols="6">
                <!-- <v-select v-model="item.params.type" label="Type" :items="TYPES" /> -->
                ???
              </v-col>
            </template>

            <v-col xs2>
              <v-btn icon class="hide-by-default" @click="toggleEdit(item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn icon class="hide-by-default">
                <v-icon>close</v-icon>
              </v-btn>
            </v-col>
          </v-row>
          <!-- </v-list-item-content> -->
        </v-list-item>

        <v-divider />
      </div>
    </template>

    <v-btn class="ma-2" outlined @click="addField">
      Add another field
    </v-btn>
  </div>
</template>

<style>
.reveal-on-hover .hide-by-default {
visibility: hidden;
}
.reveal-on-hover:hover .hide-by-default {
visibility: unset;
}
</style>
