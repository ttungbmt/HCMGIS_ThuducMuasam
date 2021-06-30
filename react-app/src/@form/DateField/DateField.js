import React from 'react';
import {Controller, useFormContext} from 'react-hook-form';
import {TextField} from '@material-ui/core';
import 'flatpickr/dist/themes/airbnb.css';
import flatpickr from 'flatpickr'
import Flatpickr from "react-flatpickr";
import {head} from 'lodash'
import moment from 'moment'
import Vietnamese from './vn'
import './style.css'

flatpickr.l10ns.vi = Vietnamese

function DateField(props) {
    const {name, label, mode = 'single', dateFormat = 'd/m/Y', locale = 'vi', ...options} = props
    const {control} = useFormContext();

    return <Controller
        name={name}
        control={control}
        render={({field}) => {
                return <Flatpickr
                    {...field}
                    onChange={val => {
                        if(mode === 'single' && dateFormat === 'd/m/Y') {
                            field.onChange(moment(head(val)).format('DD/MM/YYYY'))
                        } else {
                            field.onChange(val)
                        }
                    }}
                    options={{
                        locale,
                        dateFormat,
                        mode,
                        ...options
                    }}
                    render={
                        ({defaultValue, value, ...props}, ref) => <TextField
                            label={label}
                            variant="outlined"
                            className="mt-8 mb-10 mx-4"
                            InputLabelProps={{
                                shrink: true
                            }}
                            InputProps={{
                                inputRef: ref
                            }}
                            defaultValue={defaultValue} inputRef={ref}
                        />
                    }
                />
            }}
    />

}

export default DateField