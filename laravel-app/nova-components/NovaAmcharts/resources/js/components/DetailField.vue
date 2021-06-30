<template>
    <loading-view :loading="loading">
        <div id="chart-div" style="height: 400px" v-if="!isEmpty(field.value)"></div>
        <div v-else>
            No data
        </div>
    </loading-view>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            loading: false,
            tooltip: this.field.tooltip || '{name}'
        }
    },
    methods: {
        isEmpty: _.isEmpty
    },
    mounted() {
        am4core.useTheme(am4themes_animated);

        let chart = am4core.create("chart-div", am4plugins_forceDirected.ForceDirectedTree);
        chart.zoomable = true;
        // chart.mouseWheelBehavior = 'none';
        // chart.legend = new am4charts.Legend();

        let networkSeries = chart.series.push(new am4plugins_forceDirected.ForceDirectedSeries())

        networkSeries.data = this.field.value;

        networkSeries.dataFields.linkWith = "linkWith";
        networkSeries.dataFields.name = "name";
        networkSeries.dataFields.id = "name";
        networkSeries.dataFields.value = "value";
        networkSeries.dataFields.children = "children";

        networkSeries.nodes.template.tooltipText = this.tooltip;
        networkSeries.nodes.template.fillOpacity = 1;

        networkSeries.nodes.template.label.text = "{name}"
        networkSeries.fontSize = 13;
        networkSeries.maxLevels = 3;
        networkSeries.maxRadius = am4core.percent(5);
        networkSeries.manyBodyStrength = -30;
        networkSeries.nodes.template.label.hideOversized = true;
        networkSeries.nodes.template.label.truncate = true;

        let hoverState = networkSeries.links.template.states.create('hover');
        hoverState.properties.strokeWidth = 2;
        hoverState.properties.strokeOpacity = 1;

        networkSeries.nodes.template.events.on('over', function (event) {
            event.target.dataItem.childLinks.each(function (link) {
                link.isHover = true;
            })
            if (event.target.dataItem.parentLink) {
                event.target.dataItem.parentLink.isHover = true;
            }

        })

        networkSeries.nodes.template.events.on('out', function (event) {
            event.target.dataItem.childLinks.each(function (link) {
                link.isHover = false;
            })
            if (event.target.dataItem.parentLink) {
                event.target.dataItem.parentLink.isHover = false;
            }
        })


    }
}
</script>
