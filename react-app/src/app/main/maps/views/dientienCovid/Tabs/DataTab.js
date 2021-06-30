import {memo, useEffect} from "react";
import {DataGrid} from '@material-ui/data-grid';
import {toLatLng} from "@redux-leaflet";
import {setCenter} from "@redux-leaflet/store/config/config.actions";
import {popupsActions} from "@redux-leaflet/store";
import {nanoid} from "@reduxjs/toolkit";
import {useDispatch} from "react-redux";

const columns = [
    { field: 'ma_kv', headerName: '#', width: 70 },
    { field: 'hoten', headerName: 'Họ tên', width: 200 },
    { field: 'ngay_ntt', headerName: 'Ngày nhận thông tin', width: 150 },
];

function DataTab({data, meta}){
    const dispatch = useDispatch()
    const onRowClick = ({row}) => {
        const {geometry, ...properties} = row
        const id = nanoid()

        dispatch(setCenter(geometry))
        dispatch(popupsActions.setCurrentId(id))
        dispatch(popupsActions.setAll([{
            id,
            template: meta.popup,
            latlng: toLatLng(geometry),
            data: {geometry, properties}
        }]))
    }

    return (
        <div style={{ height: 500, width: '100%' }}>
            <DataGrid rows={data} columns={columns} pageSize={10} columnBuffer={2} onRowClick={onRowClick}/>
        </div>
    )
}

export default memo(DataTab)

