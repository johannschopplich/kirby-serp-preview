import SerpPreview from "./components/SerpPreview.vue";
import "./index.css";

window.panel.plugin("johannschopplich/website", {
  sections: {
    "serp-preview": SerpPreview,
  },
});
