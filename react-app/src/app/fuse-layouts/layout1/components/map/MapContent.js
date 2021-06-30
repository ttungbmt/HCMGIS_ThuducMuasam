import React, {memo, useEffect, useState} from 'react';
import {MapContainer, MapResize, Layer, WMSPopup, MapEvents, ScaleControl, useMap, LocateControl} from '@redux-leaflet';
import {connect, useSelector} from 'react-redux';
import {mapSelectors, layersSelectors, configSelectors} from '@redux-leaflet/store';
import FuseLoading from '@fuse/core/FuseLoading';
import MapHeader from "./MapHeader";
import {useSearchBarContext} from "app/components/SearchBar";
import {useUpdateEffect} from "react-use";
import {MapOptions} from "@redux-leaflet";
import MapNearby from "./MapNearby";

function MapSearch() {
    const map = useMap()
    const {selectedItem, selected} = useSearchBarContext()
    useUpdateEffect(() => {
        if (selectedItem) {
            map.flyTo([selectedItem.y, selectedItem.x])
        }

    }, [selected])

    return null
}

function MapContent({mapOptions, layers, loading}) {
    // not shown map on iOS: (1. useResize, 2. container must 100% height)
    // const {width, height, ref} = useResize();

    return (
        <div className="flex w-full h-full items-center">
            {loading ? <FuseLoading/> : (
                <MapContainer {...mapOptions}>
                    <MapOptions {...mapOptions}/>
                    <MapSearch/>
                    <MapHeader/>
                    <MapNearby />
                    {/*<MapResize width={width} height={height}/>*/}
                    <MapEvents/>
                    {layers.map(lp => <Layer key={lp.id} {...lp}/>)}
                    <ScaleControl/>
                    <LocateControl />
                    <WMSPopup/>
                </MapContainer>
            )}
        </div>
    );
}

const mapStateToProps = state => ({
    loading: configSelectors.selectLoading(state),
    mapOptions: mapSelectors.selectMapOptions(state),
    layers: layersSelectors.selectActiveLayers(state),
})

const mapDispatchToProps = {}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(memo(MapContent))
