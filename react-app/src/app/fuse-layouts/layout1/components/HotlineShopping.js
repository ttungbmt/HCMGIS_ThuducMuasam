import React, {Fragment, memo, useState} from "react";
import {Avatar, Box, Divider, ListItem, ListItemAvatar} from "@material-ui/core";
import {makeStyles, useTheme} from "@material-ui/core/styles";
import clsx from 'clsx';
import useMediaQuery from '@material-ui/core/useMediaQuery';
import Dialog from "@material-ui/core/Dialog";
import DialogTitle from "@material-ui/core/DialogTitle";
import DialogContent from "@material-ui/core/DialogContent";
import Button from "@material-ui/core/Button";
import DialogActions from "@material-ui/core/DialogActions";
import useSWR from "swr";
import MapsService from "app/services/mapsService/mapsService";
import {isEmpty, sortBy} from "lodash";
import {Table} from "app/components";
import Badge from "@material-ui/core/Badge";
import LocalMallIcon from '@material-ui/icons/LocalMall';

const useStyles = makeStyles(theme => ({
    root: {
        position: 'absolute',
        bottom: 25,
        left: '50%',
        zIndex: 9999,
        cursor: 'pointer',
        transform: 'translate(-50%, 0)'
    },
    text: {
        border: '2px solid white',
    },
    img: {
        position: 'absolute',
        width: 50,
        top: -11,
        right: 34
    },
    ico: {
        top: -5,
        left: -18,
        width: 40,
        height: 40,
        border: '2px solid white'
    }

}));

const columns = [
    {Header: 'ID', accessor: 'id', width: 10},
    {Header: 'Tên cửa hàng', accessor: 'ten_ch', width: 250},
    {
        Header: 'Hotline', accessor: 'hotline',
        Cell: ({row}) => row.original.hotline ? <a href={`tel:`+row.original.hotline} style={{color: '#cc003d'}}>{row.original.hotline}</a> : null
    },

    {Header: 'Phone App', accessor: 'app'},
    {Header: 'Website', accessor: 'web', Cell: ({row}) => row.original.web ? <a className="text-blue-500" href={row.original.web} target="_blank" style={{color: '#cc003d'}}>{row.original.web}</a> : null},
]

const ListHotline = ({data}) => {
    return (
        <Box>
            {data.map((v, k) => {
                return (
                    <Box key={k} >
                        <ListItem alignItems="flex-start" style={{padding: 0}}>
                            <Box>
                                <Box className="flex" mb={1.5} >
                                    <LocalMallIcon className="text-red-400" />
                                    <Box className="pl-4 text-red-400 font-semibold uppercase text-base">{v.ten_ch}</Box>
                                </Box>
                                <Box className="pl-6">
                                    {v.hotline && (
                                        <Box className="text-xs text-gray-800 mt-4" style={{fontSize: 13}} pb={1.2}>
                                            <i className="fal fa-phone pr-2"/> <a href={`tel:`+v.hotline} style={{color: '#0f9fe6'}}>{v.hotline}</a>
                                        </Box>
                                    )}
                                    {v.app && (
                                        <Box className="text-xs text-gray-800 mt-4" style={{fontSize: 13}} pb={1.2}>
                                            <i className="fal fa-mobile pr-2" /> {v.app}
                                        </Box>
                                    )}
                                    {v.web && (
                                        <Box className="text-xs text-gray-800 mt-4" style={{fontSize: 13}} pb={1.2}>
                                            <i className="fal fa-browser pr-2" /> <a href={v.web} target="_blank" style={{color: '#0f9fe6'}}>{v.web}</a>
                                        </Box>
                                    )}
                                </Box>

                            </Box>
                        </ListItem>
                        <Divider light className="mb-16"/>
                    </Box>
                )
            })}
        </Box>
    )
}

function HotlineShopping() {
    const classes = useStyles();
    const [open, setOpen] = useState(false);
    const theme = useTheme();
    const fullScreen = useMediaQuery(theme.breakpoints.down('sm'));
    const { data, error } = useSWR('hotline-shopping', (key) => MapsService.getHotlineShopping())

    const handleClickOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);

    return (
        <Fragment>
            <Box className={classes.root} onClick={handleClickOpen}>
                <Box className={clsx(classes.text, "relative bg-red-600 text-white uppercase font-bold px-12 pl-20 py-1 rounded-full")} style={{fontSize: fullScreen ? 14 : 18}}>
                    <span className="pl-4">Hotline mua sắm</span>
                </Box>
                <Box className={clsx(classes.ico, "absolute flex justify-center items-center bg-red-600 text-white text-2xl rounded-full")}> <i className="pl-2 fas fa-phone-volume"/></Box>
            </Box>
            <Dialog
                fullScreen={fullScreen}
                maxWidth={1200}
                open={open}
                onClose={handleClose}
                aria-labelledby="responsive-dialog-title"
            >
                <DialogContent>
                    {!isEmpty(data) && fullScreen ? <ListHotline data={sortBy(data, 'id')}/> : <Table data={sortBy(data, 'id')} columns={columns}/>}
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose} color="primary">
                        Đóng
                    </Button>
                </DialogActions>
            </Dialog>
        </Fragment>
    )
}

export default memo(HotlineShopping)