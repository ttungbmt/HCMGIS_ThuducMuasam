import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import mapsService from 'app/services/mapsService';
import { transformFlatTree, transformLayers } from '@redux-leaflet/store/utils.js';
import { layersActions, configActions } from '@redux-leaflet/store';
import { useQuery } from 'react-query';
import { useTreeContext } from '@ttungbmt/react-fancytree';

export const useFetchApp = () => {
	const dispatch  = useDispatch()
	const { isLoading, error, data, isFetching } = useQuery('builderData', mapsService.getBuilder);
	const { setAll, state } = useTreeContext();

	useEffect(() => {
		if (!isLoading && data) {
			let layers =  transformLayers(data.layers),
				flatLayers = transformFlatTree(layers)

			window.builder = data

			setAll(flatLayers.filter(l => l.data.control !== 'basemap'))
			dispatch(configActions.setConfig(data.config));
			dispatch(layersActions.setLayers(_.chain(flatLayers).map(({id, data}) => ({...data, id})).filter(l => l.type).value()));
			dispatch(configActions.setLoading(false))
		} else {
			dispatch(configActions.setLoading(true));
		}
	}, [isLoading]);
};