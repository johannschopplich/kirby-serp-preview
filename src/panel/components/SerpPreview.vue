<script lang="ts">
import type { SectionData } from "../types";
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

<script setup lang="ts">
const props = defineProps(propsDefinition);

const panel = usePanel();
const api = useApi();
const { t } = useI18n();
const { load } = useSection();
const { currentContent } = useContent();

// Single source of truth for the server-provided section data
const data = ref<SectionData>({});

// Derived view state
const label = computed(
  () => t(data.value.label) || panel.t("johannschopplich.serp-preview.label"),
);
const config = computed(() => data.value.config);

const path = computed(() => {
  const { previewUrl } = data.value;
  if (!previewUrl) return "";

  try {
    return new URL(previewUrl).pathname;
  } catch {
    // Ignore malformed preview URLs and fall back to the site root
    return "";
  }
});

const title = computed(
  () =>
    currentContent.value[data.value.titleContentKey!] ||
    data.value.defaultTitle ||
    [panel.view.title, data.value.titleSeparator, data.value.siteTitle].join(
      " ",
    ),
);
const description = computed(
  () =>
    (data.value.descriptionContentKey
      ? currentContent.value[data.value.descriptionContentKey]
      : undefined) || data.value.defaultDescription,
);

// Formatted proxies, populated only when a server-side formatter exists
const titleProxy = ref("");
const descriptionProxy = ref("");

const throttle = pThrottle({
  limit: 1,
  interval: 250,
});

// Monotonic tokens guard against a slow response overwriting a newer value
let titleFormatToken = 0;
const throttledFormatTitle = throttle(async (value) => {
  const token = ++titleFormatToken;
  try {
    const text = await formatProperty("title", value);
    if (token === titleFormatToken) titleProxy.value = text;
  } catch {
    // Keep the last successfully formatted value on failure
  }
});

let descriptionFormatToken = 0;
const throttledFormatDescription = throttle(async (value) => {
  const token = ++descriptionFormatToken;
  try {
    const text = await formatProperty("description", value);
    if (token === descriptionFormatToken) descriptionProxy.value = text;
  } catch {
    // Keep the last successfully formatted value on failure
  }
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

watch(
  // Will be `null` in single-language setups
  () => panel.language.code,
  () => {
    // Re-evaluate all server-side queries when the language changes
    updateSectionData();
  },
);

// Guard against a stale load winning a race on rapid language switches
let loadToken = 0;

updateSectionData();

async function updateSectionData() {
  const token = ++loadToken;
  const response = await load({
    parent: props.parent,
    name: props.name,
  });

  if (token === loadToken) {
    data.value = response;
  }
}

async function formatProperty(prop: "title" | "description", value: string) {
  // Reverse Kirby's Panel path encoding (`pages/a+b` → `a/b`)
  const pageId = panel.view.path.startsWith("pages/")
    ? panel.view.path.slice(6).replaceAll("+", "/")
    : undefined;

  const response = await api.post(`__serp-preview__/format/${prop}`, {
    pageId,
    value,
  });

  return response.data.text;
}
</script>

<template>
  <k-section :label="label" class="k-serp-section">
    <div
      class="ksp-overflow-hidden ksp-rounded-[var(--input-rounded)] ksp-bg-[var(--input-color-back)] ksp-p-4"
    >
      <div class="ksp-mb-2 ksp-flex ksp-items-center ksp-gap-3">
        <span
          v-if="data.faviconUrl"
          class="ksp-aspect-square ksp-h-[26px] ksp-w-[26px] ksp-inline-flex ksp-items-center ksp-justify-center ksp-border ksp-border-[var(--serp-favicon-border)] ksp-rounded-full ksp-border-solid ksp-bg-[var(--serp-favicon-background)]"
        >
          <img
            class="ksp-block ksp-h-[18px] ksp-w-[18px]"
            :src="data.faviconUrl"
            alt=""
          />
        </span>
        <div class="ksp-flex ksp-flex-col">
          <span class="ksp-text-sm ksp-text-[var(--serp-color-text)]">{{
            data.siteTitle
          }}</span>
          <span
            class="ksp-line-clamp-1 ksp-text-xs ksp-text-[var(--serp-color-text)]"
            >{{ joinURL(data.siteUrl, path) }}</span
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
        class="ksp-line-clamp-2 ksp-mt-1 ksp-text-sm ksp-text-[var(--serp-color-text)]"
      >
        {{ descriptionProxy || description }}
      </p>
    </div>

    <k-button-group v-show="data.searchConsoleUrl" class="ksp-mt-2 ksp-w-full">
      <k-button :link="data.searchConsoleUrl" icon="open" target="_blank">
        {{ panel.t("johannschopplich.serp-preview.searchConsole") }}
      </k-button>
    </k-button-group>
  </k-section>
</template>

<style scoped>
.k-serp-section {
  --serp-favicon-background: light-dark(#f1f3f4, #fff);
  --serp-favicon-border: light-dark(#ecedef, #5c5f5e);
  --serp-color-title: light-dark(#1a0dab, #99c3ff);
  --serp-color-text: light-dark(#4d5156, #bfbfbf);
}
</style>
