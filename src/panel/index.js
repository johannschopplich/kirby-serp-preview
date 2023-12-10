import SerpPreview from "./components/SerpPreview.vue";
import "./index.css";

window.panel.plugin("johannschopplich/serp-preview", {
  sections: {
    "serp-preview": SerpPreview,
  },
});
