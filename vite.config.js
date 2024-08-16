import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/js/app.js",
                "resources/css/app.css",
                "node_modules/material-dashboard/assets/css/material-dashboard.css", // Material Dashboard CSS
                "node_modules/material-dashboard/assets/js/material-dashboard.js", // Material Dashboard JS
            ],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        port: 1111, // Specify the port Vite should use
        hmr: {
            host: "10.15.8.57", // Replace with your local IP address
            port: 1111,
        },
    },
});
