import Field from "./components/Field.vue";
import "virtual:uno.css";

window.panel.plugin("johannschopplich/serp-preview", {
  fields: {
    "serp-preview": Field,
  },
});
