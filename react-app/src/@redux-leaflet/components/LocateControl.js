import { createControlComponent } from '@react-leaflet/core'
import { Control } from 'leaflet'
import 'leaflet.locatecontrol/dist/L.Control.Locate.css'
import Locate from 'leaflet.locatecontrol'

export const LocateControl = createControlComponent(function createScaleControl(props) {
    return new Locate({
        position: 'bottomright',
        flyTo: true,
        icon: 'fal fa-location',
        ...props
    })
})