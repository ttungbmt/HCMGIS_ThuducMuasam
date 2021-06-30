import {memo, useEffect, useRef} from 'react';
import {useMount, useUpdateEffect} from 'react-use'
import moment from 'app/utils/moment'
import {createVerticalBar} from "app/utils/chart";

const formatData = (data) => data.map(v => ({...v, date: moment(v.date, 'DD/MM/YYYY').format('DD/MM')}))

function ChartTab({data, view}){
    const chartRef = useRef()
    const dataProvider = formatData(data)

    useEffect(() => {
        if(view === 'diadiem'){
            chartRef.current = createVerticalBar(dataProvider, {
                id: 'chartdiv',
                category: 'date',
                categoryY: 'date',
                valueX: 'value'
            })
        } else {
            chartRef.current = createVerticalBar(dataProvider, {
                id: 'chartdiv',
                category: 'label',
                categoryY: 'label',
                valueX: 'count',
                labelFill: '#252F3E',
            })
        }
    }, [view])

    useUpdateEffect(() => {
        chartRef.current.data = formatData(data)
        chartRef.current.invalidateRawData();
    }, [JSON.stringify(data)])

    return (
        <div>
            <div id="chartdiv" style={{width: '100', height: 1000}} />
        </div>
    )
}

export default memo(ChartTab)