// @ts-check
import React, { useState, useCallback, useEffect } from "react";

import { Card, Tabs } from "@shopify/polaris";

import SettingTab from "./tabs/settings";
import StoriesTab from "./tabs/stories.jsx";
import AddNewStoryTab from "./tabs/addNewStory.jsx";

import { useAuthenticatedFetch } from "../hooks/index.js";
import HomeLoading from "../components/Loading/index";

export default function HomePage() {
    const fetch = useAuthenticatedFetch();

    const [selected, setSelected] = useState(0);

    const [settings, setSettings] = useState({});
    const [loading, setLoading] = useState(false);
    const [validation, setValidation] = useState({
        type: "",
        message: "",
    });
    const handleTabChange = useCallback(
        (selectedTabIndex) => setSelected(selectedTabIndex),
        []
    );

    const tabs = [
        {
            id: "all-customers-1",
            content: "Setting",
            accessibilityLabel: "All customers",
            panelID: "all-customers-content-1",
        },
        {
            id: "accepts-marketing-1",
            content: "Webstories",
            panelID: "accepts-marketing-content-1",
        },
        {
            id: "add-WebStory-1",
            content: "Add New WebStory",
            panelID: "add-WebStory-content-1",
        },
    ];

    useEffect(() => {
        var mounted = true;
        setLoading(true);

        (async () => {
            if (mounted) {
                try {
                    const response = await fetch("/api/home");
                    const data = await response.json();

                    if (data.status === "success") {
                        setSettings(data.data.settings);
                        setLoading(false);
                    }
                } catch (error) {
                    setValidation({
                        type: "error",
                        message: "Something went wrong, unable to load data..!",
                    });
                    setLoading(false);
                }
            }
        })();
        return () => {
            mounted = false;
        };
    }, []);
    const settingHandler = (data) => {
        setSettings(data);
    };

    return (
        <>
            {loading ? (
                <div className="homeLoadingContainer">
                    <HomeLoading
                        homeLoader={true}
                        color={`rgba(0, 128, 96, 1)`}
                    />
                </div>
            ) : (
                <Card>
                    <Tabs
                        tabs={tabs}
                        selected={selected}
                        onSelect={handleTabChange}
                    >
                        <Card.Section title={tabs[selected].content}>
                            {selected === 0 && (
                                <SettingTab
                                    setValidation={(data) =>
                                        setValidation(data)
                                    }
                                    validation={validation}
                                    setSettings={settingHandler}
                                    settings={settings ?? settings}
                                />
                            )}
                            {selected === 1 && <StoriesTab />}
                            {selected === 2 && <AddNewStoryTab />}
                        </Card.Section>
                    </Tabs>
                </Card>
            )}
        </>
    );
}
