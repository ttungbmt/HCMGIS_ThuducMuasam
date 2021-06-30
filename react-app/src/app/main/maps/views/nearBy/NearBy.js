import {useGeolocation} from 'react-use';
import {FormProvider, useForm} from "react-hook-form";
import {Box, Button} from "@material-ui/core";
import React, {Fragment, useEffect} from "react";
import {InputField, useMFormContext, useSyncForm} from "@form";
import axios from 'axios'
import List from "@material-ui/core/List";

import Divider from "@material-ui/core/Divider";
import {isEmpty} from "lodash";
import {toLatLng} from "@redux-leaflet";
import BoxItem from "./BoxItem";

export const formName = 'nearbyForm'

export default function () {
    const {latitude, longitude} = useGeolocation();
    const methods = useForm({
        mode: 'onChange',
    });
    const {watch, control, setValue, getValues} = methods
    const formValues = watch();
    const {setFormData, getFormData, resetFormData} = useMFormContext()

    useSyncForm(formName, formValues)

    useEffect(() => {
        if (latitude && longitude) setValue('latlng', `${latitude}, ${longitude}`)
    }, [latitude, longitude])

    const onSubmit = async () => {
        const values = getValues()
        const {data} = await axios.get('/api/search/nearby', {params: {location: values.latlng.split(',').map(v => v.trim()).join(',')}})

        if (data.status === 'OK') {
            setFormData(formName, {items: data.data})
        }
    }

    const data = getFormData(formName)

    const onShowMore = (e, item) => {
        e.stopPropagation();
        console.log(item)
    }

    const onDirection = (e, item) => {
        e.stopPropagation();
        const values = getValues()
        const latlng = toLatLng(item.geometry)
        window.open(`https://www.google.com/maps/dir/${values.latlng}/${latlng.lat},${latlng.lng}`)
    }

    return (
        <Box>
            <Box className="flex mb-6" style={{background: 'url(https://maps.hcmgis.vn/core/themes/maps/assets/img/bando_02.png) no-repeat 150px 0 #f9f9f9', height: 50}}>
                <div className="flex pl-20 self-center">
                    <div className="uppercase font-semibold" style={{color: '#0D9FE6', fontSize: 16}}>Tìm kiếm lân cận</div> <div className="pl-6 self-center" style={{color: '#EA5628', fontSize: 11}}> > 10 đối tượng</div>
                </div>
            </Box>
            <FormProvider {...methods}>
                <Box px={1} className="flex">
                    <InputField name="latlng" label="Tọa độ định vị" shrink fullWidth/>
                    <Box className="ml-4 self-center">

                    </Box>
                </Box>
                <Box pb={1} className="flex justify-center">
                    <Button variant="outlined" color="primary" style={{width: 100}} onClick={onSubmit}>
                        Tìm kiếm
                    </Button>
                    {!isEmpty(data.items) && (
                        <Button className="ml-6" variant="outlined"  onClick={() => resetFormData(formName)}>
                            Xóa
                        </Button>
                    )}
                </Box>
            </FormProvider>

            {!isEmpty(data.items) && (
                <List>
                    <Divider light />
                    {data.items.map((i, k) => <BoxItem index={k} data={i} key={k} values={getValues()}/>)}

                </List>
            )}


        </Box>
    )
}