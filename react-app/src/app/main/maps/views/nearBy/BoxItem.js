import React, {Fragment, memo, useState} from "react";
import {ListItem, ListItemAvatar, Avatar, Box, Divider} from "@material-ui/core";
import {toLatLng, strToLatLng} from "@redux-leaflet";
import {useDispatch} from "react-redux";
import $emitter from 'app/utils/eventEmitter'
import {includes, isEmpty} from 'lodash'

function BoxItem({index, data, values}) {
    const dispatch = useDispatch()

    const [toggle, setToggle] = useState(false)

    const onShowMore = (e) => {
        e.stopPropagation();
        setToggle(!toggle)
    }

    const onDirection = (e) => {
        e.stopPropagation();
        const latlng = toLatLng(data.geometry)
        window.open(`https://www.google.com/maps/dir/${values.latlng}/${latlng.lat},${latlng.lng}`)
    }

    const onShowMarker = () => {
        $emitter.emit('map/flyTo', data.geometry)
        $emitter.emit('marker/selected', index)
    }

    const openWeb = () => {
        if(includes(data.web_shopping, 'http')) window.open(data.web_shopping)
        else window.open(`http://`+data.web_shopping)
    }

    return (
        <Box>
            <ListItem alignItems="flex-start" button className="py-10" onClick={onShowMarker}>
                <ListItemAvatar>
                    <Avatar src={!isEmpty(data.ht_giao_tt) ? "http://thuduc-covid.hcmgis.vn/storage/icons/sieuthi_pt.png" : 'https://thuduc-muasam.hcmgis.vn/storage/icons/icon_shopping.png'}/>
                </ListItemAvatar>
                <Box pl={1}>
                    <Box className="text-blue-400 font-medium" pb={0.5}>
                        <Box className="flex">
                            <Box className="pr-2">{index+1}.</Box>
                            <Box>{data.ten_ch} <span className="pl-3 text-red-500 text-xs">~ {data.distance}</span></Box>
                        </Box>
                    </Box>
                    <Box className="text-xs text-gray-600" pb={1.2}>
                        {data.loaihinh}
                    </Box>
                    {data.app_shopping && (
                        <Box className="text-xs text-gray-800" style={{fontSize: 13}} pb={1.2}>
                            <i className="fal fa-browser text-blue-500 pr-2"></i> {data.app_shopping}
                        </Box>
                    )}
                    {data.hotline_shopping && (
                        <Box className="text-xs text-gray-800" style={{fontSize: 13}} pb={1.2}>
                            <i className="fal fa-phone text-blue-500 pr-2"></i> {data.hotline_shopping}
                        </Box>
                    )}
                    <Box className="text-xs text-gray-800" style={{fontSize: 13}} pb={1.2}>
                        <i className="fal fa-address-card text-blue-500 pr-2"></i> {data.diachi}
                    </Box>
                    <Box>
                        <button className="text-xs text-yellow-900" onClick={onShowMore}>{toggle ? 'R??t g???n' : 'Xem th??m'}</button>
                        <span className="px-8 text-gray-500" style={{fontSize: 10}}>|</span>
                        <button className="text-xs text-yellow-900" onClick={onDirection}>Ch??? ???????ng</button>
                        {data.web_shopping && (
                            <Fragment>
                                <span className="px-8 text-gray-500" style={{fontSize: 10}}>|</span>
                                <button className="text-xs text-yellow-900" onClick={openWeb}>Mua s???m</button>
                            </Fragment>
                        )}
                    </Box>
                </Box>
            </ListItem>
            {toggle && (
                <Box mx={4} my={1} className="border-dashed border-2 border-light-gray-500 px-6 py-6 text-xs">
                    <div className="pb-4"><span className="font-medium">Th???i gian ho???t ?????ng:</span> {data.tg_hoatdong}</div>
                    <div className="pb-4"><span className="font-medium">H??nh th???c giao h??ng tr???c tuy???n:</span> {data.ht_giao_tt}</div>
                    <div className="pb-4"><span className="font-medium">Danh m???c h??ng h??a:</span> {data.hanghoa}</div>
                    <div className="pb-4"><span className="font-medium">Ph?????ng:</span> {data.tenphuong}</div>
                    <div className="pb-4"><span className="font-medium">Ph??n tuy???n h??ng h??a:</span> {data.tuyen_cc}</div>
                    {data.ghichu && <div><span className="font-medium">Ghi ch??:</span> {data.ghichu}</div>}
                </Box>
            )}

            <Divider component="li" light/>
        </Box>
    )
}

export default memo(BoxItem)