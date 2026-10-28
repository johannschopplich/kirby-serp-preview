// @vitest-environment happy-dom
import { mount } from "@vue/test-utils";
import { expect, it, vi } from "vitest";
import { defineComponent, h, ref } from "vue";
import SerpPreview from "../../src/panel/components/SerpPreview.vue";

vi.mock("kirbyuse", () => ({
  useApi: () => ({ post: vi.fn() }),
  useContent: () => ({ currentContent: ref({}) }),
  usePanel: () => ({ view: { title: "Home" }, t: (key: string) => key }),
}));

// Mirrors Kirby's `k-field`, which renders its `disabled` prop as `data-disabled`.
const KField = defineComponent({
  props: { disabled: Boolean },
  setup:
    (props, { slots }) =>
    () =>
      h("div", { "data-disabled": props.disabled }, slots.default?.()),
});

it("keeps disabled off k-field", () => {
  const wrapper = mount(SerpPreview, {
    props: {
      name: "serp",
      label: "SERP Preview",
      siteUrl: "https://example.com",
      previewPath: "/",
    },
    attrs: { disabled: true },
    global: { stubs: { "k-button": true, "k-field": KField } },
  });

  expect(wrapper.find('[data-disabled="true"]').exists()).toBe(false);
});
