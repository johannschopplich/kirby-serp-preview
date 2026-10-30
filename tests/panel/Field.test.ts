// @vitest-environment happy-dom
import { shallowMount } from "@vue/test-utils";
import { expect, it, vi } from "vitest";
import { ref } from "vue";
import Field from "../../src/panel/components/Field.vue";

vi.mock("kirbyuse", () => ({
  useContent: () => ({ currentContent: ref({}) }),
  usePanel: () => ({
    api: { post: vi.fn() },
    view: { title: "Home" },
    t: (key: string) => key,
  }),
}));

it("keeps disabled off k-field", () => {
  const wrapper = shallowMount(Field, {
    props: {
      name: "serp",
      label: "SERP Preview",
      siteUrl: "https://example.com",
      previewPath: "/",
    },
    attrs: { disabled: true },
    global: { stubs: { "k-button": true, "k-field": true } },
  });

  expect(wrapper.attributes("disabled")).toBeUndefined();
});
