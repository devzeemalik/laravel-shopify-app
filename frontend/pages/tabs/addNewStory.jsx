//@ts-check
import React, { useState } from "react";

import { Button, Page, TextField, Grid } from "@shopify/polaris";

import { useAuthenticatedFetch } from "../../hooks/index.js";

// @ts-ignore
import styles from "./index.module.css";
import { PlusMinor, DeleteMinor } from "@shopify/polaris-icons";

import CustomButton from "../../components/button/button";
import ValidatorMessage from "../../components/validator/validator";

export default function AddNewStory() {
    const fetch = useAuthenticatedFetch();
    const [loading, setLoading] = useState(false);
    const [validation, setValidation] = useState({
        type: "",
        message: "",
    });
    const [webStory, setWebStory] = useState({
        title: "",
        Publisher: "",
        Poster: "",
        Description: "",
    });
    const [formFields, setFormFields] = useState([
        {
            pageTitle: "",
            pageDescription: "",
            pageImage: "",
            pageBtn: "",
        },
    ]);

    const handleFormChange = (event, index) => {
        let data = [...formFields];
        data[index][event.target.name] = event.target.value;
        setFormFields(data);
    };

    const addFields = () => {
        let object = {
            pageTitle: "",
            pageDescription: "",
            pageImage: "",
            pageBtn: "",
        };

        setFormFields([...formFields, object]);
    };

    const removeFields = (index) => {
        let data = [...formFields];
        data.splice(index, 1);
        setFormFields(data);
    };

    const handleSubmit = async () => {
        setLoading(true);

        const obj = {
            webStory: webStory,
            pages: formFields,
        };

        fetch("/api/add/story", {
            method: "POST",
            "Content-Type": "application/x-www-form-urlencoded",
            body: JSON.stringify(obj),
        })
            .then((response) => response.json())
            // @ts-ignore
            .then((data) => {
                setLoading(false);
                if (data?.status === "success") {
                    setValidation({
                        type: data?.status,
                        message: data?.message,
                    });
                    clearFormHandler();
                }
                if (data?.status === "error") {
                    setValidation({
                        type: data?.status,
                        message: data?.message,
                    });
                    clearFormHandler();
                }
            })
            .catch((error) => {
                setLoading(false);
                setValidation({
                    type: "error",
                    message: "Not Added Try Again!",
                });
                console.error("Error:", error);
            });
    };
    const clearFormHandler = () => {
        setFormFields([
            {
                pageTitle: "",
                pageDescription: "",
                pageImage: "",
                pageBtn: "",
                Description: "",
            },
        ]);
        setWebStory({
            title: "",
            Publisher: "",
            Poster: "",
            Description: "",
        });
    };
    return (
        <Page fullWidth>
            <Grid>
                <Grid.Cell columnSpan={{ md: 4, xs: 6, sm: 6 }}>
                    {validation?.message.length > 3 && (
                        <ValidatorMessage
                            validation={validation}
                            setValidation={(data) => setValidation(data)}
                        />
                    )}

                    <div>
                        <TextField
                            label="Title:"
                            value={webStory.title}
                            onChange={(e) =>
                                setWebStory({ ...webStory, title: e })
                            }
                            autoComplete="off"
                        />
                        <TextField
                            label="Publisher Logo Src:"
                            value={webStory.Publisher}
                            onChange={(e) =>
                                setWebStory({ ...webStory, Publisher: e })
                            }
                            autoComplete="off"
                        />
                        <TextField
                            label="Poster Potrait Src:"
                            value={webStory.Poster}
                            onChange={(e) =>
                                setWebStory({ ...webStory, Poster: e })
                            }
                            autoComplete="off"
                        />
                        <TextField
                            label="Description:"
                            value={webStory.Description}
                            onChange={(e) =>
                                setWebStory({ ...webStory, Description: e })
                            }
                            autoComplete="off"
                        />
                        {/* 4 fields below */}

                        <>
                            {formFields.map((item, index) => {
                                return (
                                    <div
                                        className={styles.pgContainer}
                                        key={index}
                                    >
                                        <div className={styles.tab3btnGroup}>
                                            <h3 className="Polaris-Subheading">
                                                Page-{index + 1}
                                            </h3>
                                            <Button
                                                submit
                                                onClick={() =>
                                                    removeFields(index)
                                                }
                                                destructive
                                                icon={DeleteMinor}
                                            ></Button>
                                        </div>

                                        <p className={styles.PageHeading}>
                                            Page Title:
                                        </p>
                                        <input
                                            className={styles.tab3Input}
                                            name="pageTitle"
                                            onChange={(event) =>
                                                handleFormChange(event, index)
                                            }
                                            value={item.pageTitle}
                                        />
                                        <p className={styles.PageHeading}>
                                            Page Description:
                                        </p>

                                        <input
                                            className={styles.tab3Input}
                                            name="pageDescription"
                                            onChange={(event) =>
                                                handleFormChange(event, index)
                                            }
                                            value={item.pageDescription}
                                        />
                                        <p className={styles.PageHeading}>
                                            Page Image:
                                        </p>

                                        <input
                                            className={styles.tab3Input}
                                            name="pageImage"
                                            onChange={(event) =>
                                                handleFormChange(event, index)
                                            }
                                            value={item.pageImage}
                                        />
                                        <p className={styles.PageHeading}>
                                            Button:
                                        </p>

                                        <input
                                            className={styles.tab3Input}
                                            name="pageBtn"
                                            onChange={(event) =>
                                                handleFormChange(event, index)
                                            }
                                            value={item.pageBtn}
                                        />
                                    </div>
                                );
                            })}
                        </>

                        <div className={styles.tab3btnGroup}>
                            <CustomButton
                                title="Create"
                                btnColor="#ffff"
                                handleSubmit={handleSubmit}
                                loaderColor="rgba(0, 128, 96, 1)"
                                loading={loading}
                                textColor="#000000"
                            />
                            <Button
                                submit
                                onClick={addFields}
                                primary
                                icon={PlusMinor}
                            >
                                Add Page
                            </Button>
                        </div>
                    </div>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}
