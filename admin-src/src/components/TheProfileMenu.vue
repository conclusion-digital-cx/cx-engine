<script>

export default {
  data: () => ({
    // user: {}
  }),
  computed: {
    user () {
      return this.$store.getters.getUser
    }
  }
}
</script>

<template>
  <span>
    <v-menu
      v-if="user"
      offset-y
      on="on"
    >
      <v-btn
        v-slot:activator="{on}"
        icon
        large
      >
        <v-avatar
          size="32px"
          tile
        >
          <v-icon dark>account_circle</v-icon>
          <!-- <img src="https://vuetifyjs.com/static/doc-images/logo.svg" alt="Vuetify"> -->
        </v-avatar>
      </v-btn>

      <v-list>
        <v-list-tile>
          <v-list-tile-action>
            <v-icon>account_circle</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ user.username || user.name || '-' }}</v-list-tile-title>
            <v-list-tile-sub-title>{{ user.email }}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile :to="`/selectcompany`">
          <v-list-tile-action>
            <v-icon>swap_horiz</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>Switch company</v-list-tile-title>
        </v-list-tile>
        <v-list-tile :to="`/accounts/edit/${user._id}`">
          <v-list-tile-action>
            <v-icon>chat</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>My profile</v-list-tile-title>
        </v-list-tile>
        <v-list-tile to="/logout">
          <v-list-tile-action>
            <v-icon>exit_to_app</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>Sign out</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>

    <template v-else>
      <v-btn
        flat
        to="/login"
      >
        Sign in
      </v-btn>
    </template>

  </span>
</template>
