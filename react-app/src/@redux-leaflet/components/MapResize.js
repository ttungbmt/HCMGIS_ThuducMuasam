import { useMap } from 'react-leaflet';
import { useEffect } from 'react';

export default function MapResize({width, height}) {
	const map = useMap()
	useEffect(() => {
		if(width && height) map.invalidateSize()
	}, [width, height])
	return null
}