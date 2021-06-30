import constate from "constate";
import {useState} from "react";

function usePage() {
    const [data, setData] = useState({
        dientienCovid: {

        }
    });
    return { data };
}

export const [PageProvider, usePageContext] = constate(usePage);