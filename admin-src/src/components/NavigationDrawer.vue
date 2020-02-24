<script>
// https://github.com/vuetifyjs/vuetify/blob/master/packages/docs/src/examples/layouts/google-contacts.vue

export default {
  props: {
    value: { type: Boolean, default: true },
    showIcons: { type: Boolean, default: false },
    items: {
      type: Array,
      default () {
        return []
      }
    }
  },
  data: () => ({})
}
</script>

<template>
  <!-- <v-navigation-drawer
    :value="value"
    app
    clipped
    mobile-break-point="600"
    width="200"
    class="white darken-3"
    @input="$emit('input',$event)"
  > -->
  <v-navigation-drawer
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
      <template v-for="item in items">
        <v-row
          v-if="item.heading"
          :key="item.heading"
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
          :key="item.text"
          v-model="item.model"
          :prepend-icon="item.model ? item.icon : item['icon-alt']"
          append-icon=""
        >
          <template #activator>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="pa-0">
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
          <v-list-item
            v-for="(child, i) in item.children"
            :key="i"
            :to="child.path"
          >
            <template v-if="showIcons">
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
            </template>
            <v-list-item-content>
              <v-list-item-title>
                {{ child.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
        <v-list-item
          v-else
          :key="item.text"
          :to="item.path"
        >
          <template v-if="showIcons">
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
          </template>

          <v-list-item-content>
            <v-list-item-title>
              {{ item.text }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>
