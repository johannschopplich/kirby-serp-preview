/// <reference types="vite/client" />

// `kirbyuse` augments `Window` with the typed `panel` object; tsc never loads the
// section component that imports it, so this file references it.
/// <reference types="kirbyuse" />
