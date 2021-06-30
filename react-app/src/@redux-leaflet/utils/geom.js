import {getCoords} from  '@turf/invariant'
import {GeoJSON} from 'leaflet'

export const toLatLng = (geometry) => GeoJSON.coordsToLatLng(getCoords(geometry))

export const toCenter = (geometry) => {
	let latlng = toLatLng(geometry)
	return [latlng.lat, latlng.lng]
}

