import {createTileLayerComponent, updateGridLayer, withPane,} from '@react-leaflet/core'
import {TileLayer} from 'leaflet'
import NonTiledLayer from  'leaflet.nontiledlayer'
import {isUndefined} from 'lodash'

export const WMSTileLayer = createTileLayerComponent(
    function createWMSTileLayer({params = {}, url, ...options}, context) {
        let WMSLayer = TileLayer.WMS

        if(!isUndefined(options.tiled) && !options.tiled){
            WMSLayer = NonTiledLayer.WMS
            if(!options.pane) options.pane = 'tilePane'
        }


        return {
            instance: new WMSLayer(url, {
                ...params,
                ...withPane(options, context),
            }),
            context,
        }
    },
    function updateWMSTileLayer(layer, props, prevProps) {
        updateGridLayer(layer, props, prevProps)

        if (props.params != null && props.params !== prevProps.params) {
            layer.setParams(props.params)
        }
    },
)