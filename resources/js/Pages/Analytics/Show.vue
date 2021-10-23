<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics - {{ $page.props.user.current_team.name }}
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <jet-dropdown align="left" width="60">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                <span v-if="selectedLink">{{selectedLink.link_name}}</span>
                                <span v-else>Select a link</span>

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <div class="w-60">
                            <div @click="setLink(link.link_id)"
                                 class="block px-4 py-2 text-xs text-gray-400 hover:text-gray-500 cursor-pointer" v-for="link in stats">
                                {{link.link_name}}
                            </div>
                        </div>
                    </template>
                </jet-dropdown>


                <div ref="chart">

                </div>

            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink';
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";

export default {
    components: {
        AppLayout,
        JetDropdown,
        JetDropdownLink
    },
    data() {
      return {
          selectedLink: null,
          chart: null
      }
    },
    computed: {
        stats() {
            return this.$page.props.statistics.map(link => {
                for (let i = 0; i < link.series.length; i++) {
                    link.series[i].timestamp = new Date(JSON.parse(link.series[i].timestamp));
                }
                return link;
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
                    console.log(series[i].timestamp)
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
        },
        toMillisecond(series) {
            return series.map(x => {
                console.log(x.timestamp)
                x.timestamp = x.timestamp.getTime()
                return x;
            })
        },
        setLink(link_id) {
            this.selectedLink = this.stats.find(link => link.link_id === link_id);
            console.log(this.selectedLink)
            this.chart.data = this.movingSum(this.selectedLink.series)
        }
    },
    mounted() {
        this.chart = am4core.create(this.$refs.chart, am4charts.XYChart);

        this.setLink(this.$page.props.statistics[0].link_id)

        let dateAxis = this.chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.title.text = "Time";

        let valueAxis = this.chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Views";
        let series = this.chart.series.push(new am4charts.LineSeries());
        series.name = "Views";
        series.stroke = am4core.color("#CDA2AB");
        series.strokeWidth = 3;
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "timestamp";

        console.log(this.selectedLink)
        console.log(this.chart.data)
    }
}
</script>

<style scoped>

</style>
