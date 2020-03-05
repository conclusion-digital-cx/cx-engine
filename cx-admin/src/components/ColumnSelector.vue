<script>
import Sortable from 'sortablejs'

export default {
  directives: {
    // Based on http://macr.ae/article/vue-sortable.html
    sortable: {
      inserted (el, binding, vnode) {
        const sorting = binding.value

        // Use the Sortable lib
        Sortable.create(el, {
          onUpdate (e) {
            // Mutate the sorting array
            const { oldIndex, newIndex } = e
            const deleted = sorting.splice(oldIndex, 1)
            sorting.splice(newIndex, 0, deleted[0])

            vnode.child.$emit('sort', sorting)
          }
        })
      }
    }
  },

  props: {
    value: {
      // The selected items
      type: Array,
      default () {
        return [
          // E.G. 'column1','column2'
        ]
      }
    },
    headers: {
      // All the headers, to be selected / ordered
      type: Array,
      default () {
        return [
          // { value: "column1", text: "Column 1" },
          // { value: "column2", text: "Column 2" }
        ]
      }
    }
  },

  data: vm => ({
    selection: vm.value,
    headersInOrder: vm.headers
  }),

  computed: {
    _selectionFlat () {
      return this.selection.map(elem => elem.value)
    },
    _fixedHeaders () {
      return this.headers.filter(elem => elem.fixed)
    },
    _sortableHeaders () {
      return this.headers.filter(elem => !elem.fixed)
    },
    _inOrderSelection () {
      // headers = source of truth for order
      const { headersInOrder, _selectionFlat } = this
      return headersInOrder.filter(({ value }) => {
        return _selectionFlat.includes(value)
        // return true;
      })
    }
  },

  watch: {
    // Handle selection change
    selection () {
      this.emitSelected()
    }
  },

  methods: {
    // Handle order change
    onSort (newOrder) {
      // Update the headers ( triggers compute of _inOrderSelection )
      this.headersInOrder = newOrder

      this.emitSelected()
    },
    emitSelected () {
      const payload = [
        // ...this._fixedHeaders,
        ...this._inOrderSelection
      ]
      this.$emit('input', payload)
    },
    closeColumnSelector () {
      this.$emit('close')
    }
  }
}
</script>

<template>
  <div>
    <h1>Columns</h1>Selecting more than 6 columns will affect the readability of the table.
    <div>
      <p>
        Total selected
        <b>{{ selection.length }}</b> columns.
      </p>

      <v-list class="pa-0">
        <!-- Fixed -->
        <template v-if="_fixedHeaders.length">
          <v-list-item
            v-for="(item) in _fixedHeaders"
            :key="item.value"
            class="non-hoverable"
          >
            <Tooltip bottom>
              <template #activator="{ on }">
                <span v-on="on">
                  <div
                    class="mt-0 v-input v-input--hide-details theme--light v-input--selection-controls v-input--checkbox"
                  >
                    <div class="v-input__control">
                      <div class="v-input__slot">
                        <div class="v-input--selection-controls__input">
                          <v-icon>info</v-icon>
                        </div>
                        <label class="v-label theme--light">{{ item.text }}</label>
                      </div>
                    </div>
                  </div>
                </span>
              </template>
              <span>This column will always be visible</span>
            </Tooltip>

            <!-- <v-checkbox
              hide-details disabled :value="true" :label="item.text"
            />-->

            <v-spacer />
            <!-- <div class="show-on-hover">
              <v-icon>drag_indicator</v-icon>
            </div>-->
          </v-list-item>
          <v-divider />
        </template>
      </v-list>

      <v-list
        v-sortable:sorting="_sortableHeaders"
        class="pa-0"
        @sort="onSort"
      >
        <!-- Sortable -->
        <v-list-item
          v-for="(item) in _sortableHeaders"
          :key="item.value"
          class="grab hoverable draggable"
        >
          <v-checkbox
            v-model="selection"
            class="mt-0"
            hide-details
            :value="item"
            :label="item.text"
          />
          <v-spacer />
          <div class="show-on-hover">
            <v-icon>drag_indicator</v-icon>
          </div>
        </v-list-item>
      </v-list>
    </div>
  </div>
</template>

<style>
/* TODO: fix all styling underneath*/

.hoverable > .show-on-hover {
  display: none;
}

.hoverable:hover > .show-on-hover {
  display: inherit;
}

.sortable-ghost,
.sortable-chosen {
  cursor: grabbing !important;
  background: var(--accent);
}

.grab {
  cursor: grab !important;
}
.grabbing,
.grab:active {
  cursor: grabbing !important;
}

.v-input--selection-controls__input + .v-label {
  cursor: grab !important;
}

.v-input--selection-controls__input + .v-label:active {
  cursor: grabbing !important;
}
</style>
