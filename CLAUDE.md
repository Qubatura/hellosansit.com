# hellosansit.com — kontekst projektu

## Co to jest
Strona serii kolorowanek **Sansit** (Amazon KDP) — marka osobna, NIE dywizja Qubatura.
Bohater: Sansit, mały przybysz z daleka, uczy się emocji jak dziecko. Motyw: „feelings have colors".

## Repo i podgląd
- GitHub (publiczne!): `Qubatura/hellosansit.com`, branch `main`
- GitHub Pages: https://qubatura.github.io/hellosansit.com/ — deploy = push na main
- Repo publiczne → NIC z zaplecza do repo; `_DOCS/`, `_tools/`, `_work/` są w .gitignore
- `index.html` w korzeniu = produkcja; warsztat i playgroundy w `_tools/`

## Stan projektu
Postępy → najnowszy `SESSION-<data>.md` w tym folderze. Tu tylko rzeczy stałe.
Wymagania → `_DOCS/SANSIT_WEBSITE_PRD_v2.md` + `_DOCS/SANSIT_ABOUT_brief.md`.

## Stack i konwencje
- Vanilla HTML/CSS/JS, bez frameworka; font **Nunito Variable** (`fonts/`)
- Paleta strony: tło perłowe (~`#F4EFE8`), ink `#3a3630`, CTA złoto `#f2b334`
  (akcenty postaci: zieleń `#7CBF6B`, magenta `#E05FD0`, fiolet `#6B2FD9`)
- Estetyka: ciepła, dziecięca-premium — NIE dark theme (wyjątek od globalnej zasady),
  narzędzia robocze w `_tools/` mogą być dark
- Język strony: EN pierwszy (rynek KDP), przełącznik EN|PL

## Struktura
- `video/`, `emotion/`, `character/`, `logo/`, `audio/`, `fonts/` — assety (wersjonowane)
- `_tools/` — narzędzia lokalne, playgroundy, pipeline'y (gitignored)
- `_work/` — klatki pośrednie pipeline'u wideo (gitignored, cache — nie kasować bez potrzeby)

## Pipeline wideo (tło → perła)
- `_tools/matting_pipeline.py <in.mp4> <out.mp4>` — pełny przebieg (ffmpeg + rembg isnet)
- `_tools/bake_variant.py <clip> <hex> <out.mp4>` — szybki wariant koloru z cache w `_work/`
- Zasady: cień z strefy stóp + mediana krocząca (bbox jeździ!); eksport bt709 tv-range,
  a strona i tak sampluje tło z rogu wideo (canvas) — zero szarej ramki
- ⚠️ **Safari/iOS gra VP9 webm, ale IGNORUJE kanał alpha** (czarne tło z przebłyskami)
  — `canPlayType` NIE wykrywa tego; wykluczać WebKit po `/apple/i.test(navigator.vendor)`
  (na iOS każda przeglądarka = WebKit) → mp4 fallback. Docelowo HEVC-alpha z Maca.
