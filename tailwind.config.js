/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx,html,php}", "./*.{html,js,jsx,ts,tsx,php}"],
  theme: {
    extend: {
      colors: {
        primary: "#45671E",
        secondary: "#73AC32",
      },
      fontFamily: {
        sans: ["Inter", "sans-serif"],
      },
      fontWeight: {
        normal: "400",
      },
    },
  },
  plugins: [],
};
