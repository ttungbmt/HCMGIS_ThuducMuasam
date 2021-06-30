import { useMapEvents } from 'react-leaflet';

function MapEvents() {
	const map = useMapEvents({
		layeradd: (e) => {

		}
	});
	return null;
}

export default MapEvents;