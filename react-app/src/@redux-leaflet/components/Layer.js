import {TileLayer} from 'react-leaflet'
import {WMSTileLayer} from './WMSTileLayer'
import PropTypes from 'prop-types';
import {useEffect, useRef} from 'react';
import {isNull, cloneDeep} from 'lodash-es'
import TileGroupLayer from './TileGroupLayer'
import GeoJSON from './GeoJSON'
import AjaxLayer from './AjaxLayer'
import MarkerCluster from 'react-leaflet-markercluster';

WMSTileLayer.defaultProps = {
    format: 'image/png',
    transparent: true,
    zIndex: 10
}

const components = {
    tile: TileLayer,
    wms: WMSTileLayer,
    'tile-group': TileGroupLayer,
    geojson: GeoJSON,
    'marker-cluster': MarkerCluster,
    'ajax': AjaxLayer,
}

function useId(id) {
    const ref = useRef()
    useEffect(() => {
        if (id && ref.current) {
            ref.current._id = id
        }
    }, [])
    return ref
}

function Layer({id, type, ...props}) {
    const ref = useId(id)
    const Component = components[type]
    let options = cloneDeep(props.options)

    if(isNull(options.url)) options.url = ''

    switch (type) {
        case 'tile':
            break;
        case 'wms':
            break;
    }

    return (
        <Component ref={ref} {...options} layerData={props}>
        </Component>
    )
}

Layer.propTypes = {
    type: PropTypes.string.isRequired
};


export default Layer