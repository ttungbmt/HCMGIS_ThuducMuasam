import Dialog from "@material-ui/core/Dialog";
import React, {memo, useState} from "react";
import Alert from "@material-ui/lab/Alert";
import HighlightOffIcon from '@material-ui/icons/HighlightOff';
import IconButton from "@material-ui/core/IconButton";
import Box from "@material-ui/core/Box";

function ThongbaoDialog() {
    const [open, setOpen] = useState(true);

    const handleClose = () => {
        setOpen(false);
    };

    return (
        <Dialog onClose={handleClose} aria-labelledby="simple-dialog-title" open={open}>
            <Alert severity="error" >
                <Box className="flex">
                    <Box>Khuyến cáo người dân thực hiện việc mua sắm bằng hình thức trực tuyến trong thời gian này!</Box>
                    <IconButton className="text-red-400" edge="start" size="small" onClick={handleClose}>
                        <HighlightOffIcon/>
                    </IconButton>
                </Box>

            </Alert>
        </Dialog>
    )
}

export default memo(ThongbaoDialog)