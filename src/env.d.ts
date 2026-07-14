/// <reference types="vite/client" />

// `kirbyuse` globally augments `Window` with the typed `panel` object. Referenced
// explicitly because the Panel entry only imports the section component (which tsc
// skips via `@ts-ignore`), so the augmentation is otherwise absent from the program.
/// <reference types="kirbyuse" />
