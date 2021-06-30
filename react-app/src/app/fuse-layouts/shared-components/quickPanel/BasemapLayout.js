import {memo} from "react";
import Box from "@material-ui/core/Box";
import clsx from "clsx";
import {Layer, MapContainer} from "@redux-leaflet";
import {useDispatch, useSelector} from "react-redux";
import {configSelectors, layersActions, layersSelectors} from "@redux-leaflet/store";
import {makeStyles} from "@material-ui/core/styles";
import Typography from "@material-ui/core/Typography";

const useStyles = makeStyles(theme => ({
    active: {
        border: '3px solid #0F9FE6',
    },
}));

function BasemapLayout({closeBtn}) {
    const classes = useStyles();
    const dispatch = useDispatch()

    const basemaps = useSelector(layersSelectors.selectBasemaps);
    const view = useSelector(configSelectors.selectView);

    const mapOptions = {
        className: 'h-full',
        ...view,
        zoomControl: false,
        attributionControl: false,
        dragging: false,
        scrollWheelZoom: false
    }

    const onActiveLayer = ({id}) => dispatch(layersActions.activeOne({ids: [id], active: true}))


    return (
        <>
            <Box className="flex items-center justify-between mb-10">
                <Typography className="text-base font-semibold">Basemap</Typography>
                {closeBtn}
            </Box>
            {basemaps.map((props, k) => (
                <Box className={clsx('relative h-full cursor-pointer overflow-auto', {[classes.active]: props.active})} key={props.id} mb={1.5} style={{height: 110}} borderRadius={5} onClick={e => onActiveLayer(props)}>
                    <MapContainer {...mapOptions}>
                        <Layer {...props}/>
                    </MapContainer>
                    <Box className="absolute px-6 py-4 font-semibold rounded ml-6 mb-6" zIndex={999} bottom={0} bgcolor="white">{props.title}</Box>
                </Box>
            ))}
        </>
    )
}

export default memo(BasemapLayout);