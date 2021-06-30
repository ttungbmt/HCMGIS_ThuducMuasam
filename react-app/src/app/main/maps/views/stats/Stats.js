import React, {memo, useState} from "react";
import Table from "app/components/Table";
import useSWR from "swr";
import LinearProgress from "@material-ui/core/LinearProgress";
import {Box, makeStyles, Tab, Tabs, Drawer} from "@material-ui/core";
import TabPanel from "../dientienCovid/TabPanel";
import ChartTab from "./ChartTab";
import BarChartIcon from '@material-ui/icons/BarChart';
import StorageIcon from '@material-ui/icons/Storage';

const useStyles = makeStyles((theme) => ({
    root: {},
    tabRoot: {
        minHeight: 50
    },
    tabWrapper: {
        flexDirection: 'row'
    },
    icon: {
        marginBottom: '0!important',
        paddingRight: 5
    }
}))

const columns = [
    {Header: 'Phưỡng xã', accessor: 'tenphuong', width: 250},
    {Header: 'Ca dương tính', accessor: 'count'},
    {Header: 'Trong ngày', accessor: 'today_count'},
]

function Stats() {
    const classes = useStyles();
    const [tab, setTab] = useState(0);
    const {data, isValidating} = useSWR('/api/thongke/px-f0')
    const dataProvider = data ? data.data : []

    const handleTabChange = (event, value) => setTab(value);

    if (isValidating) return <LinearProgress className="max-w-full rounded-2"/>

    return (
        <Box className="flex flex-col h-full">
           {/* <Drawer open={true} anchor="top">
                <Box className="h-screen">

                </Box>
            </Drawer>*/}

			<Box>
				<Tabs
					value={tab}
					onChange={handleTabChange}
					indicatorColor="primary"
					textColor="primary"
					className="w-full border-b-1"
					variant="fullWidth"
				>
					<Tab label="THỐNG KÊ" classes={{wrapper: classes.tabWrapper, root: classes.tabRoot}}
						 icon={<StorageIcon className={classes.icon}/>}/>
					<Tab label="BIỂU ĐỒ" classes={{wrapper: classes.tabWrapper, root: classes.tabRoot}}
						 icon={<BarChartIcon className={classes.icon}/>}/>
				</Tabs>
			</Box>
			<Box className="flex-1 overflow-hidden">
				<Box className="h-full overflow-auto">
					<TabPanel value={tab} index={0}>
						<Table columns={columns} data={dataProvider}/>
					</TabPanel>
					<TabPanel value={tab} index={1}>
						<ChartTab data={dataProvider}/>
					</TabPanel>
				</Box>
			</Box>
        </Box>
    )
}

export default memo(Stats)