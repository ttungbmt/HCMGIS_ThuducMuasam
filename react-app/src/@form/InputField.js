import {Controller, useFormContext} from "react-hook-form";
import {TextField} from "@material-ui/core";
import React from "react";

function InputField({name, label}) {
    const {control} = useFormContext();

    return (
        <Controller
            name={name}
            control={control}
            render={({field}) => (
                <TextField
                    {...field}
                    label={label}
                    className="mt-8 mb-10 mx-4"
                    variant="outlined"
                />
            )}
        />
    )
}

export default InputField