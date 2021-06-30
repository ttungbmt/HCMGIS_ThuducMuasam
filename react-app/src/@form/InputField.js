import {Controller, useFormContext} from 'react-hook-form';
import {TextField} from '@material-ui/core';
import React from 'react';

function InputField({name, label, shrink = false, ...props}) {
    const {control} = useFormContext();

    return (
        <Controller
            name={name}
            control={control}
            render={({field}) => {
                return <TextField
                    onChange={field.onChange}
                    label={label}
                    InputLabelProps={{
                        shrink
                    }}
                    InputProps={{
                        inputRef: field.ref
                    }}
                    className="mt-8 mb-10 mx-4"
                    {...props}
                    variant="outlined"
                />
            }}
        />
    )
}

export default InputField