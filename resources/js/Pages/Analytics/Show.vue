<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics - {{ $page.props.user.current_team.name }}
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div ref="chartdiv">

                </div>

            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import {XYChart} from "@amcharts/amcharts4/.internal/charts/types/XYChart";

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            chart: XYChart
        }
    },
    computed: {
        stats() {
            return this.$page.props.statistics.map(link => {
                for (let i = 0; i < link.series.length; i++) {
                    link.series[i].timestamp = new Date(JSON.parse(link.series[i].timestamp));
                }
                return link.series;
            });
        }
    },
    methods: {
        /**
         * Create a moving sum series.
         * @param series Original series.
         * @param window Window in days.
         * @returns {*[]}
         */
        movingSum(series, window = 2) {
            let newSeries = [];
            let firstTimestamp = series[0].timestamp.getTime();
            const delta = window * 144000; // day -> ms conversion

            let sum = 0;
            for (let i = 0; i < series.length; i++) {
                if (series[i].timestamp.getTime() < firstTimestamp + delta) {
                    sum += 1;
                } else {
                    newSeries.push({
                        timestamp: new Date(firstTimestamp),
                        value: sum
                    });
                    firstTimestamp = series[i].timestamp.getTime();
                    sum = 1;
                }
            }
            if (sum) {
                newSeries.push({
                    timestamp: new Date(firstTimestamp),
                    value: sum
                });
            }

            return newSeries;
        }
    },
    mounted() {

        const data = this.movingSum(this.stats[0]);
        console.log(data);

        let chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);

        chart.data = data;

        let categoryAxis = chart.xAxes.push(new am4charts.DateAxis());
        categoryAxis.dataFields.dateX = "timestamp";
        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        categoryAxis.dataFields.valueY = "value";

        let series = chart.series.push(new am4charts.LineSeries());

        series.dataFields.dateX = "timestamp";
        series.dataFields.valueY = "value";
    }
}
</script>

<style scoped>

</style>
