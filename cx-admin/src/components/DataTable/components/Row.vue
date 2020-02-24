<script>
export default {
  props: {
    // This prop is for selection state, value is being used to accomodate the v-model shorthand
    value: { type: Boolean, default: false },

    headers: {
      type: Array,
      default() {
        return {};
      }
    },

    row: {
      type: Object,
      default() {
        return {};
      }
    },

    selectable: { type: Boolean, default: false }
  },

  computed: {
    // alias for this.value
    selected: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit("input", newValue);
      }
    },

    fields() {
      return this.row.item;
    }
  },

  methods: {
    toggle() {
      this.selected = !this.selected;
    },
    emitClick() {
      this.$emit("click");
    }
  }
};
</script>

<template>
  <tr style="cursor: pointer;" :active="selected" @click="emitClick">
    <td v-if="selectable">
      <v-checkbox v-model="selected" primary hide-details @click.stop="toggle" />
    </td>
    <slot name="items" :item="fields">
      <!-- Loop over the headers -->
      <template v-for="(header,index) in headers">
        <td :key="index">
          <template v-if="header.value">
            <!-- Custom slot for a cell -->
            <slot :name="`column-${header.value}`" :row="fields" :field="fields[header.value]">
              {{ fields[header.value] }}
            </slot>
          </template>
        </td>
      </template>
    </slot>
  </tr>
</template>
