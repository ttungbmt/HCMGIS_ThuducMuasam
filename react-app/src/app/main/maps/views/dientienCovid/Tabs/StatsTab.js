import {memo, useMemo} from 'react';
import Table from "app/components/Table";

function StatsTab({data, view}) {
    const columns = useMemo(
        () => view === 'diadiem' ? [
            {Header: 'Ngày nhận thông tin', accessor: 'date'},
            {Header: 'Số ca', accessor: 'value'},
        ] : [
            {Header: 'Phường xã', accessor: 'label', width: 250},
            {Header: 'Số ca', accessor: 'count'},
        ],
        [view]
    );

    return <Table columns={columns} data={data}/>;
}

export default memo(StatsTab)