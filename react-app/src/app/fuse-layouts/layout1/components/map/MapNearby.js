import React, {Fragment, memo, useEffect, useState} from 'react';
import {toCenter, useMap, Marker, Popup} from "@redux-leaflet";
import $emitter from 'app/utils/eventEmitter'
import {useForm} from "@form";
import {formName} from  'app/main/maps/views/nearBy/NearBy'
import {isEmpty, toNumber} from "lodash";
import L, {ExtraMarkers, geoJSON} from 'leaflet'

function MapNearby() {
    const map = useMap()
    const [selected, setSelected] = useState(0)
    const {data, values} = useForm(formName)

    useEffect(() => {

        $emitter.on('map/flyTo', (geometry) => {
            map.invalidateSize()
            map.flyTo(toCenter(geometry))
        })

        $emitter.on('marker/selected', (index) => setSelected(index))
        $emitter.on('map/setBounds', (features) => map.fitBounds(geoJSON(features).getBounds()))

        return () => {
            $emitter.off('map/flyTo')
            $emitter.off('marker/selected')
            $emitter.off('marker/setBounds')
        }
    }, [])

    if(isEmpty(data.items) || !values.latlng) return null

    const position = values.latlng.split(',').map(v => toNumber(v.trim()))

    const userIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class='marker-number absolute' style="top: -4px; left: -10px; background: #2A93EE; width: 30px; height: 30px;border-radius: 50%;border: 3px solid white;"><i class="far fa-user text-white" style="padding: 5px 7px;"></i></div>`,
    })

    const eventHandlers = {
        dragend: ({target}) => {
            const latlng = target.getLatLng()
            $emitter.emit('marker/dragend', [latlng.lat, latlng.lng])
        }
    }

    return (
        <Fragment>
            {data.items.map((v, k) => {
                const icon = ExtraMarkers.icon({
                    markerColor: k === selected ? 'orange' : 'cyan',
                    shape: 'circle',
                    innerHTML: `<div class="text-white font-bold" style="padding-top: 8px; font-size: 14px">${k+1}</div>`
                });

                return <Marker key={k} position={toCenter(v.geometry)} icon={icon}/>
            })}

            <Marker position={position} icon={userIcon} draggable={true} eventHandlers={eventHandlers}>
                <Popup><div className="pr-10"><span className="font-semibold">Tọa độ:</span> {position.join(', ')}</div></Popup>
            </Marker>

        </Fragment>
    )
}

export default memo(MapNearby)