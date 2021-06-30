import {memo, useEffect, useState} from 'react';
import $event from 'app/utils/eventEmitter'
import {Marker, Popup} from "react-leaflet";
import {ExtraMarkers} from "leaflet";
import {isEmpty, orderBy, get} from "lodash";
import {toLatLng, PopContent, GeoJSON} from "@redux-leaflet";

import MarkerCluster from "react-leaflet-markercluster";
import moment from "app/utils/moment";
import {useForm} from "@form";
import {formName} from "app/main/maps/views/dientienCovid/DientienCovid";
import useSWR from "swr";
import axios from "axios";
import {getStatsDataByHc} from "app/main/maps/views/dientienCovid/Detail";

const renderMarkers = (items, range) => orderBy(items.filter(v => {
    let unix = moment(v.date, 'DD/MM/YYYY').unix()
    return v.geometry && (unix >= range[0] && unix<= range[1])
}), ['ma_kv'], ['asc']).map((v, k) => {
    const latlng = toLatLng(v.geometry)
    const position = [latlng.lat, latlng.lng]

    const icon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class='marker-number absolute' style="color: white; background: red; padding: 0 4px;border-radius: 5px 5px;border: 2px solid white;">${v.ma_kv}</div>`,
    });

    return (
        <Marker key={k} position={position} icon={icon}>
        </Marker>
    )
})

const fetcher = (...args) => axios.get(...args).then(res => res.data)

const getColor = (items, value) => get(items.filter(v => value >= v.value[0] && value < v.value[1]), '0.fillColor', 'white')

function MapRangeSlider(){
    const resp = useSWR('/api/phuongs', fetcher, {revalidateOnFocus: false, revalidateOnReconnect: false})
    const {data: formData, values: formValues} = useForm(formName)
    const {is_cluster = false, is_shown, range, view} = formValues
    const {data} = formData

    if(isEmpty(data) || !is_shown) return null

    if(view === 'diadiem'){
        if(is_cluster) return (
            <MarkerCluster>
                {renderMarkers(data, range)}
            </MarkerCluster>
        )

        return renderMarkers(data, range)
    }

    if(view === 'khuvuc' && (resp.data || !resp.isValidating)){
        const {data: features, meta} = resp.data
        const colors = meta.colors
        const statsData = getStatsDataByHc(data, range)


        const style = (feature) => {
            let count = get(statsData.filter(v => v.code === feature.properties.maphuong), '0.count', 0)
            return {
                fillColor: getColor(colors, count),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            }
        }

        return <GeoJSON data={features} style={style}/>
    }

    return null

}

export default memo(MapRangeSlider)