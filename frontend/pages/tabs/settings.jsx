// @ts-check
import React, { useState } from "react";

import {
    Button,
    Checkbox,
    Form,
    FormLayout,
    Grid,
    Page,
    Select,
    TextField,
    Avatar,
    Thumbnail,
    Icon,
} from "@shopify/polaris";
import { useAuthenticatedFetch } from "../../hooks/index.js";

import { generateUUID } from "../../utilis/global";
import CustomButton from "../../components/button/button";
import ValidatorMessage from "../../components/validator/validator";
function Settings({ settings, setSettings, validation, setValidation }) {
    const [loading, setLoading] = useState(false);
    const fetch = useAuthenticatedFetch();

    const handleSubmit = async () => {
        setLoading(true);
        const formData = new FormData();

        for (const setting in settings) {
            formData.append(setting, settings[setting]);
        }

        fetch("/api/update/settings", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                setValidation({
                    type: "success",
                    message: "updated successfully!",
                });
                setLoading(false);
            })
            .catch((error) => {
                setLoading(false);
                setValidation({
                    type: "error",
                    message: "Something went wrong Not Updated!",
                });

                console.error("Error:", error);
            });
    };

    const options = [
        { label: "Left", value: "left" },
        { label: "Right", value: "right" },
    ];

    const generateToken = () => {
        setLoading(false);

        const token = generateUUID();

        setSettings({
            ...settings,
            api_token: token,
        });
    };

    return (
        <Page fullWidth>
            <Grid>
                <Grid.Cell columnSpan={{ md: 6, sm: 6, xs: 6 }}>
                    {validation?.message.length > 3 && (
                        <ValidatorMessage
                            validation={validation}
                            setValidation={(data) => setValidation(data)}
                        />
                    )}
                    <Form onSubmit={handleSubmit}>
                        <FormLayout>
                            <TextField
                                value={settings.api_token}
                                label="API Token:"
                                autoComplete="off"
                                onChange={(value) => {
                                    setSettings({
                                        ...settings,
                                        api_token: value,
                                    });
                                }}
                            />
                            <Button primary onClick={generateToken}>
                                Genrate New Token
                            </Button>
                            <Checkbox
                                label="Enable WebStories to display on Front Pages."
                                checked={settings.is_enabled}
                                onChange={(value) => {
                                    setSettings({
                                        ...settings,
                                        is_enabled: value == true ? 1 : 0,
                                    });
                                }}
                            />
                            <Select
                                label="Selection Position:"
                                options={options}
                                value={settings.widget_position}
                                onChange={(value) => {
                                    setSettings({
                                        ...settings,
                                        widget_position: value,
                                    });
                                }}
                            />
                            <Grid>
                                <Grid.Cell columnSpan={{ md: 6, sm: 6, xs: 6 }}>
                                    <div className="settingFileManager">
                                        <label>Upload Widget Icon</label>
                                        <input
                                            type="file"
                                            autoComplete="off"
                                            onChange={(e) => {
                                                e?.target?.files &&
                                                    setSettings({
                                                        ...settings,

                                                        file: e?.target
                                                            ?.files[0],
                                                    });
                                            }}
                                        />
                                    </div>
                                </Grid.Cell>

                                <Grid.Cell columnSpan={{ md: 6, sm: 6, xs: 6 }}>
                                    <Thumbnail
                                        alt="HelloWoofy"
                                        size="large"
                                        source={
                                            settings?.file
                                                ? URL.createObjectURL(
                                                      settings.file
                                                  )
                                                : settings?.widget_url
                                        }
                                    />
                                </Grid.Cell>
                            </Grid>

                            <CustomButton
                                loading={loading}
                                handleSubmit={handleSubmit}
                                title="Update"
                                btnColor={`rgba(0, 128, 96, 1)`}
                                loaderColor="#ffffff"
                                textColor=""
                            />
                        </FormLayout>
                    </Form>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}

export default Settings;
