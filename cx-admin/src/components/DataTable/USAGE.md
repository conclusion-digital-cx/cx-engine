    <DataTable
      :add-link="`${baseUrl}/add`"
      :headers="headers"
      :service="service"
      @itemClick="itemClick"
    >

    <!-- Custom table column -->
      <template #column-Tags="{row, field}">
        <v-chip v-for="(item,index) in field" :key="index" @click.stop="$router.push(`/contacts?filter=${item}`)">
          {{ item }}
        </v-chip>
      </template>
    </DataTable>