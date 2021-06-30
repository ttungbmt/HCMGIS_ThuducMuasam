import React, {memo} from 'react'
import {useForm, FormProvider} from 'react-hook-form';
import {
    Box,
    makeStyles,
    Button,
    LinearProgress,
} from "@material-ui/core";
import _, {isEmpty, map, pick} from "lodash";
import useSWR from "swr";
import axios from 'axios'
import {useUpdateEffect} from 'react-use'
import {useMFormContext, InputField, DateField, useSyncForm} from "@form";
import {useHistory} from "react-router";
import useNext from "app/hooks/useNext";
import {toUnixDate} from 'app/utils/moment'

const useStyles = makeStyles((theme) => ({
    root: {},
    tab: {
        minWidth: 72,
    },
}))

const fetcher = (...args) => axios.get(...args).then(res => res.data)

export const formName = 'dientienCovid'

const formatData = data => data.map(d => ({...d.properties, geometry: d.geometry}))

function DientienCovid() {
    const history = useHistory();
    const {getFormDefaultValues, setFormData, getFormData, updateFormValues, getFormValues} = useMFormContext()
    const defaultValues = getFormDefaultValues(formName)
    const formData = getFormData(formName)

    const classes = useStyles();

    const methods = useForm({
        defaultValues,
        mode: 'onChange'
    });
    const {watch, control, setValue} = methods
    const formValues = watch();

    const {data: {data, meta} = {}, isValidating} = useSWR(formValues.key, fetcher, {
        revalidateOnFocus: false,
        revalidateOnReconnect: false
    })

    useSyncForm(formName, formValues)
    useNext('/maps/dientien-covid/detail', !isEmpty(formData))

    useUpdateEffect(() => {
        if(data) {
            setFormData(formName, {
                data: formatData(data),
                meta
            })
            history.push('/maps/dientien-covid/detail')
        }
    }, [JSON.stringify(data)])

    const onDownload = () => {
        let min_date = toUnixDate(formValues.from_date),
            max_date = toUnixDate(formValues.to_date)

        map({
            range: [min_date, max_date],
            min_date,
            max_date,
            key: ['/api/cabenhs', {params: pick(formValues, ['from_date', 'to_date'])}]
        }, (v, k) => setValue(k, v))
    }

    return (
        <div className="p-10">
            <Box pb={2}>
                <FormProvider {...methods}>
                    <Box className="flex">
                        <DateField name="from_date" label="Từ ngày"/>
                        <DateField name="to_date" label="Đến ngày"/>
                    </Box>
                    <Box className="flex justify-center gap-6">
                        <Box className="flex align-center">
                            <Button color="primary" className="mb-6" variant="outlined" onClick={onDownload}>
                                Tải dữ liệu
                            </Button>
                        </Box>
                    </Box>

                    {isValidating && <Box my={3}><LinearProgress className="max-w-full rounded-2"/></Box>}
                </FormProvider>
            </Box>


        </div>
    )
}

export default memo(DientienCovid)