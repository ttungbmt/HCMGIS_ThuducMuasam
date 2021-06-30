import React, { useState } from 'react'
import constate from 'constate'
import {useImmerReducer} from "use-immer";
import {keys, map} from 'lodash'
import {unixDate, today} from "app/utils/moment";
import {useUpdateEffect} from "react-use";

const initialState = {
    nearbyForm: {
        defaultValues: {

        },
        values: {

        },
        data: {},
        meta: {},
    }
};

function reducer(state, {type, payload}) {
    switch (type) {
        case 'reset':
            state[payload].values = {}
            state[payload].data = {}
            state[payload].meta = {}
            return;
        case 'setValues':
            state[payload.name].values = payload.values
            return;
        case 'updateValues':
            map(payload.values, (value, key) => {
                state[payload.name].values[key] = value
            })
            return;
        case 'updateData':
            map(payload.data, (value, key) => {
                state[payload.name].data[key] = value
            })
            return;
        case 'setData':
            state[payload.name].data = payload.data
            return;
        case 'resetData':
            state[payload].data = {}
            return;
    }
}


function useMForm() {
    const [state, dispatch] = useImmerReducer(reducer, initialState);
    const getFormDefaultValues = (name) => state[name].defaultValues
    const getFormValues = (name) => state[name].values
    const getFormData = (name) => state[name].data
    const getFormMeta = (name) => state[name].meta
    const getFormNames = () => keys(state)
    const initialize = (name, data) => dispatch({type: 'initialize', payload: {name, data}})
    const reset = (name) => dispatch({type: 'reset', payload: name})
    const resetFormData = (name) => dispatch({type: 'resetData', payload: name})

    const setFormValues = (name, values) => dispatch({type: 'setValues', payload: {name, values}})
    const updateFormValues = (name, values) => dispatch({type: 'updateValues', payload: {name, values}})
    const updateFormData = (name, data) => dispatch({type: 'updateData', payload: {name, data}})
    const setFormData = (name, data) => dispatch({type: 'setData', payload: {name, data}})

    return { state, dispatch, getFormDefaultValues, getFormData, getFormValues, setFormValues, setFormData, updateFormValues, updateFormData, reset, resetFormData };
}

export const useForm = (name) => {
    const {getFormData, getFormValues, updateFormValues, reset, setFormValues, updateFormData, resetFormData} = useMFormContext()
    return {
        values: getFormValues(name),
        data: getFormData(name),
        setValues: (values) => setFormValues(name, values),
        updateValues: (values) => updateFormValues(name, values),
        updateData: (data) => updateFormData(name, data),
        reset: () => reset(name),
        resetData: () => resetData(name),
    }
}

export const useSyncForm = (name, values, callback = () => {}) => {
    const methods = useForm(name)

    useUpdateEffect(() => {
        methods.setValues(values)
        callback(methods)
    }, [JSON.stringify(values)])
}

export const [MFormProvider, useMFormContext] = constate(useMForm);