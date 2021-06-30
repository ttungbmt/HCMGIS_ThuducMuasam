import Icon from '@material-ui/core/Icon';
import IconButton from '@material-ui/core/IconButton';
import { useDispatch } from 'react-redux';
import { toggleQuickPanelLayout } from './store/stateSlice';
import Tooltip from "app/components/Tooltip";

function QuickPanelToggleButton({tooltip, children, layout}) {
	const dispatch = useDispatch();
	return (
		<Tooltip content={tooltip}>
			<IconButton className="w-40 h-40" onClick={ev => dispatch(toggleQuickPanelLayout(layout.name))}>
				{children}
			</IconButton>
		</Tooltip>
	);
}

QuickPanelToggleButton.defaultProps = {
	children: <Icon>bookmarks</Icon>
};

export default QuickPanelToggleButton;
