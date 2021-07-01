import {useGeolocation} from 'react-use';
import {FormProvider, useForm} from "react-hook-form";
import {Box, Button} from "@material-ui/core";
import React, {useEffect, useState} from "react";
import {InputField, useMFormContext, useSyncForm} from "@form";
import axios from 'axios'
import List from "@material-ui/core/List";
import Divider from "@material-ui/core/Divider";
import {isEmpty, toNumber} from "lodash";
import {toLatLng, toFeature, strToLatLng} from "@redux-leaflet";
import BoxItem from "./BoxItem";
import $emitter from "app/utils/eventEmitter";
import LinearProgress from "@material-ui/core/LinearProgress";
import {setBounds} from "@redux-leaflet/store/config/config.actions";
import {useDispatch} from "react-redux";


export const formName = 'nearbyForm'

export default function () {
    const dispatch = useDispatch()
    const [loading, setLoading] = useState(false)
    const {latitude, longitude} = useGeolocation();
    const methods = useForm({
        mode: 'onChange',
    });
    const {watch, setValue, getValues} = methods
    const formValues = watch();
    const {setFormData, getFormData, resetFormData} = useMFormContext()

    useSyncForm(formName, formValues)

    useEffect(() => {
        if (latitude && longitude) {
            setValue('latlng', `${latitude}, ${longitude}`)
            onSubmit()
        }
    }, [latitude, longitude])

    useEffect(() => {
        $emitter.on('marker/dragend', (latlng) => {
            setValue('latlng', latlng.join(','))
            onSubmit()
        })

        $emitter.on('map/contextmenu', ({latlng}) => {
            setValue('latlng', [latlng.lat, latlng.lng].join(','))
            onSubmit()
        })

        return () => {
            $emitter.off('marker/dragend')
            $emitter.off('map/contextmenu')
        }
    }, [])

    const onSubmit = async () => {
        const values = getValues()
        setLoading(true)
        const {data} = await axios.get('/api/search/nearby', {params: {location: values.latlng.split(',').map(v => v.trim()).join(',')}})

        if (data.status === 'OK') {
            const latlng = strToLatLng(values.latlng)
            setFormData(formName, {items: data.data})
            let features = data.data.map(v => ({type: 'Feature', geometry: v.geometry}))
            features.push(toFeature(latlng))
            setTimeout(() => dispatch(setBounds(features)), 300)
        }
        setLoading(false)
    }

    const data = getFormData(formName)


    return (
        <Box>
            <Box className="flex" style={{background: 'url(https://maps.hcmgis.vn/core/themes/maps/assets/img/bando_02.png) no-repeat 150px 0 #f9f9f9', height: 50}}>
                <div className="flex pl-20 self-center">
                    <div className="uppercase font-semibold" style={{color: '#0D9FE6', fontSize: 16}}>Điểm mua sắm gần nhất</div> <div className="pl-6 self-center" style={{color: '#EA5628', fontSize: 11}}> > 20 đối tượng</div>
                </div>
            </Box>
            {loading && <LinearProgress />}

            <FormProvider {...methods}>
                <Box mt={1.5} px={1} className="flex">
                    <InputField name="latlng" label="Vị trí của tôi" shrink fullWidth/>
                </Box>
                <Box pb={1} className="flex justify-center">
                    <Button variant="outlined" color="primary" onClick={onSubmit}>
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