<script>
import { joinURL } from "ufo";
import {
  computed,
  ref,
  useApi,
  usePanel,
  useSection,
  useStore,
  watch,
} from "kirbyuse";
import pThrottle from "p-throttle";
import { section } from "kirbyuse/props";

const propsDefinition = {
  ...section,
};

export default {
  inheritAttrs: false,
};
</script>

<script setup>
const props = defineProps(propsDefinition);

const panel = usePanel();
const api = useApi();
const store = useStore();
const { load } = useSection();

const label = ref();
const faviconUrl = ref();
const siteTitle = ref();
const siteUrl = ref();
const titleSeparator = ref();
const titleContentKey = ref();
const defaultTitle = ref();
const descriptionContentKey = ref();
const defaultDescription = ref();
const searchConsoleUrl = ref();
const config = ref();

// Local state
const previewUrl = ref("");
const titleProxy = ref("");
const descriptionProxy = ref("");

const currentContent = computed(() => store.getters["content/values"]());
const path = computed(() => {
  if (!previewUrl.value) return "";
  const url = new URL(previewUrl.value);
  return url.pathname;
});

const title = computed(
  () =>
    currentContent.value[titleContentKey.value] ||
    defaultTitle.value ||
    [panel.view.title, titleSeparator.value, siteTitle.value].join(" "),
);
const description = computed(
  () =>
    (descriptionContentKey.value
      ? currentContent.value[descriptionContentKey.value]
      : undefined) || defaultDescription.value,
);

watch(
  // Will be `null` in single language setups
  () => panel.language.code,
  async () => {
    // Update the section props when the language changes to
    // get a translated `defaultTitle` and `defaultDescription`
    loadSectionProps();
    // Update the path
    const data = await panel.api.get(panel.view.path, { select: "previewUrl" });
    previewUrl.value = data.previewUrl;
  },
  { immediate: true },
);

const throttle = pThrottle({
  limit: 1,
  interval: 250,
});
const throttledGetParsedTitle = throttle(async (value) => {
  titleProxy.value = await getParsedProperty("title", value);
});
const throttledGetParsedDescription = throttle(async (value) => {
  descriptionProxy.value = await getParsedProperty("description", value);
});

watch(title, (value) => {
  if (config.value?.parsers?.title) {
    throttledGetParsedTitle(value);
  }
});

watch(description, (value) => {
  if (config.value?.parsers?.description) {
    throttledGetParsedDescription(value);
  }
});

// loadSectionProps();

async function loadSectionProps() {
  const response = await load({
    parent: props.parent,
    name: props.name,
  });

  label.value =
    t(response.label) || panel.t("johannschopplich.serp-preview.label");
  faviconUrl.value = response.faviconUrl;
  siteTitle.value = response.siteTitle;
  siteUrl.value = response.siteUrl;
  titleSeparator.value = response.titleSeparator;
  titleContentKey.value = response.titleContentKey;
  defaultTitle.value = response.defaultTitle;
  descriptionContentKey.value = response.descriptionContentKey;
  defaultDescription.value = response.defaultDescription;
  searchConsoleUrl.value = response.searchConsoleUrl;
  config.value = response.config;
}

function t(value) {
  if (!value || typeof value === "string") return value;
  return value[panel.translation.code] ?? Object.values(value)[0];
}

async function getParsedProperty(prop, value) {
  const pageId =
    panel.view.path === "site"
      ? undefined
      : // Replace leading `pages/`
        panel.view.path.slice(6).replaceAll("+", "/");

  const { text } = await api.post(`__serp-preview__/parse/${prop}`, {
    pageId,
    value,
  });

  return text;
}
</script>

<template>
  <k-section :label="label">
    <div
      class="ksp-overflow-hidden ksp-rounded-[var(--input-rounded)] ksp-bg-[var(--input-color-back)] ksp-p-4"
    >
      <div class="ksp-mb-2 ksp-flex ksp-items-center ksp-gap-3">
        <figure
          v-if="faviconUrl"
          class="ksp-inline-flex ksp-aspect-square ksp-h-[26px] ksp-w-[26px] ksp-items-center ksp-justify-center ksp-rounded-full ksp-border ksp-border-solid ksp-border-[#ecedef] ksp-bg-[#f1f3f4]"
        >
          <img
            class="ksp-block ksp-h-[18px] ksp-w-[18px]"
            :src="faviconUrl"
            alt=""
          />
        </figure>
        <div class="ksp-flex ksp-flex-col">
          <span class="ksp-text-sm ksp-text-[#4d5156]">{{ siteTitle }}</span>
          <span class="ksp-line-clamp-1 ksp-text-xs ksp-text-[#4d5156]">{{
            joinURL(siteUrl, path)
          }}</span>
        </div>
      </div>

      <h3 class="ksp-line-clamp-1 ksp-text-xl ksp-text-[#1a0dab]">
        {{ titleProxy || title }}
      </h3>

      <p
        v-show="description"
        class="ksp-mt-1 ksp-line-clamp-2 ksp-text-sm ksp-text-[#4d5156]"
      >
        {{ descriptionProxy || description }}
      </p>
    </div>

    <k-button-group v-show="searchConsoleUrl" class="ksp-mt-2 ksp-w-full">
      <k-button :link="searchConsoleUrl" icon="open" target="_blank">
        Google Search Console
      </k-button>
    </k-button-group>
  </k-section>
</template>
