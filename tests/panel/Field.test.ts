// @vitest-environment happy-dom
import { shallowMount } from "@vue/test-utils";
import { expect, it, vi } from "vitest";
import { defineComponent, h } from "vue";
import Field from "../../src/panel/components/Field.vue";

const currentContent = vi.hoisted(() => ({
  value: {} as Record<string, unknown>,
}));

vi.mock("kirbyuse", () => ({
  useContent: () => ({ currentContent }),
  useHelpers: () => ({ debounce: (callback: unknown) => callback }),
  usePanel: () => ({
    api: { post: vi.fn() },
    view: { title: "Home | Preview" },
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

it("titles the snippet with currentContent.title over panel.view.title", () => {
  currentContent.value = { title: "Home" };
  const KField = defineComponent(
    (_, { slots }) =>
      () =>
        h("div", slots.default?.()),
  );

  const wrapper = shallowMount(Field, {
    props: {
      name: "serp",
      label: "SERP Preview",
      siteUrl: "https://example.com",
      siteTitle: "Example",
      titleSeparator: "|",
      previewPath: "/",
    },
    global: { stubs: { "k-button": true, "k-field": KField } },
  });

  expect(wrapper.find("h3").text()).toBe("Home | Example");
});
