<script>
import { joinURL, withLeadingSlash } from "ufo";
import SectionMixin from "../mixins/section.js";
import LocaleMixin from "../mixins/locale.js";

export default {
  mixins: [SectionMixin, LocaleMixin],

  data() {
    return {
      label: undefined,
      defaultLanguagePrefix: undefined,
      faviconUrl: undefined,
      siteTitle: undefined,
      siteUrl: undefined,
      titleContentKey: undefined,
      titleSeparator: undefined,
      descriptionContentKey: undefined,
      searchConsoleUrl: undefined,
      url: "",
    };
  },

  computed: {
    currentContent() {
      return this.$store.getters["content/values"]();
    },
    path() {
      if (!this.url) return "";

      if (!this.$panel.multilang) {
        const url = new URL(this.url);
        return url.pathname;
      }

      let path = this.getNonLocalizedPath(this.url);

      if (!this.defaultLanguagePrefix) {
        if (!this.$panel.language.default) {
          path = joinURL(this.$panel.language.code, path);
        }
      } else {
        path = joinURL(this.$panel.language.code, path);
      }

      return withLeadingSlash(path);
    },
  },

  watch: {
    "$panel.language.code": {
      async handler() {
        const { url } = await this.$api.get(this.$panel.view.path);
        this.url = url;
      },
      immediate: true,
    },
  },

  async created() {
    const response = await this.load();
    this.label = this.t(response.label) || "SERP Preview";
    this.defaultLanguagePrefix = response.defaultLanguagePrefix ?? true;
    this.faviconUrl = response.faviconUrl;
    this.siteTitle = response.siteTitle;
    this.siteUrl = response.siteUrl;
    this.titleSeparator = response.titleSeparator ?? "–";
    this.titleContentKey = response.titleContentKey;
    this.descriptionContentKey = response.descriptionContentKey;
    this.searchConsoleUrl = response.searchConsoleUrl;
  },

  methods: {
    joinURL,

    t(value) {
      if (!value || typeof value === "string") return value;
      return value[this.$panel.translation.code] ?? Object.values(value)[0];
    },
  },
};
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

      <h3 class="ksp-mb-1 ksp-line-clamp-1 ksp-text-xl ksp-text-[#1a0dab]">
        {{
          currentContent[titleContentKey]
            ? currentContent[titleContentKey]
            : `${$panel.view.title} ${(
                titleSeparator || "–"
              ).trim()} ${siteTitle}`
        }}
      </h3>

      <p
        v-show="currentContent[descriptionContentKey]"
        class="ksp-line-clamp-2 ksp-text-sm ksp-text-[#4d5156]"
      >
        {{ currentContent[descriptionContentKey] }}
      </p>
    </div>

    <k-button-group v-show="searchConsoleUrl" class="ksp-mt-2 ksp-w-full">
      <k-button
        :link="searchConsoleUrl"
        icon="open"
        target="_blank"
        rel="noopener noreferrer"
      >
        Google Search Console
      </k-button>
    </k-button-group>
  </k-section>
</template>
