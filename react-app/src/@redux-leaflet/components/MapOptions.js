import {useUpdateEffect, useMount} from 'react-use'
import {useMap} from "react-leaflet";
import {useRef, useEffect} from "react";
import {latLngBounds} from 'leaflet'

const normalizeCenter = (pos) => {
    return Array.isArray(pos)
        ? [pos[0], pos[1]]
        : [pos.lat, pos.lon ? pos.lon : pos.lng]
}

const getZoomPanOptions = (props) => {
    const { animate, duration, easeLinearity, noMoveStart } = props
    return {
        animate,
        duration,
        easeLinearity,
        noMoveStart,
    }
}

const shouldUpdateCenter = (next, prev) => {
    if (!prev) return true
    next = normalizeCenter(next)
    prev = normalizeCenter(prev)
    return next[0] !== prev[0] || next[1] !== prev[1]
}

const shouldUpdateBounds = (next, prev) => {
    return prev ? !latLngBounds(next).equals(latLngBounds(prev)) : true
}

const getFitBoundsOptions = (props) => {
    const zoomPanOptions = getZoomPanOptions(props)
    return {
        ...zoomPanOptions,
        ...props.boundsOptions,
    }
}

function MapOptions(props) {
    const ref = useRef()
    const map = useMap()

    const toProps = props

    const {
        bounds,
        boundsOptions,
        boxZoom,
        center,
        className,
        doubleClickZoom,
        dragging,
        keyboard,
        maxBounds,
        scrollWheelZoom,
        tap,
        touchZoom,
        useFlyTo,
        viewport,
        zoom,
    } = toProps


    useUpdateEffect(() => {
        const fromProps = ref.current

        if (viewport && viewport !== fromProps.viewport) {
            const c = viewport.center ? viewport.center : center
            const z = viewport.zoom == null ? zoom : viewport.zoom
            if (useFlyTo === true) {
                map.flyTo(c, z, getZoomPanOptions(toProps))
            } else {
                map.setView(c, z, getZoomPanOptions(toProps))
            }
        } else if (center && shouldUpdateCenter(center, fromProps.center)) {
            if (useFlyTo === true) {
                map.flyTo(center, zoom, getZoomPanOptions(toProps))
            } else {
                map.setView(
                    center,
                    zoom,
                    getZoomPanOptions(toProps),
                )
            }
        } else if (typeof zoom === 'number' && zoom !== fromProps.zoom) {
            if (fromProps.zoom == null) {
                map.setView(
                    center,
                    zoom,
                    getZoomPanOptions(toProps),
                )
            } else {
                map.setZoom(zoom, getZoomPanOptions(toProps))
            }
        }

        if (maxBounds && shouldUpdateBounds(maxBounds, fromProps.maxBounds)) {
            map.setMaxBounds(maxBounds)
        }

        if (
            bounds &&
            (shouldUpdateBounds(bounds, fromProps.bounds) ||
                boundsOptions !== fromProps.boundsOptions)
        ) {
            if (useFlyTo === true) {
                map.flyToBounds(
                    bounds,
                    getFitBoundsOptions(toProps),
                )
            } else {
                map.fitBounds(bounds, getFitBoundsOptions(toProps))
            }
        }

        if (boxZoom !== fromProps.boxZoom) {
            if (boxZoom === true) {
                map.boxZoom.enable()
            } else {
                map.boxZoom.disable()
            }
        }

        if (doubleClickZoom !== fromProps.doubleClickZoom) {
            if (doubleClickZoom === true || typeof doubleClickZoom === 'string') {
                map.options.doubleClickZoom = doubleClickZoom
                map.doubleClickZoom.enable()
            } else {
                map.doubleClickZoom.disable()
            }
        }

        if (dragging !== fromProps.dragging) {
            if (dragging === true) {
                map.dragging.enable()
            } else {
                map.dragging.disable()
            }
        }

        if (keyboard !== fromProps.keyboard) {
            if (keyboard === true) {
                map.keyboard.enable()
            } else {
                map.keyboard.disable()
            }
        }

        if (scrollWheelZoom !== fromProps.scrollWheelZoom) {
            if (scrollWheelZoom === true || typeof scrollWheelZoom === 'string') {
                map.options.scrollWheelZoom = scrollWheelZoom
                map.scrollWheelZoom.enable()
            } else {
                map.scrollWheelZoom.disable()
            }
        }

        if (tap !== fromProps.tap) {
            if (tap === true) {
                map.tap.enable()
            } else {
                map.tap.disable()
            }
        }

        if (touchZoom !== fromProps.touchZoom) {
            if (touchZoom === true || typeof touchZoom === 'string') {
                map.options.touchZoom = touchZoom
                map.touchZoom.enable()
            } else {
                map.touchZoom.disable()
            }
        }


    }, [JSON.stringify(props)])

    useEffect(() => {
        ref.current = props
    })

    return null;
}

export default MapOptions;