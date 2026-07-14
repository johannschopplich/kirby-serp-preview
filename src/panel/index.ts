/* eslint-disable ts/ban-ts-comment */
// @ts-ignore - Vue component
import SerpPreview from "./components/SerpPreview.vue";
import "virtual:uno.css";

window.panel.plugin("johannschopplich/serp-preview", {
  sections: {
    "serp-preview": SerpPreview,
  },
});
