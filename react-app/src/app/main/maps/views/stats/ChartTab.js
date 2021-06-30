import {memo} from 'react';
import {useMount} from 'react-use'
import {createVerticalBar} from "app/utils/chart";
import {Box} from "@material-ui/core";

function ChartTab({data}){
    useMount(() => {
        createVerticalBar(data, {
            id: 'chartdiv',
            category: 'tenphuong',
            categoryY: 'tenphuong',
            valueX: 'count',
            wrap: 200,
            labelFill: '#1B2330'
        })
    })

    return (
        <Box>
            <div id="chartdiv" style={{width: '100', height: 1200}} />
        </Box>
    )
}

export default memo(ChartTab)