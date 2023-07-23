//@ts-check

import React from "react";
// @ts-ignore
import style from "./index.module.css";

const Loading = ({ color, homeLoader }) => {
    const styles = { background: `${color}` };

    return (
        <div className={!homeLoader ? style.lds_ellipsis : style.homeLoaders}>
            <div style={styles}></div>
            <div style={styles}></div>
            <div style={styles}></div>
            <div style={styles}></div>
        </div>
    );
};

export default Loading;
