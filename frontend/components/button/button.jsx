//@ts-check
import React from "react";
import Loading from "../Loading/index";

const CustomButton = ({
    title,
    btnColor,
    loaderColor,
    handleSubmit,
    loading,
    textColor,
}) => {
    const styles = { backgroundColor: btnColor, color: textColor };
    const keys = Math.floor(Math.random() * (999 - 100 + 1) + 100);
    return (
        <button
            key={keys}
            className="Tab1Update"
            style={styles}
            onClick={handleSubmit}
        >
            {loading ? (
                <Loading homeLoader={false} color={loaderColor} />
            ) : (
                title
            )}
        </button>
    );
};
export default CustomButton;
