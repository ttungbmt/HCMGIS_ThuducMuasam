import React, {memo} from "react";
import Box from "@material-ui/core/Box";
import Typography from "@material-ui/core/Typography";
import {useQuery} from "react-query";
import mapsService from "app/services/mapsService";
import LinearProgress from "@material-ui/core/LinearProgress";

function useLegend() {
    return useQuery('legend', mapsService.getLegend);
}

function LegendLayout({closeBtn}) {
    const { status, data, error, isFetching } = useLegend();

    return (
        <>
            <Box className="flex items-center justify-between mb-10">
                <Typography className="text-base font-semibold">Chú giải</Typography>
                {closeBtn}
            </Box>
            {isFetching ? <LinearProgress /> : <div dangerouslySetInnerHTML={{ __html: data }} />}
        </>
    )
}

export default memo(LegendLayout);