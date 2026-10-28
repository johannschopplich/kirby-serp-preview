import { fileURLToPath } from "node:url";
import vue from "@vitejs/plugin-vue";
import { build } from "vite";
import { describe, expect, it } from "vitest";

const SITE_ENTRY = fileURLToPath(
  new URL("../../src/panel/site.ts", import.meta.url),
);

const PACKAGE_IMPORTS = [
  "kirbyuse",
  "kirbyuse/props",
  "p-throttle",
  "ufo",
  "vue",
];

describe("site barrel", () => {
  it("loads without `window`", async () => {
    expect(globalThis.window).toBeUndefined();

    const { SerpPreview } = await import("../../src/panel/site");

    expect(SerpPreview).toBeTypeOf("object");
  });

  it("imports only the packages in PACKAGE_IMPORTS", async () => {
    const output = await build({
      configFile: false,
      logLevel: "silent",
      plugins: [vue()],
      build: {
        lib: { entry: SITE_ENTRY, formats: ["es"] },
        write: false,
        rolldownOptions: {
          external: (id) => !id.startsWith(".") && !id.startsWith("/"),
        },
      },
    });

    const outputFiles = (Array.isArray(output) ? output : [output]).flatMap(
      (result) => ("output" in result ? result.output : []),
    );
    const fileNames = new Set(outputFiles.map((file) => file.fileName));
    const packageImports = new Set(
      outputFiles
        .flatMap((file) =>
          file.type === "chunk"
            ? [...file.imports, ...file.dynamicImports]
            : [],
        )
        .filter((id) => !fileNames.has(id)),
    );

    expect([...packageImports].sort()).toEqual(PACKAGE_IMPORTS);
  });
});
