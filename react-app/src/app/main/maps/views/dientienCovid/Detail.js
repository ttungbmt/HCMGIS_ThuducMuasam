import React, {memo, useEffect, useState} from "react";
import {makeStyles, Tab, Tabs, MenuItem, Typography, Box} from "@material-ui/core";
import TabPanel from "./TabPanel";
import DataTab from "./Tabs/DataTab";
import StatsTab from "./Tabs/StatsTab";
import ChartTab from "./Tabs/ChartTab";
import _, {debounce, isEmpty, last, indexOf, findIndex, get } from "lodash";
import moment, {toUnixDate} from 'app/utils/moment';
import {useForm} from "@form";
import {formName} from "./DientienCovid"
import useBack from 'app/hooks/useBack';
import KeyboardBackspaceOutlinedIcon from '@material-ui/icons/KeyboardBackspaceOutlined';
import {useHistory} from "react-router";
import RangeSlider from "app/components/RangeSlider";
import PauseCircleOutlineRoundedIcon from '@material-ui/icons/PauseCircleOutlineRounded';
import PlayArrowRoundedIcon from '@material-ui/icons/PlayArrowRounded';
import MapOutlinedIcon from '@material-ui/icons/MapOutlined';
import AllOutOutlinedIcon from '@material-ui/icons/AllOutOutlined';
import {Button} from "app/components";
import {useUpdateEffect} from "react-use";
import ViewMenu from "./ViewMenu";

const useStyles = makeStyles((theme) => ({
    tab: {
        minWidth: 72,
    },
    margin: {
        padding: 7,
    }
}))

const getStatsData = (data, range, orderType = 'asc') => _.chain(data)
    .filter('ngay_ntt')
    .map(v => ({...v, unix: moment(v.date, 'DD/MM/YYYY').unix()}))
    // .filter(v => v.unix >= range[0] && v.unix <= range[1])
    .groupBy('ngay_ntt')
    .mapValues((v, k) => ({date: k, value: v.length}))
    .orderBy('unix', orderType)
    .value()

export const getStatsDataByHc = (data, range) => _.chain(data).filter(v => {
    let unix = toUnixDate(v.ngay_ntt)
    return v.ngay_ntt && (unix >= range[0] && unix <= range[1])
}).groupBy('maphuong').map((v, k) => {
    let tenphuong = get(v, '0.tenphuong')
    return {label: tenphuong ? tenphuong : 'None', count: v.length, code: k}
}).orderBy('count', ['desc']).value()

const backUrl = '/maps/dientien-covid'

const toDateList = (data) => _.chain(data).groupBy('date').keys().sortBy(v => moment(v, 'DD/MM/YYYY').unix()).map(v => moment(v, 'DD/MM/YYYY').unix()).value()

function useTimer() {
    const {updateValues, data, values} = useForm(formName)
    const {is_play = false, timer_index = 0, range} = values

    useEffect(() => {
        let interval = null;
        if (is_play) {
            const dates = toDateList(data.data)

            interval = setInterval(() => {
                let end_date = dates[timer_index]
                if (!end_date) {
                    clearInterval(interval)
                    updateValues({timer_index: 0, is_play: !is_play})
                } else {
                    updateValues({timer_index: timer_index + 1, range: [range[0], end_date]})
                }
            }, 1000);
        } else {
            clearInterval(interval)
        }
        return () => clearInterval(interval);
    }, [is_play, timer_index]);
}

function useRange() {
    const {updateValues, data: formData, values: formValues} = useForm(formName)
    const {is_play = false, timer_index, ...values} = formValues
    const [range, setRange] = useState(values.range)

    useUpdateEffect(() => {
        setRange(values.range)
    }, [timer_index])

    const updateWhenPlay = ({from, to}) => {
        const dates = toDateList(formData.data)
        let fromVal = last(dates.filter(v => v <= from)),
            toVal = last(dates.filter(v => v <= to)),
            toIndex = indexOf(dates, toVal)

        updateValues({range: [fromVal, toVal], timer_index: toIndex})
    }

    const onFinish = ({from, to}) => {
        if(is_play){
            updateValues({is_play: !is_play})
            updateWhenPlay({from, to})
        }
    }

    const onChangeSlider = debounce(({from, to}) => {
        if(!is_play) updateValues({range: [from, to]})
    }, 100)

    return {range, onFinish, onChangeSlider}
}

function Detail() {
    const history = useHistory()
    const classes = useStyles();
    const [selectedTab, setSelectedTab] = useState(2);
    const {updateValues, data: formData, values: formValues, resetData} = useForm(formName)
    const handleTabChange = (event, value) => setSelectedTab(value);
    const {view, min_date, max_date, is_play = false, is_cluster = false, is_shown = false, timer_index, ...values} = formValues
    const {data, meta} = formData

    useBack(backUrl, isEmpty(formData))
    useTimer()

    const {range, onFinish, onChangeSlider} = useRange()
    const viewItems = [
        {name: 'diadiem', label: 'Địa điểm', onClick: () => updateValues({view: 'diadiem'})},
        {name: 'khuvuc', label: 'Khu vực', onClick: () => updateValues({view: 'khuvuc'})},
    ]
    const selectedView = findIndex(viewItems, {name: view})

    const onBack = () => {
        history.push(backUrl)
        resetData()
    }

    if(isEmpty(formData)) return null

    const statsData = view === 'diadiem' ? getStatsData(data, values.range) : getStatsDataByHc(data, values.range)
    console.log(statsData)

    return (
        <Box>
            <Box className="flex items-center justify-between border-b-1 border-gray-300" height={45} pl={1} pr={2}>
                <Box className="flex align-center">
                    <Button tooltip="Quay lại" onClick={onBack} icon={<KeyboardBackspaceOutlinedIcon />}/>
                    <Typography className="self-center uppercase font-medium pl-10">
                        Chi tiết
                    </Typography>
                </Box>
                <Box>
                    {(is_shown && view === 'diadiem') && <Button tooltip="Cluster" icon={<AllOutOutlinedIcon color={is_cluster ? 'primary' : 'inherit'}/>} onClick={() => updateValues({is_cluster: !is_cluster})}/>}
                    <Button tooltip="Hiển thị" icon={<MapOutlinedIcon color={is_shown ? 'primary' : 'inherit'}/>} onClick={() => updateValues( {is_shown: !is_shown})}/>
                    <ViewMenu items={viewItems} selected={selectedView}/>
                    <Button tooltip="Biểu diễn" icon={is_play ? <PauseCircleOutlineRoundedIcon color="primary"/>: <PlayArrowRoundedIcon/>} onClick={() => updateValues({is_play: !is_play})}/>
                </Box>
            </Box>
            <Box px={4} py={2}>
                <RangeSlider
                    className="flex-1"
                    type="double"
                    min={min_date}
                    max={max_date}
                    from={range[0]}
                    to={range[1]}
                    onFinish={onFinish}
                    onChange={onChangeSlider}
                    prettify={num => moment(num, 'X').format('L')} grid={true}
                />
            </Box>
            <Tabs
                value={selectedTab}
                onChange={handleTabChange}
                indicatorColor="primary"
                textColor="primary"
                className="w-full border-b-1"
                variant="fullWidth"
            >
                <Tab label="Dữ liệu" className={classes.tab}/>
                <Tab label="Thống kê" className={classes.tab}/>
                <Tab label="Biểu đồ" className={classes.tab}/>
            </Tabs>
            <TabPanel value={selectedTab} index={0}>
                {data && <DataTab data={data} meta={meta}/>}
            </TabPanel>
            <TabPanel value={selectedTab} index={1}>
                {data && <StatsTab view={view} data={statsData}/>}
            </TabPanel>
            <TabPanel value={selectedTab} index={2}>
                {data && <ChartTab view={view} data={statsData}/>}
            </TabPanel>
        </Box>
    )
}

export default memo(Detail)