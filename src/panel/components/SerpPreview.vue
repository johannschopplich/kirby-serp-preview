<script setup lang="ts">
import type { PropType } from "vue";
import { useContent, usePanel } from "kirbyuse";
// Aliased, since `<script setup>` exposes imports to the template, where they
// would shadow the props of the same name.
import {
  disabled as disabledProp,
  help as helpProp,
  id as idProp,
  label as labelProp,
  name as nameProp,
} from "kirbyuse/props";
import pThrottle from "p-throttle";
import { joinURL } from "ufo";
import { computed, ref, watch } from "vue";

const props = defineProps({
  // Declared to keep `disabled` off `k-field`, so the field never looks disabled.
  ...disabledProp,
  ...helpProp,
  ...idProp,
  ...labelProp,
  ...nameProp,
  endpoints: Object,
  faviconUrl: String,
  siteTitle: String,
  siteUrl: { type: String, required: true },
  titleSeparator: String,
  titleContentKey: String,
  defaultTitle: String,
  descriptionContentKey: String,
  defaultDescription: String,
  searchConsoleUrl: String,
  previewPath: { type: String, required: true },
  formatters: Object as PropType<{ title: boolean; description: boolean }>,
});

const panel = usePanel();
const { currentContent } = useContent();

const title = computed(
  () =>
    currentContent.value[props.titleContentKey!] ||
    props.defaultTitle ||
    [panel.view.title, props.titleSeparator, props.siteTitle].join(" "),
);
const description = computed(
  () =>
    (props.descriptionContentKey
      ? currentContent.value[props.descriptionContentKey]
      : undefined) || props.defaultDescription,
);

// Formatted proxies, populated only when a server-side formatter exists.
const titleProxy = ref("");
const descriptionProxy = ref("");

const throttle = pThrottle({
  limit: 1,
  interval: 250,
});

// Monotonic tokens guard against a slow response overwriting a newer value.
let titleFormatToken = 0;
const throttledFormatTitle = throttle(async (value) => {
  const token = ++titleFormatToken;
  try {
    const text = await formatProperty("title", value);
    if (token === titleFormatToken) titleProxy.value = text;
  } catch {
    // Keep the last successfully formatted value on failure.
  }
});

let descriptionFormatToken = 0;
const throttledFormatDescription = throttle(async (value) => {
  const token = ++descriptionFormatToken;
  try {
    const text = await formatProperty("description", value);
    if (token === descriptionFormatToken) descriptionProxy.value = text;
  } catch {
    // Keep the last successfully formatted value on failure.
  }
});

watch(
  title,
  (value) => {
    if (props.formatters?.title) {
      throttledFormatTitle(value);
    }
  },
  { immediate: true },
);

watch(
  description,
  (value) => {
    if (props.formatters?.description) {
      throttledFormatDescription(value);
    }
  },
  { immediate: true },
);

async function formatProperty(prop: "title" | "description", value: string) {
  const response = await panel.api.post<{ text: string }>(
    `${props.endpoints!.field}/format/${prop}`,
    { value },
  );

  return response.text;
}
</script>

<template>
  <k-field
    :id="id"
    :help="help"
    :input="false"
    :label="label"
    :name="name"
    type="serp-preview"
    class="k-serp-preview-field"
  >
    <template v-if="searchConsoleUrl" #options>
      <k-button
        :link="searchConsoleUrl"
        :responsive="true"
        :text="panel.t('johannschopplich.serp-preview.searchConsole')"
        icon="open"
        size="xs"
        target="_blank"
        variant="filled"
      />
    </template>

    <div
      class="ksp-overflow-hidden ksp-rounded-[var(--input-rounded)] ksp-bg-[var(--input-color-back)] ksp-p-4"
    >
      <div class="ksp-mb-2 ksp-flex ksp-items-center ksp-gap-3">
        <span
          v-if="faviconUrl"
          class="ksp-aspect-square ksp-h-[26px] ksp-w-[26px] ksp-inline-flex ksp-items-center ksp-justify-center ksp-border ksp-border-[color:var(--serp-favicon-border)] ksp-rounded-full ksp-border-solid ksp-bg-[color:var(--serp-favicon-background)]"
        >
          <img
            class="ksp-block ksp-h-[18px] ksp-w-[18px]"
            :src="faviconUrl"
            alt=""
          />
        </span>
        <div class="ksp-flex ksp-flex-col">
          <span class="ksp-text-sm ksp-text-[color:var(--serp-color-text)]">{{
            siteTitle
          }}</span>
          <span
            class="ksp-line-clamp-1 ksp-text-xs ksp-text-[color:var(--serp-color-text)]"
            >{{ joinURL(siteUrl, previewPath) }}</span
          >
        </div>
      </div>

      <h3
        class="ksp-line-clamp-1 ksp-text-xl ksp-text-[color:var(--serp-color-title)]"
      >
        {{ titleProxy || title }}
      </h3>

      <p
        v-show="description"
        class="ksp-line-clamp-2 ksp-mt-1 ksp-text-sm ksp-text-[color:var(--serp-color-text)]"
      >
        {{ descriptionProxy || description }}
      </p>
    </div>
  </k-field>
</template>

<style scoped>
.k-serp-preview-field {
  --serp-favicon-background: light-dark(#f1f3f4, #fff);
  --serp-favicon-border: light-dark(#ecedef, #5c5f5e);
  --serp-color-title: light-dark(#1a0dab, #99c3ff);
  --serp-color-text: light-dark(#4d5156, #bfbfbf);
}
</style>
