import {makeStyles} from '@material-ui/core/styles';
import {useDispatch, useSelector} from 'react-redux';
import {isEmpty, map, get} from 'lodash';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import {FancyTree, getToggleFiles, useTreeContext} from '@ttungbmt/react-fancytree';
import {layersSelectors, layersActions} from '@redux-leaflet/store';
import React, {useEffect, useState} from 'react';
import clsx from 'clsx';
import NavbarToggleButton from 'app/fuse-layouts/shared-components/NavbarToggleButton';
import TextField from "@material-ui/core/TextField";
import InputAdornment from "@material-ui/core/InputAdornment";
import Icon from "@material-ui/core/Icon";
import Box from "@material-ui/core/Box";
import {useDebouncedValue} from 'app/hooks';
import Badge from "@material-ui/core/Badge";
import {Menu} from "app/components";
import {SearchBar, SearchBarProvider} from "app/components/SearchBar";
import {toggleQuickPanelLayout} from "app/fuse-layouts/shared-components/quickPanel/store/stateSlice";
import Alert from "@material-ui/lab/Alert";


const useStyles = makeStyles({
    layoutRoot: {},
    appBar: {
        // backgroundImage: 'linear-gradient(90deg, #80485B 0%, #af637d 100%)',
        // backgroundImage: 'linear-gradient(90deg, #039BE5 0%, #9AD7F5 100%)',
        background: 'url(https://maps.hcmgis.vn/core/themes/maps/assets/img/bando_02.png) no-repeat 150px 0 #f9f9f9',
        borderBottom: '1px solid rgba(0,0,0,0.07)',
        boxShadow: 'none',
    },
    toolBar: {
        minHeight: 70,
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center'
    },
    input: {
        padding: '2px'
    },
    badge: {
        height: 15,
        maxWidth: 15,
        fontSize: 9,
        color: 'white'
    }
});

function LayersToolbar() {
    const dispatch = useDispatch()
    const {toggleExpandAll} = useTreeContext()
    const [isExpand, setIsExpand] = useState(false)

    const items = [
        {
            tooltip: `${isExpand ? 'Collapse' : 'Expand'} All`, icon: isExpand ? 'fad fa-compress-arrows-alt' : 'fad fa-expand-arrows', onClick: e => {
                setIsExpand(!isExpand)
                toggleExpandAll()
            }
        },
    ]

    return (
        <Box className="flex items-center justify-between border-b-1 border-gray-300 px-6" height={45}>
            <Menu text={'Basemap'} icon={'fad fa-layer-group'} onClick={() => dispatch(toggleQuickPanelLayout('basemap'))}/>
            <Box>
                {items.map((v, k) => <Menu key={k} {...v}/>)}
            </Box>
        </Box>
    )
}


function Layers() {
    const dispatch = useDispatch();
    const classes = useStyles();
    const [searchTerm, setSearchTerm] = useDebouncedValue('')
    const [count, setCount] = useState(undefined)
    // const config = useSelector(({ fuse }) => fuse.settings.current.layout.config);
    // const navbar = useSelector(({ fuse }) => fuse.navbar);
    // const settings = useSelector(({ fuse }) => fuse.settings.current);
    const {source, selectOne, select, setExpand, setCollapse, tree} = useTreeContext();

    const onSelect = (event, {node}) => {
        let ids = map(getToggleFiles(node), n => n.data.id),
            payload = {ids, selected: node.isSelected(), active: node.isSelected()};

        if (node.parent) payload.siblingIds = map(node.parent.children, 'data.id')

        if (_.get(node, 'parent.radiogroup') === true) {
            dispatch(layersActions.activeOne(payload));
            selectOne(payload)
        } else {
            dispatch(layersActions.active(payload))
            select(payload)
        }
    };

    const onExpand = (event, {node}) => setExpand(node.data.id)

    const onCollapse = (event, {node}) => setCollapse(node.data.id)

    useEffect(() => {
        if (tree) {
            let founded = tree.filterNodes.call(tree, searchTerm, {
                mode: 'hide',
                autoExpand: true,
                counter: true
            })
            setCount(founded)
        }
    }, [searchTerm])

    const appName = get(window.builder, 'app.name', 'Map App')
    const cabenhsCount = get(window.builder, 'app.cabenhs_count', 0)

    return (
        <div>
            <AppBar position="static" className={clsx(classes.appBar)}>
                <Toolbar className={clsx(classes.toolBar)}>
                    <Box className="flex">
                        <Typography variant="h5" className="uppercase font-semibold" style={{color: '#0D9FE6'}}>
                            {appName}
                        </Typography>
                        <Typography className="font-semibold uppercase self-center pl-6" style={{color: '#EA5628'}}>> Lớp dữ liệu</Typography>
                    </Box>

                </Toolbar>
            </AppBar>

            <Alert severity="error">Khuyến cáo người dân thực hiện việc mua sắm bằng hình thức trực tuyến trong thời gian này!</Alert>
            {/*<SearchBar />*/}

            <LayersToolbar/>
            <Box p={1} pt={0.5}>
                <Box mb={1}>
                    <TextField
                        fullWidth
                        placeholder="Tìm kiếm..."
                        inputProps={{type: 'search'}}
                        InputProps={{
                            classes: {root: classes.input},
                            startAdornment: (
                                <InputAdornment position="start">
                                    <Badge classes={{badge: classes.badge}} badgeContent={count} color="secondary">
                                        <Icon color="action">search</Icon>
                                    </Badge>
                                </InputAdornment>
                            ),
                        }}
                        onChange={e => setSearchTerm(e.target.value)}
                    />
                </Box>


                {!isEmpty(source) &&
                <FancyTree source={source} onSelect={onSelect} onExpand={onExpand} onCollapse={onCollapse}/>}
            </Box>
        </div>
    );
}

export default Layers;
