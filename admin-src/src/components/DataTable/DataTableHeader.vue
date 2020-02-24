<script>
export default { 
    methods: {
        toggleAll() {
            this.selected = this.selected.length ? [] : this.items.slice();
            this.$emit('input',this.selected)
        },

        changeSort(column) {
            if (this.pagination.sortBy === column) {
                this.pagination.descending = !this.pagination.descending;
            } else {
                this.pagination.sortBy = column;
                this.pagination.descending = false;
            }
        }
    }
}
</script>

<template>
  <tr>
    <th v-if="selectable">
      <v-checkbox
        :input-value="props.all"
        :indeterminate="props.indeterminate"
        primary
        hide-details
        @click.stop="toggleAll"
      />
    </th>
    <th
      v-for="header in props.headers"
      :key="header.text"
      :class=" [
        'column sortable',
        (header.sortable !== false) ? 'sortable' : '',
        pagination.descending ? 'desc' : 'asc',
        header.value === pagination.sortBy ? 'active' : ''
      ]"
      @click="changeSort(header.value)"
    >
      <v-icon v-if="header.sortable !== false" small>
        arrow_upward
      </v-icon>
      {{ header.text }}
    </th>
  </tr>
</template>

<style>
.theme--light.v-datatable thead th.column.sortable .v-icon {
    margin-left: -16px;
}
</style>