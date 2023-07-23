import React from "react";
import { MobileCancelMajor } from "@shopify/polaris-icons";
import { Icon } from "@shopify/polaris";

const ValidatorMessage = ({ validation, setValidation }) => {
    return (
        <div
            className={`tab1ErrorHandling ${
                validation?.type === "error" ? "errorMessage" : "sucessMessage"
            }`}
        >
            {validation.message}

            <button
                className="tab1CrossButton"
                onClick={() =>
                    setValidation({
                        type: "",
                        message: "",
                    })
                }
             
            >
                <Icon color="#ffff" source={MobileCancelMajor} />
            </button>
        </div>
    );
};
export default ValidatorMessage;
