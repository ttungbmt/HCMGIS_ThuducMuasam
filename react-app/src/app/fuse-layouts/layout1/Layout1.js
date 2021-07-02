import FuseDialog from '@fuse/core/FuseDialog';
import FuseMessage from '@fuse/core/FuseMessage';
import FuseSuspense from '@fuse/core/FuseSuspense';
import {makeStyles, ThemeProvider} from '@material-ui/core/styles';
import AppContext from 'app/AppContext';
import SettingsPanel from 'app/fuse-layouts/shared-components/SettingsPanel';
import clsx from 'clsx';
import React, {memo, useContext} from 'react';
import {useSelector} from 'react-redux';
import {renderRoutes} from 'react-router-config';
import FooterLayout1 from './components/FooterLayout1';
import LeftSideLayout1 from './components/LeftSideLayout1';
import NavbarWrapperLayout1 from './components/NavbarWrapperLayout1';
import RightSideLayout1 from './components/RightSideLayout1';
import ToolbarLayout1 from './components/ToolbarLayout1';
import MapContent from './components/map/MapContent';
import {useFetchApp} from 'app/hooks/useFetchApp';
import MapRightContent from "./components/map/MapRightContent";
import {SearchBarProvider} from "../../components/SearchBar";
import {PageProvider} from '../PageContext'
import {MFormProvider} from '@form'
import {Button} from "@material-ui/core";
import $ from 'jquery'
import {get} from "lodash";
import Typography from "@material-ui/core/Typography";
import Box from "@material-ui/core/Box";
import Hidden from "@material-ui/core/Hidden";
import ThongbaoDialog from "app/main/maps/views/nearBy/ThongbaoDialog";
import HotlineShopping from "./components/HotlineShopping";

const useStyles = makeStyles(theme => ({
    root: {
        '&.boxed': {
            clipPath: 'inset(0)',
            maxWidth: props => `${props.config.containerWidth}px`,
            margin: '0 auto',
            boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)'
        },
        '&.container': {
            '& .container': {
                maxWidth: props => `${props.config.containerWidth}px`,
                width: '100%',
                margin: '0 auto'
            }
        }
    }
}));

function Layout1(props) {
    const config = useSelector(({fuse}) => fuse.settings.current.layout.config);
    const appContext = useContext(AppContext);
    const {routes} = appContext;
    const classes = useStyles({...props, config});
    const { folded } = config.navbar;
    useFetchApp();

	const appName = get(window.builder, 'app.name', 'Map App')


    return (
        <MFormProvider>
            <ThongbaoDialog />

            <SearchBarProvider>
                <div id="fuse-layout" className={clsx(classes.root, config.mode, 'w-full flex')}>
                    {config.leftSidePanel.display && <LeftSideLayout1/>}

                    <div className="flex flex-auto min-w-0">
                        {config.navbar.display && config.navbar.position === 'left' && <NavbarWrapperLayout1/>}

                        <main id="fuse-main" className="flex flex-col flex-auto min-h-screen min-w-0 relative z-10">
                            {config.toolbar.display && (
                                <ToolbarLayout1 className={config.toolbar.style === 'fixed' && 'sticky top-0'}/>
                            )}

                            {/*<div className="sticky top-0 z-99">
							<SettingsPanel />
							</div>*/}

                            <div className="flex flex-col flex-auto min-h-0 relative z-10 h-full">
                                <FuseDialog/>
                                {/*<FuseSuspense>{renderRoutes(routes)}</FuseSuspense>*/}
                                <Box className="relative h-full">
                                    <MapContent/>
                                    <HotlineShopping />
                                </Box>
                                {props.children}
                                {!folded && (
                                    <Hidden lgUp>
                                        <div className="flex justify-center align-center" style={{height: 60, background: 'url(https://maps.hcmgis.vn/core/themes/maps/assets/img/bando_02.png) no-repeat 150px 0 #f9f9f9'}}>
                                            <div className="self-center">
                                                <Typography variant="h5" className="uppercase font-semibold" style={{color: '#0D9FE6'}}>
                                                    {appName}
                                                </Typography>
                                            </div>
                                        </div>
                                    </Hidden>
                                )}
                            </div>

                            {config.footer.display && (
                                <FooterLayout1 className={config.footer.style === 'fixed' && 'sticky bottom-0'}/>
                            )}
                        </main>

                        {/*<MapRightContent />*/}

                        {config.navbar.display && config.navbar.position === 'right' && <NavbarWrapperLayout1/>}
                    </div>

                    {config.rightSidePanel.display && <RightSideLayout1/>}
                    <FuseMessage/>
                </div>
            </SearchBarProvider>
        </MFormProvider>
    );
}

export default memo(Layout1);
