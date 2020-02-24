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
        .filter(elem => elem.showInNavigation)
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
        { path: '/media', text: 'Files Upload' },
        { path: '/pages', text: 'Pages' },
        { path: '/blocks', text: 'Blocks' },
        { path: '/themes', text: 'Themes' },

        { heading: 'Data' },
        ...vm._contentItems,
        { heading: 'General' },
        // { path: '/plugins',text: 'Plugins' },
        { path: '/roles', text: 'Roles & Permissions' },
        // { path: '/content',text: 'Content Manager' },
        { path: '/typebuilder', text: 'Type Builder' },
        { path: '/settings', text: 'Settings' }
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
