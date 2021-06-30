import FuseScrollbars from '@fuse/core/FuseScrollbars';
import ClickAwayListener from '@material-ui/core/ClickAwayListener';
import {ThemeProvider, makeStyles, useTheme} from '@material-ui/core/styles';
import useMediaQuery from '@material-ui/core/useMediaQuery';
import clsx from 'clsx';
import {memo, useState, useEffect, useContext} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import FuseNavigation from '@fuse/core/FuseNavigation/FuseNavigation';
import {selectNavigation} from 'app/store/fuse/navigationSlice';
import {navbarCloseMobile} from 'app/store/fuse/navbarSlice';
import {selectContrastMainTheme} from 'app/store/fuse/settingsSlice';
import {useLocation} from 'react-router-dom';
import FuseSuspense from '@fuse/core/FuseSuspense';
import {renderRoutes} from 'react-router-config';
import AppContext from 'app/AppContext';
import history from '@history';
import {actions as mapActions, selectors as mapSelectors} from 'app/main/maps/store';
import {Box} from "@material-ui/core";

const useStyles = makeStyles(theme => ({
    root: {
        // backgroundColor: theme.palette.primary.main,
        backgroundImage: 'linear-gradient(0deg, #9AD7F5 0%, #039BE5 100%)',
        color: theme.palette.primary.contrastText
    },
    sidePanel: {
        boxShadow: '0 0 transparent, 0 0 transparent, 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%)',
        zIndex: 100
    },
    panel: {
        // backgroundColor: theme.palette.background.default,
        backgroundColor: 'white',
        color: theme.palette.text.primary,
        transition: theme.transitions.create(['opacity'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.shortest
        }),
        opacity: 0,
        pointerEvents: 'none',
        '&.opened': {
            opacity: 1,
            pointerEvents: 'initial'
        }
    },
	logo: {
    	borderRadius: '50%',
		border: '2px solid white'
	}
}));

function needsToBeOpened(location, item) {
    return location && isUrlInChildren(item, location.pathname);
}

function isUrlInChildren(parent, url) {
    if (!parent.children) {
        return false;
    }

    for (let i = 0; i < parent.children.length; i += 1) {
        if (parent.children[i].children) {
            if (isUrlInChildren(parent.children[i], url)) {
                return true;
            }
        }

        if (parent.children[i].url === url) {
            return true;
        }
    }

    return false;
}

function NavbarStyle3Content(props) {
    const navigation = useSelector(selectNavigation);
    const [selectedNavigation, setSelectedNavigation] = useState([]);
    const classes = useStyles({...props, selectedNavigation});
    const [panelOpen, setPanelOpen] = useState(false);
    const theme = useTheme();
    const mdDown = useMediaQuery(theme.breakpoints.down('md'));
    const dispatch = useDispatch();
    const contrastTheme = useSelector(selectContrastMainTheme(theme.palette.primary.main));
    const location = useLocation();
    const appContext = useContext(AppContext);
    const {routes} = appContext;
    const sidebarFolded = useSelector(mapSelectors.selectSidebarFolded);


    useEffect(() => {

        navigation &&
        navigation.forEach(item => {
            needsToBeOpened(location, item) && setSelectedNavigation([item]);
        });
    }, [navigation, location]);

    function handleParentItemClick(selected) {
        if (selected.href) {
            window.open(selected.href)
            return;
        }

        if (selected.url) {
            history.push(selected.url);
            sidebarFolded && dispatch(mapActions.sidebarToggle());
            return;
        }

        /** if there is no child item do not set/open panel
         */
        if (!selected.children) {
            setSelectedNavigation([]);
            setPanelOpen(false);
            return;
        }

        /**
         * If navigation already selected toggle panel visibility
         */
        if (selectedNavigation[0]?.id === selected.id) {
            setPanelOpen(!panelOpen);
        } else {
            /**
             * Set navigation and open panel
             */
            setSelectedNavigation([selected]);
            setPanelOpen(true);
        }
    }

    function handleChildItemClick(selected) {
        setPanelOpen(false);
        mdDown && dispatch(navbarCloseMobile());
    }

    return (
        <ClickAwayListener onClickAway={() => setPanelOpen(false)}>
            <div className={clsx('flex flex-auto flex h-full', classes.root, props.className)}>
                <ThemeProvider theme={contrastTheme}>
                    <div
                        id="fuse-navbar-side-panel"
                        className={clsx(classes.sidePanel, 'flex flex-shrink-0 flex-col items-center')}
                    >
                        <a href="/" className="cursor-pointer">
                            <img className={clsx("w-44 my-16", classes.logo)}
                                 src={process.env.PUBLIC_URL + "/assets/images/logos/logo.ico"} alt="logo"/></a>

                        <FuseScrollbars
                            className="flex flex-1 min-h-0 justify-center w-full overflow-y-auto overflow-x-hidden"
                            option={{suppressScrollX: true, wheelPropagation: false}}
                        >
                            <FuseNavigation
                                className={clsx('navigation')}
                                navigation={navigation}
                                layout="vertical-2"
                                onItemClick={handleParentItemClick}
                                firstLevel
                                selectedId={selectedNavigation[0]?.id}
                                dense={props.dense}
                            />
                        </FuseScrollbars>
                    </div>
                </ThemeProvider>

                <FuseScrollbars
                    id="fuse-navbar-panel"
                    className={clsx(
                        classes.panel,
                        panelOpen && 'opened',
                        'overflow-y-auto overflow-x-hidden'
                    )}
                    option={{suppressScrollX: true, wheelPropagation: true}}
                >
                    <FuseSuspense>{renderRoutes(routes)}</FuseSuspense>
                </FuseScrollbars>
            </div>
        </ClickAwayListener>
    );
}

export default memo(NavbarStyle3Content);
