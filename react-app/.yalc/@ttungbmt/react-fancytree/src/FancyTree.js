import React, { memo, useRef, useEffect } from 'react';
import clsx from 'clsx';
import _ from 'lodash';
import $ from 'jquery';
import 'jquery-ui'
import 'jquery.fancytree'
import 'jquery.fancytree/dist/modules/jquery.fancytree.filter'
import 'jquery.fancytree/dist/modules/jquery.fancytree.glyph'
import {useTreeContext} from './TreeContext';

const events = ['blurTree', 'create', 'init', 'focusTree', 'restore', 'activate', 'beforeActivate', 'beforeExpand', 'beforeSelect', 'blur', 'click', 'collapse', 'createNode', 'dblclick', 'deactivate', 'expand', 'focus', 'keydown', 'keypress', 'lazyLoad', 'loadChildren', 'loadError', 'postProcess', 'modifyChild', 'renderNode', 'renderTitle', 'select'];

const toEventFuncs = (props) => _.chain(events).mapKeys((n) => n).mapValues((n) => (...args) => {
	let eventName = `on${_.upperFirst(n)}`;
	props[eventName] && props[eventName](...args);
}).value();

function useTreeElement(treeRef, props) {
	const { setTree } = useTreeContext()

	const options = _.omit(props.options, events) || {};
	useEffect(() => {
		let $el;

		if (treeRef.current) {
			let eventFns = toEventFuncs(props);

			$el = $(treeRef.current).fancytree({
				...eventFns,
				...options,
				renderNode: function(event, {node}) {
					let $el = $(node.span).find('.label-feature-count'),
						count = node.data.count

					if(count && !$el.length){
						$(node.span).append($(`<div class="label-feature-count">${count}</div>`));
					}
				},
			});

			setTree($.ui.fancytree.getTree(treeRef.current))
		}
	}, []);
}

const renderTree = (nodes, key) => {
	if (_.isArray(nodes))
		return nodes.map((node, key) => renderTree(node, key + 1))

	const { children, tooltip, title, count, data = {}, ...rest } = nodes
	const folder = _.isNil(rest.folder) ? _.isArray(children) && !_.isEmpty(children) : rest.folder

	return (
		<li
			key={key}
			id={'ft' + key}
			title={tooltip}
			className={clsx('relative', { folder })}
			data-json={JSON.stringify({...rest, ...data})}
		>
			{title}
			{/*<div className="absolute h-full top-0 right-0 ">
				{data.count && <span className="label-feature-count flex items-center px-4 py-2 text-white" style={{fontSize: 11, backgroundColor: '#29B6F6', borderRadius: 3}}>{data.count}</span>}
			</div>*/}
			{_.isArray(children) ? (
				<ul>
					{children.map((node1, key1) => renderTree(node1, key + '.' + (key1 + 1)))}
				</ul>
			) : null}
		</li>
	)
}

function FancyTree({ id, style, className, children, source, ...props }) {
	const treeRef = useRef(null);
	useTreeElement(treeRef, props);

	return (
		<div id={id} className={clsx(className)} style={style} ref={treeRef}>
			{children ? (
				children
			) : (
				<ul style={{ display: 'none' }}>
					{renderTree(source)}
				</ul>
			)}
		</div>
	);
}

FancyTree.defaultProps = {
	options: {
		checkbox: true, // Show check boxes
		selectMode: 3, // 1:single, 2:multi, 3:multi-hier
		quicksearch: true, // Navigate to next node by typing the first letters
		icon: true, // Display node icons
		clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)

		checkboxAutoHide: false, // Display check boxes on hover only
		disabled: false, // Disable control

		wide: {
			iconWidth: '3em', // Adjust this if @fancy-icon-width != "16px"
			iconSpacing: '0.5em', // Adjust this if @fancy-icon-spacing != "3px"
			labelSpacing: '0.1em', // Adjust this if padding between icon and label != "3px"
			levelOfs: '1.5em' // Adjust this if ul padding != "16px"
		},

		filter: {
			autoApply: true, // Re-apply last filter if lazy data is loaded
			autoExpand: false, // Expand all branches that contain matches while filtered
			counter: true, // Show a badge with number of matching child nodes near parent icons
			fuzzy: false, // Match single characters in order, e.g. 'fb' will match 'FooBar'
			hideExpandedCounter: true, // Hide counter badge if parent is expanded
			hideExpanders: false, // Hide expanders if all child nodes are hidden by filter
			highlight: true, // Highlight matches by wrapping inside <mark> tags
			leavesOnly: false, // Match end nodes only
			nodata: true, // Display a 'no data' status node if result is empty
			mode: 'dimm' // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
		},

		childcounter: {
			deep: true,
			hideZeros: true,
			hideExpanded: true
		},

		extensions: [
			'filter'
			// 'childcounter'
		]
	}
};

export default memo(FancyTree);