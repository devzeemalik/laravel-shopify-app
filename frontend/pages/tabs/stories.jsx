//@ts-check
import React, { useEffect, useRef, useState } from "react";
import { Icon, Page } from "@shopify/polaris";
import StoryCard from "../../components/story/storyCard";
// @ts-ignore
import Logo from "../../assets/logo.png";
// @ts-ignore
import styles from "./index.module.css";
import "../style.css";
import { SearchMajor } from "@shopify/polaris-icons";
import { useAuthenticatedFetch } from "../../hooks/index.js";
import Loading from "../../components/Loading";
import ValidatorMessage from "../../components/validator/validator";

export default function StoriesTab() {
    const fetch = useAuthenticatedFetch();
    const boxRef = useRef(null);
    const [stories, setStories] = useState([
        {
            body_html: "",
            created_at: "",
            description: "",
            featured_image: "",
            id: null,
            logo_image: "",
            shop_id: "",
            title: "",
        },
    ]);
    const [storeName, setStoreName] = useState();
    const [loading, setLoading] = useState(false);
    const [deleteLoading, setDeleteLoading] = useState(false);
    const [validation, setValidation] = useState({
        type: "",
        message: "",
    });
    const [counter, setCounter] = useState(3);
    const [filteredItems, setFilteredItems] = useState([]);
    const [search, setSearch] = useState("");
    const [deletedItem, setDeletedItem] = useState(false);

    const [popUp, setPopUp] = useState({
        status: false,
        id: "",
    });

    useEffect(() => {
        var mounted = true;
        setLoading(true);
        (async () => {
            if (mounted) {
                try {
                    const response = await fetch("/api/home");
                    const data = await response.json();

                    if (data.status === "success") {
                        setDeletedItem(false);
                        setDeleteLoading(false);
                        setLoading(false);
                        setStories(data?.data.stories);
                        setStoreName(data?.data?.shop_name);
                    }
                } catch (error) {
                    setDeletedItem(false);
                    setDeleteLoading(false);
                    setLoading(false);
                    setStories([]);

                    setValidation({
                        type: "error",
                        message:
                            "Something went wrong, unable to load Stories..!",
                    });
                }
            }
        })();
        return () => {
            mounted = false;
        };
    }, [deletedItem]);

    const showModel = (id) => {
        setPopUp({
            status: popUp.id == id ? !popUp.status : true,
            id: id,
        });
    };

    useEffect(() => {
        if (stories && stories.length > 0) {
            const filteredStory = stories?.filter(
                (story) =>
                    story?.title &&
                    story?.title.toLowerCase().includes(search.toLowerCase()) &&
                    story
            );

            // @ts-ignore
            setFilteredItems(filteredStory);
        }
    }, [search, stories]);

    const deleteHandler = async (id, story_id) => {
        setDeleteLoading(true);
        const obj = {
            id: id.toString(),
            shop_id: story_id.toString(),
        };

        try {
            const response = await fetch("/api/delete", {
                method: "POST",
                body: JSON.stringify(obj),
            });
            if (response.status === 200) {
                setDeletedItem(true);
            }
        } catch (error) {
            console.log("error", error);
            setValidation({
                type: "error",
                message: "Unable to Delete Story try later!",
            });
        }
    };

    function handleOutsideClick(event) {
        if (!event.target.closest(".dropDownBox")) {
            setPopUp({
                status: false,
                id: "",
            });
        }
    }
    useEffect(() => {
        if (popUp.status) {
            document.addEventListener("click", handleOutsideClick);
        } else {
            document.removeEventListener("click", handleOutsideClick);
        }
        return () => {
            document.removeEventListener("click", handleOutsideClick);
        };
    }, [popUp]);

    return (
        <Page fullWidth>
            <div className={styles.storyWrapper}>
                <div className={styles.storyLeftContainer}>
                    <div className={styles.LogoContainer}>
                        <img className={styles.imgLogo} src={Logo} />
                    </div>
                </div>

                <div className={styles.storyRightContainer}>
                    <div className={styles.headingContainer}>
                        <p>All Web Stories</p>
                        <div className={styles.storySearchContainer}>
                            <input
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search"
                                type="text"
                            />
                            <button className={styles.storySearchBtn}>
                                <Icon source={SearchMajor} />
                            </button>
                        </div>
                    </div>

                    <div className={styles.storyCardContainer}>
                        <p
                            onClick={() =>
                                stories.length && setCounter(stories.length)
                            }
                            className={styles.allStoriesHeader}
                        >
                            View All {stories.length} webstories
                        </p>

                        <div className={styles.storyValidationContainer}>
                            {loading && !deleteLoading && (
                                <Loading
                                    homeLoader={false}
                                    color={`rgba(0, 128, 96, 1)`}
                                />
                            )}
                            {validation?.message.length > 3 && (
                                <ValidatorMessage
                                    validation={validation}
                                    setValidation={(data) =>
                                        setValidation(data)
                                    }
                                />
                            )}
                        </div>

                        <div className={styles.storyCardInnerContainer}>
                            {stories.length > 0
                                ? filteredItems &&
                                  filteredItems?.map(
                                      (story, index) =>
                                          index < counter && (
                                              <div
                                                  // @ts-ignore
                                                  key={story.id}
                                                  style={{
                                                      marginTop: "5%",
                                                  }}
                                              >
                                                  <StoryCard
                                                      deleteHandler={(
                                                          id,
                                                          shop_id
                                                      ) =>
                                                          deleteHandler(
                                                              id,
                                                              shop_id
                                                          )
                                                      }
                                                      loading={deleteLoading}
                                                      storeName={storeName}
                                                      popUp={popUp}
                                                      story={story}
                                                      onShowHandler={(data) =>
                                                          showModel(data)
                                                      }
                                                      boxRef={boxRef}
                                                  />
                                              </div>
                                          )
                                  )
                                : null}
                        </div>
                    </div>
                </div>
            </div>
        </Page>
    );
}
