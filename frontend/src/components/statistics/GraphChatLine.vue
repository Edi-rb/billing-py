<template>
  <v-card
    class="bt_card">
  <v-list-item
    class="bt_card__header">
    <v-list-item-avatar color="cyan accent-3"><v-icon>attach_money</v-icon></v-list-item-avatar>
    <v-list-item-content>
      <v-list-item-title class="title white--text font-weight-light">Facturas Correspondientes a Mayo</v-list-item-title>
      <v-list-item-subtitle class="white--text">Creadas de 1 mayo - 31 Mayo</v-list-item-subtitle>
    </v-list-item-content>
  </v-list-item>
    <chartist
        :data="data"
        :options="options"
        type="Line"
        style="max-height: 300px;"
    >
    </chartist>
  </v-card>
</template>

<script>
export default {
  data () {
    return {
      data: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        series: [
          [12, 9, 7, 8, 5],
          [2, 1, 3.5, 7, 3],
          [1, 3, 4, 5, 6]
        ]
      },
      options: {
        fullWidth: true,
        horizontalBars: true,
        chartPadding: {
          right: 40
        }
      }
    }
  }
}
</script>
