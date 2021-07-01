import {getCoords} from  '@turf/invariant'
import {geoJSON, GeoJSON, marker} from 'leaflet'

export const toLatLng = (geometry) => GeoJSON.coordsToLatLng(getCoords(geometry))

export const toFeature = (latlng) => marker(latlng).toGeoJSON()

export const toCenter = (geometry) => {
	let latlng = toLatLng(geometry)
	return [latlng.lat, latlng.lng]
}

export const strToLatLng = (str) => str.split(',').map(v => parseFloat(v))

export const toBounds = (features) => geoJSON(features).getBounds()