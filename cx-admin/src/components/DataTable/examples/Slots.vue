<template>
  <DataTable
    :items="[]"
    item-key="sampleDate"
    :headers="[]"
    selectable
  >
    <template #headers>
      headers
    </template>
    <template #actions-prepend>
      actions-prepend
    </template>
    <template #actions-append>
      actions-append
    </template>
    <template slot="footer">
      footer
    </template>
    <template slot="no-data">
      no-data
    </template>

    <template #page-text="{ pageStart, pageStop, itemsLength }">
      page-text
    </template>

    <template #column-sampleDate="{row, field}">
      <v-chip
        v-for="(item,index) in field"
        :key="index"
      >
        {{ item }}
      </v-chip>
    </template>
  </DataTable>
</template>
