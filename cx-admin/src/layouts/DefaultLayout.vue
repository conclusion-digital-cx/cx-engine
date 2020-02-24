<script>
import { TITLE } from '@/config'

export default {
  props: {},
  data: () => ({
    title: TITLE,
    dark: false,
    drawer: true,
    items: [
      // { path: '/', text: 'Dashboard' },
      // { path: '/themes', text: 'Themes' },
      // { path: '/pages', text: 'Pages' },
      // { path: '/users', text: 'Users' },
      // { path: '/blocks', text: 'Blocks' },
      // { path: '/media', text: 'Media' },
      // { path: '/collections', text: 'Collections' },
      // { divider: true },
      // { path: '/settings', text: 'Settings' }
    ]
  }),
  computed: {
    _contentItems: vm => {
      return vm.items
        // .filter(elem => !elem.core)
        .map(elem => ({
          path: `/content/${elem.name}`,
          // path: `/content/${elem.name}/${elem._id}`,
          icon: 'perm_identity',
          text: elem.name
        }))
    },

    _items: vm => {
      return [
        { path: '/', text: 'Dashboard' },
        // { heading: 'Basics' },
        // { path: '/themes', text: 'Themes' },
        // { path: '/pages', text: 'Pages' },
        // { path: '/users', text: 'Users' },
        // { path: '/blocks', text: 'Blocks' },
        // { path: '/media', text: 'Media' },
        // { path: '/collections', text: 'Collections' },

        { heading: 'Content' },
        ...vm._contentItems,
        { heading: 'General' },
        // { path: '/plugins', icon: 'settings', text: 'Plugins' },
        { path: '/roles', icon: 'settings', text: 'Roles & Permissions' },
        // { path: '/upload', icon: 'settings', text: 'Files Upload' },
        // { path: '/content', icon: 'settings', text: 'Content Manager' },
        { path: '/typebuilder', icon: 'settings', text: 'Type Builder' },
        { path: '/settings', icon: 'settings', text: 'Settings' }
      ]
    }
  },
  async created () {
    const resp = await this.$serviceFactory('types').getAll()
    this.items = resp
    // Set store
    // this.$store.commit("types/set", resp);
  }
}
</script>

<template>
  <v-app :dark="dark">
    <!-- <StoreMessages /> -->
    <!-- {{ items }} -->
    <Toolbar
      v-model="drawer"
      :title="title"
    />
    <NavigationDrawer
      v-model="drawer"
      :items="_items"
    />

    <v-content>
      <v-container>
        <!-- store{{ $store.state.types.items }} -->
        <slot />
      </v-container>
    </v-content>
  </v-app>
</template>
