import FuseScrollbars from '@fuse/core/FuseScrollbars';
import SwipeableDrawer from '@material-ui/core/SwipeableDrawer';
import { makeStyles } from '@material-ui/core/styles';
import withReducer from 'app/store/withReducer';
import { useDispatch, useSelector } from 'react-redux';
import { memo } from 'react';
import { toggleQuickPanel } from './store/stateSlice';
import reducer from './store';
import Box from "@material-ui/core/Box";
import IconButton from "@material-ui/core/IconButton";
import Icon from "@material-ui/core/Icon";
import BasemapLayout from "./BasemapLayout";
import LegendLayout from "./LegendLayout";
import {selectLayout} from "./store/dataSlice";

const useStyles = makeStyles(theme => ({
	root: props => ({
		width: props.width || 200,
	}),
}));

const Layouts = {
	basemap: BasemapLayout,
	legend: LegendLayout
}

function QuickPanel() {
	const dispatch = useDispatch();
	const state = useSelector(({ quickPanel }) => quickPanel.state);
	const layout = useSelector(selectLayout);

	const classes = useStyles({width: layout.width});

	const Layout = Layouts[layout.name]

	return (
		<SwipeableDrawer
			classes={{ paper: classes.root }}
			open={state}
			anchor="right"
			variant="persistent"
			onOpen={ev => {}}
			onClose={ev => dispatch(toggleQuickPanel())}
			disableSwipeToOpen
		>
			<FuseScrollbars>
				<Box m={1.5}>
					{state && <Layout closeBtn={(
						<IconButton style={{width: 10, height: 10}} onClick={ev => dispatch(toggleQuickPanel())}>
							<Icon className="text-16">close</Icon>
						</IconButton>
					)}/>}
				</Box>
			</FuseScrollbars>
		</SwipeableDrawer>
	);
}

export default withReducer('quickPanel', reducer)(memo(QuickPanel));
