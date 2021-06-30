import React from "react";
import {useMap} from 'react-leaflet';
import QuickPanelToggleButton from "app/fuse-layouts/shared-components/quickPanel/QuickPanelToggleButton";
import {layouts} from "app/fuse-layouts/shared-components/quickPanel/store/dataSlice";
import {useSelector} from "react-redux";

function MapHeader() {
    const map = useMap()

    const onMouseOver = () => map.dragging.disable()
    const onMouseOut = () => map.dragging.enable()

    return (
        <div className="absolute -top-0 right-0 flex pt-10 pr-10" style={{zIndex: 999}}>
            {layouts.map(({icon, tooltip, ...layout}, k) => (
                <QuickPanelToggleButton layout={layout} tooltip={tooltip} key={k}>
                    <i className={icon} />
                </QuickPanelToggleButton>
            ))}
        </div>
    )
}

export default MapHeader