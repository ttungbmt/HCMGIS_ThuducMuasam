import { toCenter } from '../../utils/geom'

export const setCenter = (geometry) => (dispatch, getState) => {
	if(geometry) {
		dispatch({ type: 'map/config/setCenter', payload: toCenter(geometry) })
	} else {
		dispatch({ type: 'map/config/notFoundGeometry'})
	}
}