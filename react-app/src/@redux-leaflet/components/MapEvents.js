import { useMapEvents } from 'react-leaflet';
import $emitter from 'app/utils/eventEmitter'

function MapEvents() {
	const map = useMapEvents({
		contextmenu: (e) => {
			$emitter.emit('map/contextmenu', e)
		}
	});
	return null;
}

export default MapEvents;