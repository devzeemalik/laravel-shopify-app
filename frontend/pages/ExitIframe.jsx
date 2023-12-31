//@ts-check
import { Redirect } from "@shopify/app-bridge/actions";
import { useAppBridge, Loading } from "@shopify/app-bridge-react";
import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import React from "react";

export default function ExitIframe() {
    const app = useAppBridge();
    const { search } = useLocation();

    useEffect(() => {
        if (!!app && !!search) {
            const params = new URLSearchParams(search);
            const redirectUri = params.get("redirectUri");
            const url = redirectUri && new URL(decodeURIComponent(redirectUri));

            if (url && url.hostname === location.hostname) {
                const redirect = Redirect.create(app);
                redirectUri &&
                    redirect.dispatch(
                        Redirect.Action.REMOTE,
                        decodeURIComponent(redirectUri)
                    );
            }
        }
    }, [app, search]);

    return <Loading />;
}
