import {useTable} from "react-table";
import {Table as UITable, TableBody, TableCell, TableHead, TableRow} from "@material-ui/core";
import {memo} from "react";

function Table({ columns, data }) {
    const { getTableProps, headerGroups, rows, prepareRow } = useTable({
        columns,
        data,
    });

    return (
        <UITable {...getTableProps()}>
            <TableHead>
                {headerGroups.map(headerGroup => (
                    <TableRow {...headerGroup.getHeaderGroupProps()}>
                        {headerGroup.headers.map(column => {
                            let style = {}
                            if(column.width) style.width = column.width
                            return <TableCell style={style} {...column.getHeaderProps()}>{column.render('Header')}</TableCell>
                        })}
                    </TableRow>
                ))}
            </TableHead>
            <TableBody>
                {rows.map((row, i) => {
                    prepareRow(row);
                    return (
                        <TableRow {...row.getRowProps()}>
                            {row.cells.map(cell => {
                                return <TableCell {...cell.getCellProps()}>{cell.render('Cell')}</TableCell>;
                            })}
                        </TableRow>
                    );
                })}
            </TableBody>
        </UITable>
    );
}

export default memo(Table)
