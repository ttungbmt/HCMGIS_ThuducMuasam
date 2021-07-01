import {getCoords} from  '@turf/invariant'
import {GeoJSON, marker} from 'leaflet'

export const toLatLng = (geometry) => GeoJSON.coordsToLatLng(getCoords(geometry))

export const toFeature = (latlng) => marker(latlng).toGeoJSON()

export const toCenter = (geometry) => {
	let latlng = toLatLng(geometry)
	return [latlng.lat, latlng.lng]
}

