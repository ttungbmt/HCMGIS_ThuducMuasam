import React, {Fragment, memo, useEffect, useState} from 'react';
import {toCenter, useMap, Marker, Popup} from "@redux-leaflet";
import $emitter from 'app/utils/eventEmitter'
import {useForm} from "@form";
import {formName} from  'app/main/maps/views/nearBy/NearBy'
import {isEmpty, toNumber} from "lodash";
import L, {ExtraMarkers} from 'leaflet'

function MapNearby() {
    const map = useMap()
    const [selected, setSelected] = useState(null)
    const {data, values} = useForm(formName)

    useEffect(() => {

        $emitter.on('map/flyTo', (geometry) => {
            map.invalidateSize()
            map.flyTo(toCenter(geometry))
        })

        $emitter.on('marker/selected', (index) => setSelected(index))

        return () => {
            $emitter.off('map/flyTo')
            $emitter.off('marker/selected')
        }
    }, [])

    if(isEmpty(data.items) || !values.latlng) return null

    const position = values.latlng.split(',').map(v => toNumber(v.trim()))

    const userIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class='marker-number absolute' style="top: -3px; left: -3px; background: #2A93EE; width: 17px; height: 17px;border-radius: 50%;border: 3px solid white;"></div>`,
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
                    innerHTML: '<i class="far fa-disease" style="font-size: 17px"></i>'
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