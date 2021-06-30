import L from 'leaflet'

export const getUrlLayer = (latlng, layer) => {
	let map = layer._map,
		point = map.latLngToContainerPoint(latlng, map.getZoom()),
		size = map.getSize(),
		wmsParams = layer.wmsParams,
		params = {
			request: 'GetFeatureInfo',
			service: 'WMS',
			srs: 'EPSG:4326',
			styles: wmsParams.styles,
			transparent: wmsParams.transparent,
			version: wmsParams.version,
			format: wmsParams.format,
			bbox: map.getBounds().toBBoxString(),
			height: size.y,
			width: size.x,
			layers: layer.wmsParams.layers,
			query_layers: layer.wmsParams.layers,
			info_format: 'application/json',
			feature_count: 10
		};

	params[params.version === '1.3.0' ? 'i' : 'x'] = Math.round(point.x);
	params[params.version === '1.3.0' ? 'j' : 'y'] = Math.round(point.y);

	if(wmsParams.cql_filter) params.cql_filter = wmsParams.cql_filter

	return layer._url + L.Util.getParamString(params, layer._url, true)
}