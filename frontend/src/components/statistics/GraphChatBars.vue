<template>
  <v-card
    class="mt-2 bt_card">
    <v-list-item
        class="bt_card__header">
        <v-list-item-avatar color="cyan accent-3"><v-icon>attach_money</v-icon></v-list-item-avatar>
        <v-list-item-content>
        <v-list-item-title class="title white--text font-weight-light">Ganancias obtenidas de Mayo</v-list-item-title>
        <v-list-item-subtitle class="white--text">De 1 mayo - 31 Mayo</v-list-item-subtitle>
        </v-list-item-content>
    </v-list-item>
    <chartist
        :data="data"
        :options="options"
        type="Bar"
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
        labels: ['Lunes', 'Martes', 'MIercoles', 'Jueves', 'Viernes'],
        series: [
          [5, 4, 3, 7, 5, 10, 3],
          [3, 2, 9, 5, 4, 6, 4]
        ]
      },
      options: {
        seriesBarDistance: 10,
        reverseData: true,
        horizontalBars: true,
        axisY: {
          offset: 70
        }
      }
    }
  }
}
</script>
