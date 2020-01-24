<template>
  <div>
    <!-- Total {{components.length}} js components available -->

    <!-- Render blocks -->
    <!-- blocks: {{blocks}} -->

    <template v-if="preview">
      <div v-for="(item,index) in blocks" :key="index">
        <div v-html="item.render"></div>
      </div>
    </template>

    <template v-else>
      <!-- Blocks -->
      <div v-for="(item,index) in blocks" :key="index">
        <div class="blocks">
          <div class="blocks__heading">
            <div style="width:60%;float:left; ">
              <strong>{{item.name}}</strong>
              <!-- ({{index}}) -->
            </div>

            <div class="actions text-right">
              <v-btn icon text class="pull-right" @click.prevent="upBlock(item)">
                <v-icon>keyboard_arrow_up</v-icon>
              </v-btn>

              <v-btn icon text class="pull-right" @click.prevent="downBlock(item)">
                <v-icon>keyboard_arrow_down</v-icon>
              </v-btn>

              <v-btn icon text class="pull-right" @click.prevent="deleteBlock(item)">X</v-btn>
            </div>
          </div>

          <!-- JS Edit modus -->
          <!-- <small>data: {{item.data}}</small> -->
          <component
            :components="components"
            v-bind="item.props"
            :is="_componentsLookup[item.name]"
            @done="onEnter(item, $event)"
            @input="onInput(item, $event)"
            @render="onRender(item, $event)"
          />
        </div>
      </div>

      <!-- Add Block -->
      <v-menu :close-on-content-click="false" :nudge-width="200" offset-x>
        <template #activator="{ on }">
          <v-btn color="indigo" dark v-on="on" outlined>Add block</v-btn>
        </template>

        <v-card>
          <grid :items="components">
            <template #card="{item}">
              <v-card @click="addBlockJs(item)" class="show-actions-on-hover">
                <v-card-title>
                  <div class="text-truncate" style="width:60%">{{ item.name || 'No title' }}</div>
                  <v-spacer />
                </v-card-title>
              </v-card>
            </template>
          </grid>
        </v-card>
      </v-menu>
    </template>
  </div>
</template>
