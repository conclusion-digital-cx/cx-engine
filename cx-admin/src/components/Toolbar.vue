<script>
import TheProfileMenu from "./TheProfileMenu";

export default {
  components: {
    TheProfileMenu
  },
  props: {
    value: Boolean,
    title: { type: String, default: "" }
  },
  methods: {
    toggle() {
      this.$emit("input", !this.value);
    }
  }
};
</script>

<template>
  <v-app-bar clipped-left dense app color="deep-purple accent-4">
    <v-app-bar-nav-icon @click.stop="toggle()">
      <v-icon>menu</v-icon>
    </v-app-bar-nav-icon>

    <v-toolbar-title>{{ title }}</v-toolbar-title>

    <!-- <v-text-field @keyup.enter.native="submitSearch" v-model='search' text solo-inverted prepend-icon="search" label="Search" class="hidden-sm-and-down"/> -->

    <v-spacer />

    <v-btn target="_blank" href="/" text>
      <v-icon>mdi-export-variant</v-icon>To site
    </v-btn>

    <TheProfileMenu />
  </v-app-bar>
</template>
