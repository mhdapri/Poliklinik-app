import "./bootstrap";

if (import.meta.env.VITE_PUSHER_APP_KEY && import.meta.env.VITE_PUSHER_APP_CLUSTER) {
    import("pusher-js").then(({ default: Pusher }) => {
        window.Pusher = Pusher;
        window.Echo = new (require("laravel-echo"))({
            broadcaster: "pusher",
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });
    });
}
