import React, {Fragment, memo, useState} from "react";
import {ListItem, ListItemAvatar, Avatar, Box, Divider} from "@material-ui/core";
import {toLatLng} from "@redux-leaflet";
import {useDispatch} from "react-redux";
import $emitter from 'app/utils/eventEmitter'
import {includes} from 'lodash'

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
                    <Avatar alt="Remy Sharp" src="http://thuduc-covid.hcmgis.vn/storage/icons/sieuthi_pt.png"/>
                </ListItemAvatar>
                <Box pl={1}>
                    <Box className="text-blue-400 font-medium" pb={0.5}>
                        <Box>
                            {data.ten_ch} <span className="pl-3 text-red-500 text-xs">~ {data.distance}</span>
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
                        <button className="text-xs text-yellow-900" onClick={onShowMore}>{toggle ? 'Rút gọn' : 'Xem thêm'}</button>
                        <span className="px-8 text-gray-500" style={{fontSize: 10}}>|</span>
                        <button className="text-xs text-yellow-900" onClick={onDirection}>Chỉ đường</button>
                        {data.web_shopping && (
                            <Fragment>
                                <span className="px-8 text-gray-500" style={{fontSize: 10}}>|</span>
                                <button className="text-xs text-yellow-900" onClick={openWeb}>Mua sắm</button>
                            </Fragment>
                        )}
                    </Box>
                </Box>
            </ListItem>
            {toggle && (
                <Box mx={4} my={1} className="border-dashed border-2 border-light-gray-500 px-6 py-6 text-xs">
                    <div className="pb-4"><span className="font-medium">Thời gian hoạt động:</span> {data.tg_hoatdong}</div>
                    <div className="pb-4"><span className="font-medium">Hình thức giao trực tuyến:</span> {data.ht_giao_tt}</div>
                    {/*<div className="pb-4"><span className="font-medium">Điện thoại người liên hệ:</span> {data.dienthoai}</div>*/}
                    {/*<div className="pb-4"><span className="font-medium">Thông tin liên hệ:</span> {data.tt_lienhe}</div>*/}
                    <div className="pb-4"><span className="font-medium">Phường xã:</span> {data.tenphuong}</div>
                    <div className="pb-4"><span className="font-medium">Phân tuyến hàng hóa:</span> {data.tuyen_cc}</div>
                    {data.ghichu && <div><span className="font-medium">Ghi chú:</span> {data.ghichu}</div>}
                </Box>
            )}

            <Divider component="li" light/>
        </Box>
    )
}

export default memo(BoxItem)