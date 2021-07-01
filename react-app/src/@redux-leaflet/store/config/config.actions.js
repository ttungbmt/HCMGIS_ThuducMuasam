import { toCenter, toBounds } from '../../utils/geom'

export const setCenter = (geometry) => (dispatch, getState) => {
	if(geometry) {
		dispatch({ type: 'map/config/setCenter', payload: toCenter(geometry) })
	} else {
		dispatch({ type: 'map/config/notFoundGeometry'})
	}
}

export const setBounds = (features) => (dispatch) => dispatch({ type: 'map/config/setBounds', payload: toBounds(features) })