<script>

export default {
  props: {
    items: { type: Array, default () { return [] } },
    loading: { type: Boolean, default: false },
    imageKey: { type: String, default: 'url' }
  }
}
</script>

<template>
  <v-container grid-list-xl>
    <v-row>
      <v-col v-if="loading">
        <v-progress-circular
          indeterminate
          color="primary"
        />
      </v-col>

      <v-col v-if="!loading && !items.length">
        <v-alert
          border="left"
          color="indigo"
          dark
        >
          No items
        </v-alert>
      </v-col>
    </v-row>

    <!-- Grid -->
    <v-row v-if="!loading">
      <template v-for="(item,index) in items">
        <v-col
          :key="index"
          cols="3"
        >
          <slot
            name="card"
            :item="item"
          >
            <!-- Fallback content -->
            <!-- {{ item }} -->

            <v-card @click="$emit('click:item',item)">
              <v-img
                class="white--text align-end"
                height="200px"
                :src="item[imageKey] || item.image"
              >
                <v-card-title>{{ item.title || item.name }}</v-card-title>
              </v-img>

              <v-card-subtitle class="pb-0">
                {{ item.subTitle }}
              </v-card-subtitle>

              <v-card-text class="text--primary">
                {{ item.description }}
              </v-card-text>

              <!-- <v-card-actions>
                  <v-btn
                    color="orange"
                    text
                  >
                    Share
                  </v-btn>

                  <v-btn
                    color="orange"
                    text
                  >
                    Explore
                  </v-btn>
                </v-card-actions> -->
            </v-card>
          </slot>
        </v-col>
      </template>
    </v-row>
  </v-container>
</template>
