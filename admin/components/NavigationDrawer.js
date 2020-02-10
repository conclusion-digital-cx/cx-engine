// https://github.com/vuetifyjs/vuetify/blob/master/packages/docs/src/examples/layouts/google-contacts.vue

export default {
  props: {
    value: { type: Boolean, default: true },
    items: {
      type: Array,
      default () {
        return [
            { url: '/dashboard', icon: 'home', text: 'Dashboard' },
            // Bookings
            // { icon: 'insert_invitation', heading: 'Bookings' },
            // { url: '/booknow', icon: 'add', text: 'Add booking' },
            { url: '/clients', icon: 'perm_identity', text: 'Contacts' },
            // { url: '/calendar', icon: 'insert_invitation', text: 'Calendar' },
            { url: '/calendar',
              icon: 'insert_invitation',
              text: 'Calendar' },
            // { url: '/gantt', icon: 'insert_invitation', text: 'Calendar' },
            { url: '/orders', icon: 'event_note', text: 'Orders' },
            {
              url: '/bookings',
              icon: 'event_note',
              text: 'Bookings'
            }
        ]
      }
    }
  },
  data: () => ({}),
  template: `<v-navigation-drawer
  :value="value"
  app
  clipped
  dark
  color="#223440"
  mobile-break-point="600"
  width="200"
  @input="$emit('input',$event)"
>
  <v-list dense>
    <template v-for="(item,index) in items">
      <v-divider
        v-if="item.divider"
        :key="index"
      />

      <!-- Heading -->
      <v-row
        v-else-if="item.heading"
        :key="index"
        align="center"
      >
        <v-col cols="6">
          <v-subheader v-if="item.heading">
            {{ item.heading }}
          </v-subheader>
        </v-col>
      </v-row>

      <!-- Dropdown -->
      <v-list-group
        v-else-if="item.children"
        :key="index"
        v-model="item.model"
        :prepend-icon="item.model ? item.icon : item['icon-alt']"
        append-icon=""
      >
        <template #activator>
          <v-list-item-content>
            <v-list-item-title class="pa-0">
              {{ item.text }}
            </v-list-item-title>
          </v-list-item-content>
        </template>

        <template
          v-for="(child, i) in item.children"
        >
          <v-list-item
            v-if="!child.hide"
            :key="i"
            :to="child.to"
          >
  
            <v-list-item-content>
              <v-list-item-title>
                {{ child.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list-group>

      <!-- Single item -->
      <v-list-item
        v-else-if="!item.hide"
        :key="item.index"
        :to="item.to"
      >

        <v-list-item-content>
          <v-list-item-title>
            {{ item.text }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-list>
</v-navigation-drawer>`
}

