<script>
import { TITLE } from '@/config'

export default {
  props: {},
  data: () => ({
    title: TITLE,
    dark: false,
    drawer: true
  }),
  computed: {
    _contentItems: vm => {
      // return []
      return vm.$store.state.types.items
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
  async mounted () {
    // const resp = await this.$serviceFactory('types').getAll()
    // this.items = resp
    // Set store
    // this.$store.commit("types/set", resp);
    try {
      this.$store.dispatch('plugins/getAll')
      this.$store.dispatch('types/getAll')
    } catch (err) {
      console.warn(err)
      this.$snackbar('Something went wrong')
    }
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
        <!-- {{ $store.state }} -->
        <!-- {{ _contentItems }} -->
        <!-- types{{ $store.state.types.items }} -->
        <slot />
      </v-container>
    </v-content>
  </v-app>
</template>
