import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Share Tech Mono", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: {
                    DEFAULT: "#2E2C2F",
                    50: "#C7C5C8",
                    100: "#BDBABE",
                    200: "#A9A5AB",
                    300: "#959097",
                    400: "#807B83",
                    500: "#6C676E",
                    600: "#575359",
                    700: "#434044",
                    800: "#2E2C2F",
                    850: "#202021",
                    900: "#121112",
                    950: "#030304",
                },
                blue: {
                    DEFAULT: "#13182A",
                    50: "#B9BCDF",
                    100: "#9DA1D3",
                    200: "#737BBF",
                    300: "#5662B3",
                    400: "#46529A",
                    500: "#39447E",
                    600: "#2C3662",
                    700: "#202746",
                    800: "#13182A",
                    900: "#0E1220",
                    950: "#070910",
                },
            },
        },
    },

    plugins: [forms, typography],
};
