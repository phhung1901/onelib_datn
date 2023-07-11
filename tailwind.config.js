/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
  ],
  theme: {
      extend: {
          colors: {
              primary: "#00A888",
              "primary-darker": "#006350",
              "primary-lighter": "#00ecbf",
              secondary: "#FFAA00",
              "secondary-darker": "#cc8800",
              "secondary-lighter": "#ffbb32",
              warning: "#FFAA00",
              info: "#5D789B",
              success: "#00A888",
              danger: "#E13D34",
              error: "#E13D34",
              default: "#333333",
              "main-background": "#CBCCCC3C",
              "default-lighter": "#666666",
          },
          borderRadius: {
              "1.5lg": "0.625rem",
              "4xl": "1.75rem",
              "4.5xl": "1.875rem",
          },
          boxShadow: {
              hover: "5px 5px 8px #cccccc",
              around: '2px 0px 10px #00000033'
          },
          fontSize: {
              "3.25xl": ["2rem", "48px"],
          },
          screens: {
              lg: "1000px",
          },
          flex: {
              max: "1 0 max-content",
          },
          typography: {
              DEFAULT: {
                  css: {
                      maxWidth: "none",
                      color: "var(--color-text-default)",
                      fontWeight: "normal",
                      lineHeight: "1.5rem",
                      h1: {
                          fontSize: "1.5rem",
                          fontWeight: "600",
                          color: "var(--color-text-default)",
                      },
                      h2: {
                          fontWeight: "600",
                          color: "var(--color-text-default)",
                      },
                      h3: {
                          fontWeight: "500",
                          color: "var(--color-text-default)",
                      },
                      h4: {
                          fontWeight: "normal",
                          color: "var(--color-text-default)",
                      },
                      p: {
                          fontWeight: "normal",
                          color: "var(--color-text-default)",
                          marginTop: "0.6em",
                          marginBottom: "0.6em",
                      },
                      a: {
                          fontWeight: "normal",
                          color: "#0080FF",
                      },
                      img: {
                          display: "block",
                          marginLeft: "auto",
                          marginRight: "auto",
                          maxWidth: "500px",
                      },
                      figcaption: {
                          textAlign: "center",
                      },
                  },
              },
          },
          inset: {
              0: 0,
              "1/2": "50%",
          },
      },
  },
    plugins: [
        require('flowbite/plugin')
    ],
}

