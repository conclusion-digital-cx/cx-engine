<template>
  <v-container>
    <h1>Roles</h1>
    <v-card>
      <!-- <div v-for="item in items">
        {{ item }}
      </div>-->
      <v-data-table
        :headers="headers"
        :items="items"
      >
        <!-- Custom table column -->
        <template #item:company="{item}">
          <small>{{ item._id }}</small>
          {{ item.name }} / {{ item.email }}
        </template>
        <template #item:user="{item}">
          <div>{{ item.name }} / {{ item.email }}</div>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
export default {
  components: {},
  data () {
    return {
      // roles: [
    //   { text: 'public', description: 'Default role given to unauthenticated user.' },
    //   { text: 'authenticated', description: 'Default role given to authenticated user.' }
    // ],
      items: [],
      headers: [
        {
          text: 'Role',
          value: 'role'
        },
        { text: 'User#', value: 'user' },
        { text: 'Email', value: 'user.email' },
        { text: 'Company', value: 'company' }
      ]
    }
  },
  async created () {
    const { data } = await this.$service.getAll(`roles`, {
      // params: { populate: ['company', 'user'] }
    })
    this.items = data
  }
}
</script>
