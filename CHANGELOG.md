# Changelog

## 1.1.0 — 2026-10-14

- Controllers resolve the core handlers through the container, so a handler can be
  replaced with `$app->bind()`; controllers can be replaced the same way.
- Requires `mikhailmelnikov/laravel-jobs-monitor` ^1.1.

## 1.0.1 — 2026-10-14

- README translated into Russian; step-by-step installation guide.

## 1.0.0 — 2026-10-14

- Initial release: Scheduler and Queue pages for Backpack 6/7 on top of
  `mikhailmelnikov/laravel-jobs-monitor`, install command for the menu,
  English and Russian translations.
