import React, { useEffect, useState } from "react";
import { Grid } from "@shopify/polaris";
import { MobileVerticalDotsMajor } from "@shopify/polaris-icons";
import { Icon } from "@shopify/polaris";
import { DuplicateMinor, CircleTickMinor } from "@shopify/polaris-icons";
import styles from "./index.module.css";
import { createURL } from "../../utilis/global";
import Loading from "../Loading";

const StoryCard = ({
    story,
    popUp,
    onShowHandler,
    storeName,
    deleteHandler,
    boxRef,
    loading,
}) => {
    const [copied, setCopied] = useState(false);
    const [targetURL, setTargetURL] = useState("");

    useEffect(() => {
        let interval;
        if (copied) {
            interval = setTimeout(() => {
                setCopied(false);
            }, 5000);
        }

        return () => clearInterval(interval);
    }, [copied]);

    const copyHanlder = async (id, story_id) => {
        const url = await createURL(storeName, id, story_id);
        navigator.clipboard.writeText(url);
        setCopied(true);
    };
    const openInNewTab = async (id, story_id) => {
        const url = await createURL(storeName, id, story_id);
        navigator.clipboard.writeText(url);
        setTargetURL(url);
    };

    return (
        <Grid.Cell columnSpan={{ sm: 4, md: 6 }}>
            <div
                style={{ marginTop: "5px" }}
                className="entry-point-card-container2"
            >
                <img
                    src={`${story?.featured_image}`}
                    className="entry-point-card-img"
                    alt="A cat"
                />
                <div className="author-container">
                    <div className="logo-container">
                        <div className="logo-ring"></div>
                        <img
                            className="entry-point-card-logo"
                            src={`${story?.logo_image}`}
                            alt="Publisher logo"
                        />
                    </div>
                    <span className="entry-point-card-subtitle">
                        {story?.title}
                    </span>
                </div>
                <div className="card-headline-container_admin">
                    <div className="menu-container">
                        {popUp.status && popUp.id === story?.id ? (
                            <ul className="dropdown dropDownBox" ref={boxRef}>
                                <li
                                    onClick={() =>
                                        openInNewTab(story?.id, story?.shop_id)
                                    }
                                >
                                    <a
                                        href={targetURL ?? targetURL}
                                        target="_blank"
                                    >
                                        Open in new tab
                                    </a>
                                </li>
                                <li
                                    className={styles.copyClipBoard}
                                    onClick={() =>
                                        copyHanlder(story?.id, story?.shop_id)
                                    }
                                >
                                    Copy Story URL
                                    <Icon
                                        source={
                                            copied
                                                ? CircleTickMinor
                                                : DuplicateMinor
                                        }
                                        color={copied ? "success" : "base"}
                                    />
                                </li>
                                <li
                                    onClick={() =>
                                        deleteHandler(story?.id, story?.shop_id)
                                    }
                                >
                                    {loading ? (
                                        <Loading
                                            color={`rgba(0, 128, 96, 1)`}
                                        />
                                    ) : (
                                        "Delete Story"
                                    )}
                                </li>
                            </ul>
                        ) : null}
                    </div>
                    <button
                        onClick={() => onShowHandler(story?.id)}
                        className="tab1CrossButton"
                    >
                        <Icon color="#ffff" source={MobileVerticalDotsMajor} />
                    </button>
                </div>
            </div>
        </Grid.Cell>
    );
};
export default StoryCard;
