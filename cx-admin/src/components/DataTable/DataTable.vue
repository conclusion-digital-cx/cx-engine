<script>
// Please read https://vuetifyjs.com/en/components/data-tables
// https://dev.to/sebastiangperez/vuetify-datatable-vuex-and-laravel-pagination-1d0o

import Row from './components/Row.vue'
import ProgressLinear from './components/ProgressLinear.vue'

export default {
  components: {
    Row,
    ProgressLinear
  },
  props: {
    headers: { type: Array, default: () => [] },
    selectable: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    paginationEnabled: { type: Boolean, default: true },
    value: {
      // Selection
      type: Array,
      default () {
        return []
      }
    },
    pagination: {
      type: Object,
      default () {
        return {}
      }
    },
    noItems: { type: Boolean, default: false }, // Use to indicate no items

    loadingText: { type: String, default: 'Loading...' },
    noItemsText: { type: String, default: 'No items found.' }
  },
  data () {
    return {
      // Check https://vuetifyjs.com/en/components/data-tables#api => props => pagination.sync
      // pagination: {
      // descending: true,
      // page: 0,
      // rowsPerPage: 20, // -1 for All",
      // sortBy: null,
      // totalItems: null
      // },
    }
  },
  computed: {
    // When we use paginataion, we use server-side pagination, sorting etc. In that case, the total-items should be defined from the pagination object.
    // When we don't use pagination, we use v-datatable's own sorting. In that case we shouldn't set total-items because setting it disables the v-datatable's sorting functionality.
    // Also, we need to supply rows-per-page-items with value [-1] which means we have only one option for the number of items per page which is indefinate.
    totalItems () {
      const { paginationEnabled, pagination } = this
      if (!paginationEnabled) return
      return pagination.totalItems
    },
    rowsPerPageItems () {
      const { paginationEnabled } = this
      if (!paginationEnabled) return [-1]
      return [10, 20, 50, 100]
    },
    hasItems () {
      return this.value.length > 0
    }
  },

  methods: {
    updatePagination () {
      // v-on="$listeners" proxies this
    }
  }
}
</script>

<template>
  <div>
    <!-- {{ value }} -->
    <!-- {{ selected }} -->
    <v-data-table
      :value="value"
      must-sort
      :headers="headers"
      :items="items"
      :loading="loading"
      :select-all="!!items.length && selectable"
      :hide-actions="!paginationEnabled"
      :total-items="totalItems"
      :rows-per-page-items="rowsPerPageItems"
      :disable-initial-sort="true"
      v-bind="$attrs"
      :pagination="pagination"
      @input="$emit('input',$event)"
      v-on="$listeners"
      @update:pagination="updatePagination"
    >
      <!-- Custom loader -->
      <template #progress>
        <slot name="progress">
          <ProgressLinear />
        </slot>
      </template>

      <!-- Custom header -->
      <!-- <template #headers="props">
        <DataTableHeader :headers='props'/>
      </template>-->

      <slot slot="actions-append" name="actions-append" />
      <slot slot="actions-prepend" name="actions-prepend" />
      <slot slot="footer" name="footer" />
      <!-- <slot slot="no-data" name="no-data" /> -->
      <slot slot="page-text" name="page-text" />

      <slot slot="no-data" name="no-data">
        <div class="text-xs-center ma-3">
          <b>{{ loading ? loadingText : noItemsText }}</b>
        </div>
      </slot>

      <template v-if="!loading" #items="row">
        <Row
          v-model="row.selected"
          :headers="headers"
          :row="row"
          :selectable="selectable"
          @click="$emit('click',row)"
        >
          <!-- This passes down all slot down to this child -->
          <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData">
            <slot :name="name" v-bind="slotData" />
          </template>
        </Row>
      </template>

      <slot slot="no-results" name="no-results">
        <v-layout>
          <v-spacer />Create your first item
          <v-spacer />
        </v-layout>
      </slot>
    </v-data-table>
  </div>
</template>
