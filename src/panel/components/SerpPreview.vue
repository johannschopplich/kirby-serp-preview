<script>
import {
  computed,
  ref,
  useApi,
  useContent,
  useI18n,
  usePanel,
  useSection,
  watch,
} from "kirbyuse";
import { section } from "kirbyuse/props";
import pThrottle from "p-throttle";
import { joinURL } from "ufo";

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
const { t } = useI18n();
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

const { currentContent } = useContent();
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
  () => {
    // Update the section props when the language changes to
    // re-evaluate all queries
    updateSectionData();
  },
);

const throttle = pThrottle({
  limit: 1,
  interval: 250,
});
const throttledFormatTitle = throttle(async (value) => {
  titleProxy.value = await formatProperty("title", value);
});
const throttledFormatDescription = throttle(async (value) => {
  descriptionProxy.value = await formatProperty("description", value);
});

watch(title, (value) => {
  if (config.value?.formatters?.title) {
    throttledFormatTitle(value);
  }
});

watch(description, (value) => {
  if (config.value?.formatters?.description) {
    throttledFormatDescription(value);
  }
});

updateSectionData(true);

async function updateSectionData(isInitializing = false) {
  const response = await load({
    parent: props.parent,
    name: props.name,
  });

  // Set values once that don't need to be re-evaluated on the server
  // when the language changes
  if (isInitializing) {
    label.value =
      t(response.label) || panel.t("johannschopplich.serp-preview.label");
    titleContentKey.value = response.titleContentKey;
    descriptionContentKey.value = response.descriptionContentKey;
    config.value = response.config;
    searchConsoleUrl.value = response.searchConsoleUrl;
  }

  faviconUrl.value = response.faviconUrl;
  siteTitle.value = response.siteTitle;
  siteUrl.value = response.siteUrl;
  titleSeparator.value = response.titleSeparator;
  defaultTitle.value = response.defaultTitle;
  defaultDescription.value = response.defaultDescription;

  // Update the path
  const data = await panel.api.get(panel.view.path, { select: "previewUrl" });
  previewUrl.value = data.previewUrl;
}

async function formatProperty(prop, value) {
  // Replace leading `pages/`
  const pageId = panel.view.path.startsWith("pages/")
    ? panel.view.path.slice(6).replaceAll("+", "/")
    : undefined;

  const { text } = await api.post(`__serp-preview__/format/${prop}`, {
    pageId,
    value,
  });

  return text;
}
</script>

<template>
  <k-section :label="label" class="k-serp-section">
    <div
      class="ksp-overflow-hidden ksp-rounded-[var(--input-rounded)] ksp-bg-[var(--input-color-back)] ksp-p-4"
    >
      <div class="ksp-mb-2 ksp-flex ksp-items-center ksp-gap-3">
        <figure
          v-if="faviconUrl"
          class="ksp-inline-flex ksp-aspect-square ksp-h-[26px] ksp-w-[26px] ksp-items-center ksp-justify-center ksp-rounded-full ksp-border ksp-border-solid ksp-border-[var(--serp-favicon-border)] ksp-bg-[var(--serp-favicon-background)]"
        >
          <img
            class="ksp-block ksp-h-[18px] ksp-w-[18px]"
            :src="faviconUrl"
            alt=""
          />
        </figure>
        <div class="ksp-flex ksp-flex-col">
          <span class="ksp-text-sm ksp-text-[var(--serp-color-text)]">{{
            siteTitle
          }}</span>
          <span
            class="ksp-line-clamp-1 ksp-text-xs ksp-text-[var(--serp-color-text)]"
            >{{ joinURL(siteUrl, path) }}</span
          >
        </div>
      </div>

      <h3
        class="ksp-line-clamp-1 ksp-text-xl ksp-text-[var(--serp-color-title)]"
      >
        {{ titleProxy || title }}
      </h3>

      <p
        v-show="description"
        class="ksp-mt-1 ksp-line-clamp-2 ksp-text-sm ksp-text-[var(--serp-color-text)]"
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

<style scoped>
.k-serp-section {
  --serp-favicon-background: light-dark(#f1f3f4, #fff);
  --serp-favicon-border: light-dark(#ecedef, #9aa0a6);
  --serp-color-title: light-dark(#1a0dab, #99c3ff);
  --serp-color-text: light-dark(#4d5156, #bfbfbf);
}
</style>
