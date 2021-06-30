import {Popup, useMapEvents} from 'react-leaflet';
import {layersSelectors, popupsActions, popupsSelectors} from '../store';
import {connect, useDispatch} from 'react-redux';
import {map as _map, includes, find, orderBy, get, findIndex, take, cloneDeep, isEmpty, reverse, head} from 'lodash-es'
import {memo} from 'react';
import {TileLayer} from 'leaflet';
import {getUrlLayer} from '../utils';
import {nanoid} from 'nanoid';
import axios from 'axios'

import {createAsyncThunk} from '@reduxjs/toolkit'
import PopContent from './PopContent';

const formatFeatures = (data) => reverse(get(data, 'features', [])).filter(v => v.geometry && v.properties)

const fetchPopups = createAsyncThunk(
    'popups/fetchFeatureInfos',
    async ({items, startIndex = 0}, {dispatch, getState}) => {
        try {
            dispatch(popupsActions.setAll(items))

            dispatch(popupsActions.setLoading(true))
            let index = 0
            for (let i of items) {
                if (index >= startIndex) {
                    try {
                        const id = nanoid()
                        const data = await axios.get(i.url).then(({data}) => data)
                        const features = formatFeatures(data)

                        if (!isEmpty(features)) {
                            const lastFeature = head(features)

                            dispatch(popupsActions.setCurrentId(i.id))
                            dispatch(popupsActions.setInfo({id: i.id, changes: {locs: features, data: {properties: lastFeature.properties, geometry: lastFeature.geometry,}}}))

                            // Thêm cùng vị trí

                            dispatch(popupsActions.setLoading(false))
                            break
                        } else {
                            dispatch(popupsActions.remove(i.id))
                        }

                    } catch (e) {
                        console.error(e)
                    }
                }
                index++
            }

            dispatch(popupsActions.setLoading(false))

            const state = getState()
            const maxRequest = popupsSelectors.selectMaxRequest(state)
            const currentId = popupsSelectors.selectCurrentId(state)
            const popups = popupsSelectors.selectAll(state)
            const nextPopups = cloneDeep(take(popups.filter((p, k) => k > findIndex(popups, {id: currentId})), maxRequest))
            for (let i of nextPopups) {
                const data = await axios.get(i.url).then(({data}) => data)
                const features = formatFeatures(data)
                if (!isEmpty(features)) {
                    const {properties, geometry} = head(features)
                    dispatch(popupsActions.setInfo({id: i.id, changes: {locs: features, data: {properties, geometry}}}))
                    break
                } else {
                    dispatch(popupsActions.remove(i.id))
                }
            }


        } catch (e) {
            console.log(e)
        } finally {

        }
    }
)

const fetchNextPopup = createAsyncThunk(
    'popups/fetchNextFeatureInfo',
    async (item, {dispatch, getState}) => {
        dispatch(popupsActions.setCurrentId(item.id))
        const nextPopup = popupsSelectors.selectNextCurrent(getState())
        if (nextPopup && !nextPopup.feature) {
            try {
                const {id, url} = nextPopup
                const data = await axios.get(url).then(({data}) => data)
                const features = formatFeatures(data)
                if (!isEmpty(features)) {
                    const {properties, geometry} = head(features)
                    dispatch(popupsActions.setInfo({id: id, changes: {locs: features, data: {properties, geometry}}}))
                } else {
                    dispatch(popupsActions.remove(id))
                }
            } catch (e) {
                console.log(e)
            }
        }
    }
)


function WMSPopup({layers, popup, prevPopup, nextPopup}) {
    const dispatch = useDispatch()

    const map = useMapEvents({
        click({latlng}) {
            let popups = []
            map.eachLayer(layer => {
                let rawLayer = find(layers, {id: layer._id})

                if (includes(_map(layers, 'id'), layer._id) && layer instanceof TileLayer.WMS && !isEmpty(rawLayer.popup)) {
                    popups.push({
                        id: nanoid(),
                        layer_id: layer._id,
                        url: getUrlLayer(latlng, layer),
                        latlng,
                        zIndex: get(rawLayer, 'options.zIndex')
                    })
                }
            });
            popups = orderBy(popups, 'zIndex', 'desc')

            dispatch(fetchPopups({startIndex: 0, items: popups}))
        },
        popupclose(e) {
            // dispatch(popupsActions.removeAll())
        }
    });

    const onPrevious = (item) => dispatch(popupsActions.setCurrentId(item.id))

    const onNext = (item) => dispatch(fetchNextPopup(item))

    if (popup && popup.data && popup.template) {
        return (
            <Popup position={popup.latlng}>
                <PopContent data={popup.data.properties} locs={popup.locs || []} {...popup.template}/>
                <div className="absolute" style={{top: 4, right: 20}}>
                    <div className="flex">
                        {prevPopup && <button className="px-4 btn-previous" onClick={e => onPrevious(prevPopup)}><i className="fas fa-chevron-left"/></button>}
                        {nextPopup && <button className="px-4 btn-next" onClick={e => onNext(nextPopup)}><i className="fas fa-chevron-right"/></button>}
                    </div>
                </div>
            </Popup>
        );
    }

    return null
}


const mapStateToProps = state => ({
    layers: layersSelectors.selectActiveLayers(state),
    popup: popupsSelectors.selectCurrent(state),
    prevPopup: popupsSelectors.selectPrevCurrent(state),
    nextPopup: popupsSelectors.selectNextCurrent(state),
})

const mapDispatchToProps = {}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(memo(WMSPopup))