import {forwardRef} from 'react';
import useSWR from 'swr';
import {isEmpty, get, isPlainObject} from 'lodash'
import {GeoJSON, Marker, Popup} from 'react-leaflet';
import MarkerCluster from 'react-leaflet-markercluster';
import axios from "axios";
import {toLatLng} from "../utils/geom";
import {ExtraMarkers} from 'leaflet'
import voronoi from '@turf/voronoi'
import PopContent from "./PopContent";

const fetcher = (...args) => axios(...args).then(res => get(res, 'data.data', []))

let icon = ExtraMarkers.icon({
    markerColor: 'orange',
    shape: 'circle',
    innerHTML: '<i class="far fa-disease" style="font-size: 17px"></i>'
});

const AjaxLayer = forwardRef(({layerData, url, type, ...props}, ref) => {
    const { error, data } = useSWR(url, fetcher)
    if(!data) return null

    const features = data.filter(f => f.geometry)

    if(type === 'marker-cluster'){
        const popup = layerData.popup
        let iconOpts = get(layerData, 'options.icon')

        iconOpts = isPlainObject(iconOpts) ? JSON.parse(iconOpts) : {
            markerColor: 'orange',
            shape: 'circle',
            innerHTML: '<i class="far fa-disease" style="font-size: 17px"></i>'
        }

        return (
            <MarkerCluster>
                {features.map((v, k) => {
                    const latlng = toLatLng(v.geometry)
                    const position = [latlng.lat, latlng.lng]
                    return (
                        <Marker key={k} position={position} icon={ExtraMarkers.icon(iconOpts)}>
                            {!isEmpty(popup) && (
                                <Popup>
                                    <PopContent data={v.properties} {...layerData.popup}/>
                                </Popup>
                            )}

                        </Marker>
                    )
                })}
            </MarkerCluster>
        )
    } else if(type === 'voronoi'){
        const geojsonData = voronoi({type: 'FeatureCollection', features}, {
            bbox: [106.69782257080078, 10.741743087768555,106.88191223144531,10.898930549621582]
        }).features.filter(v => v)

        return <GeoJSON data={geojsonData} style={{
            color: '#00abf4',
            weight: 2,
            opacity: 0.4
        }}/>
    } else if(type === 'geojson'){

        return <GeoJSON data={features} style={{
            color: '#00abf4',
            weight: 2,
            opacity: 0.4
        }}/>
    }


    return null
})



export default AjaxLayer