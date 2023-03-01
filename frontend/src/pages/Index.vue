<template>
  <v-container
    fluid
    tag="section">
    <v-row
      class="mb-6"
      no-gutters
    >
      <v-col
        cols="12"
        sm="6"
        md="3"
        lg="3"
        v-for="stt in statistics"
        :key="stt.statistic"
      >
        <v-card
          class="pa-4 ma-1 d-flex card-sts dash-card--numbers"
        >
          <v-avatar color="" :class="stt.bg" size="62">
            <v-icon>{{stt.icoimg}}</v-icon>
          </v-avatar>

          <div class="card-sts__info">
            <h4>{{stt.statistic}}</h4>
            <v-progress-linear
              color="teal"
              buffer-value="0"
              :value="stt.progressval"
              stream
            ></v-progress-linear>
          </div>

          <span class="font-weight-bold title">{{stt.number}}</span>
        </v-card>
      </v-col>
    </v-row>

    <v-row
      class="chart-graphs"
    >
      <v-col
      cols="12"
      md="4"
      >
        <template>
          <v-card
            class="mx-auto bt_card"
          >
            <v-list-item
              two-line
              class="bt_card__header">
              <v-list-item-content>
                <v-list-item-title class="title font-weight-light white--text text-capitalize">Factura Semanal</v-list-item-title>
                <v-list-item-subtitle class="white--text">Lunes</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-card-text>
              <v-row align="center">
                <v-col class="display-3" cols="6">
                  120
                </v-col>
                <v-col cols="6">
                  <v-progress-circular
                    :indeterminate="indeterminate"
                    :rotate="rotate"
                    :size="size"
                    :value="value"
                    :width="width"
                    color="light-blue"
                    class="headline"
                  >{{ value }} %</v-progress-circular>
                </v-col>
              </v-row>
            </v-card-text>

            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-cloud-download</v-icon>
              </v-list-item-icon>
              <v-list-item-subtitle>48%</v-list-item-subtitle>
            </v-list-item>

            <v-slider
              v-model="time"
              :max="6"
              :tick-labels="dayslabels"
              class="mx-4"
              ticks
            ></v-slider>

            <v-list class="transparent">
              <v-list-item
                v-for="item in forecast"
                :key="item.day"
              >
                <v-list-item-title>{{ item.day }}</v-list-item-title>

                <v-list-item-subtitle class="subtitle-1">
                  {{ item.numfacs }}
                </v-list-item-subtitle>
              </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-card-actions>
              <v-btn text>Full Report</v-btn>
            </v-card-actions>
          </v-card>
        </template>
      </v-col>
      <v-col
      cols="12 chart-graphs__line"
      md="8"
      >
        <ChartGraphChatLine/>

        <ChartGraphChatBar/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import ChartGraphChatLine from '@/components/statistics/GraphChatLine'
import ChartGraphChatBar from '@/components/statistics/GraphChatBars'

export default {
  name: 'PageIndex',
  components: {
    ChartGraphChatLine,
    ChartGraphChatBar
  },
  data () {
    return {
      statistics: [
        {
          icoimg: 'insert_chart',
          statistic: 'Total de Facturas',
          number: '345',
          progressval: 20,
          bg: 'bg-color bg-color--yellow'
        },
        {
          icoimg: 'list_alt',
          statistic: 'Timbres generados',
          number: '123',
          progressval: 50,
          bg: 'bg-color bg-color--green'
        },
        {
          icoimg: 'rate_review',
          statistic: 'Total de Folios Usados',
          number: '343',
          progressval: 90,
          bg: 'bg-color bg-color--orange'
        },
        {
          icoimg: 'insert_chart',
          statistic: 'Otro',
          number: '67',
          progressval: 20,
          bg: 'bg-color bg-color--blue'
        }
      ],
      dayslabels: ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'],
      time: 0,
      forecast: [
        { day: 'Domingo', numfacs: '322' },
        { day: 'Lunes', numfacs: '233' },
        { day: 'Martes', numfacs: '56' },
        { day: 'Miercoles', numfacs: '234' },
        { day: 'Jueves', numfacs: '546' },
        { day: 'Viernes', numfacs: '456' },
        { day: 'Sábado', numfacs: '211' }
      ],
      indeterminate: false,
      rotate: 0,
      size: 100,
      value: 60,
      width: 16
    }
  }
}
</script>
