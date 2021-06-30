import React, {forwardRef} from 'react'
import {TileLayer, WMSTileLayer} from 'react-leaflet';
import {includes} from 'lodash-es'

WMSTileLayer.defaultProps = {
    format: 'image/png',
    transparent: true,
}

const TileGroupLayer = forwardRef(({group}, ref) => {
    return group.map(({url, ...options}, k) => {
        const Component = (includes(url, 'ows') || includes(url, 'wms')) ? WMSTileLayer : TileLayer

        return <Component key={k} url={url} {...options}/>
    })
});

export default TileGroupLayer