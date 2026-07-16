# hellosansit.com — PRD v2 (dla Claude Code)
**Wersja:** 2.0
**Data:** 16 lipca 2026
**Autor:** Kuba (Qubatura) + Claude
**Cel:** kompletna specyfikacja strony do buildu w Claude Code. Opisuje stan docelowy; realizacja fazami (patrz §11).
**Powiązane dokumenty:** `SANSIT_ABOUT_brief.md` (treść sekcji About, PL + notatki EN).

> ZMIANY vs v1: dodano stack i deployment (IQ Host + GitHub), strukturę folderów assetów, mailer (reuse z Qubatury), dwa playery audio (tło + piosenka), rozwiązanie blendu wideo (bez alfy — perła), aktualny stan assetów (wideo i emocje gotowe).

---

## 1. CEL I KONTEKST

### 1.1 Po co ta strona
Książka "Sansit: Joy" (Amazon KDP, live) ma na Cover 3 QR: rodzic skanuje → wchodzi na stronę → zostawia opinię. **Strona MUSI być live zanim pierwszy egzemplarz dotrze do czytelnika** — inaczej QR prowadzi w pustkę.

### 1.2 Dwie grupy odbiorców
- **Dziecko (4–5 lat):** przyciąga je Sansit (ruch, kolor, interakcja). Często nie czyta.
- **Rodzic (płacący, świadomy, rynek US):** przyciąga go elegancja, zaufanie, psycholog, jakość. To on wypełnia formularz i kupuje.
- Zasada: **Sansit bawi dziecko, biel/jakość przekonuje rodzica.**

### 1.3 Dwa cele konwersji (CTA)
1. **Get the book** → Amazon
2. **Leave feedback** → formularz (badanie rynku pod S02 + relacja)

### 1.4 KRYTYCZNE: mobile-first
QR skanuje się telefonem. Większość ruchu = komórki, często słaby zasięg. Wszystko (zwłaszcza hero z wideo) projektowane najpierw pod telefon. Waga assetów i szybkość ładowania = priorytet nadrzędny.

---

## 2. STACK I DEPLOYMENT (nowe w v2)

### 2.1 Stack
- **Vanilla HTML + CSS + JavaScript** (jak na qubatura.* — sprawdzone, lekkie, bez frameworka).
- **BEZ** React/Vue/itp. — one-pager tego nie potrzebuje, framework tylko obciąża mobile.
- **Architektura modułowa** — kod ma być tak zorganizowany, żeby dało się dokładać interaktywność (glow, mikroanimacje, docelowo gra) BEZ przepisywania całości.
- Ciężkie biblioteki tylko **punktowo, per-moduł**, jeśli konieczne (np. Canvas API / lekki silnik do gry w Fazie 2). Nie na launch.

### 2.2 Workflow i hosting
- **Domena:** `hellosansit.com` — kupiona i hostowana u Kuby na **IQ Host**.
- **Praca:** na **GitHub** (wersjonowanie, Claude Code ma dostęp — sprawdzony, najlepszy dotychczasowy workflow) + lokalnie na MSI. Kuba i Claude Code dogadają szczegóły lokalnej pracy.
- **Produkcja:** po ~2 dniach pracy → **fizyczny wrzut na serwer IQ Host** (nie GitHub Pages jako produkcja — GitHub to środowisko robocze/repo, produkcja ląduje na IQ Host).
- Build i praca: Claude Code na MSI.

### 2.3 Lokalizacja assetów (MSI)
- Wszystkie projekty Kuby: **`Pulpit/claude/`** na MSI. Projekt Sansita w folderze projektowym tam (Claude Code zna strukturę).
- Struktura folderu projektu (spójna z Google Drive `SANSIT - ROBOCZO/hellosansit.com/`):
```
hellosansit.com/
├── _DOCS/          ← SANSIT_WEBSITE_PRD_v2.md, SANSIT_ABOUT_brief.md
├── video/          ← intro.mp4, loop.mp4 (hero)
├── emotion/        ← 7-8 grafik emocji (Blok 3 About) — patrz §13
├── character/      ← master look, plecak, refy
├── logo/           ← logo SANSIT
└── audio/          ← main theme (tło) + piosenka (później)
```

---

## 3. ESTETYKA I KIERUNEK WIZUALNY

### 3.1 Tło
- **Ciepła perła / off-white**, NIE czysty biały (kliniczny). 
- **WAŻNE:** kolor tła strony **dobrać pod ciepłe tło wideo** (film ma ciepłe kremowo-beżowe tło) — żeby wideo zlało się ze stroną bez widocznej ramki. Patrz §4.4 (blend). Docelowy odcień do ustalenia na podstawie realnego koloru tła klipów (ciepła perła, nie zimna).
- Duża przestrzeń, "quiet design". Jeden żywy akcent (Sansit + glow), reszta spokojna.

### 3.2 Glow reagujący na kursor
- Opalizujący gradient **zielono-fioletowy** (kolory Sansita) podążający za kursorem, warstwa **POD/wokół** wideo. Subtelny (10–20%).
- Mobile (brak kursora): glow statyczny/delikatnie pulsujący lub reagujący na touch.
- **Respektować `prefers-reduced-motion`** — glow statyczny, wideo zredukowane.

### 3.3 Paleta i typografia
- Akcenty: magenta (czułki neutralne), złoto (radość/peak), fiolet plecaka `#2E1065`.
- Font: **Nunito** (spójny z książką) jako web-font, lub najbliższy zamiennik. Do potwierdzenia.
- Ton: elegancki, ciepły, premium-dziecięcy. Zero krzykliwości.

---

## 4. WIDEO SANSITA — STAN, TECHNIKA, BLEND

### 4.1 Stan: GOTOWE ✅
- **intro.mp4** — Sansit wbiega z lewej, staje na środku twarzą do widza (odtwarzane RAZ przy załadowaniu).
- **loop.mp4** — idle 6s: oddycha, mruga, ręce subtelnie żyją, czułki się kołyszą, NIE kłania się, NIE macha (świadoma decyzja: duży gest w pętli męczy).
- Format: 16:9, 720p (upscale lokalny w razie potrzeby). Oba z tym samym ciepłym tłem (spójność).

### 4.2 Zachowanie hero
- Załadowanie → **intro gra raz** → płynne przejście → **loop w kółko** (muted, autoplay).
- Ostatnia klatka intro ≈ pierwsza klatka loopa (ta sama poza) — przejście bez przeskoku.
- Poster image jako fallback do czasu załadowania. Lazy/priorytet pod mobile.

### 4.3 Reaktywność = KOD, nie wideo
- Wideo jest liniowe, nie reaguje. Reaktywność (glow, później śledzenie kursora) robi **kod na stronie**.
- **Faza 1:** glow podąża za kursorem (radial gradient + JS). Sam Sansit się nie obraca.
- **Faza 2:** śledzenie kursora wzrokiem/głową (warstwy / rig) — osobno, nie na launch.

### 4.4 BLEND TŁA — kluczowe zadanie (nowe w v2)
**Nie robimy alfy/przezroczystości w wideo** (generatory AI jej nie dają, a keyowanie Sansita jest trudne — zielony/magenta/fiolet zjadają klucze). Zamiast tego:
- **tło strony = ten sam ciepły odcień co tło wideo** → wideo zlewa się naturalnie.
- **maska gradientowa na górnej krawędzi kontenera wideo** (`mask-image: linear-gradient`) — górne ~15–20% wideo rozpływa się w tło strony. To **ukrywa twardą krawędź uciętych czułków** (przy niektórych klatkach czubek czułka jest ucięty prostą linią — gradient to rozpuszcza) i pozwala headerowi/logo nachodzić na tę strefę.
- **ZADANIE DLA CLAUDE CODE:** ujednolicić tło wideo ze stroną. Opcje do oceny przez Claude Code: (a) dobór koloru tła strony pod wideo + maska gradientowa (preferowane, najprostsze), (b) AI-matting/usunięcie tła z wideo (DaVinci Magic Mask / rembg / Replace Background) jeśli (a) nie wystarczy. Kuba nie robi color/matting sam — to po stronie Claude Code.

---

## 5. ARCHITEKTURA STRONY (one-page, mobile-first)

### 5.1 HEADER (sticky, minimalny)
- **Lewy góra:** logo SANSIT (+ easter egg/mini-gra — Faza 2, §6).
- **Prawy góra:** przełącznik **EN/PL** + **player tła** (mute/unmute, default OFF — §8) + ew. link "Get the book".
- Header lekki. Może mieć podkład/tło, żeby nachodzić na górną (rozmytą) krawędź wideo (§4.4).

### 5.2 HERO
- Sansit (intro → loop) na środku, perła + reaktywny glow.
- Główny CTA: **Get the book** → Amazon.
- Bezsłowny (§7 model komunikacji).

### 5.3 ABOUT
- **Treść i struktura: patrz `SANSIT_ABOUT_brief.md`** (4 bloki: potrzeba rodzica → Sansit jako odpowiedź → świat emocji + galeria → Sandra + geneza).
- Blok 3 zawiera **galerię emocji** (7-8 grafik z `emotion/` — §13).
- Wyróżnik: psycholog (Sandra) — ciepło, odczarowana powaga.
- PL gotowe, **EN do przetłumaczenia** (§9).

### 5.4 DOWNLOAD (tapety) — Faza 2 / lekko
- 2 tapety (desktop + mobile). Na launch opcjonalne/placeholder.
- Ewentualny mail-capture obok (nie twardy gate — friction na mobile). Decyzja otwarta.

### 5.5 FEEDBACK (formularz — sedno, tu prowadzi QR) — §10
- Może mieć obok **drugi player: "Posłuchaj piosenki Sansita"** (rozwijany) — §8.

### 5.6 SERIES PREVIEW
- Zapowiedź kolejnych emocji: **ciekawość → smutek → złość** (jak Cover 3, "or" nie zamyka listy). Bez zobowiązania do konkretnego S02.

### 5.7 FOOTER
- Kontakt, nota prawna, prywatność (RODO — §10.3).
- Nazwa prawna wydawcy: **Qubatura / Qubatura Group — DO POTWIERDZENIA**.
- Sansit™ + copyright.

---

## 6. EASTER EGG / MINI-GRA (Faza 2)
- Pod logo SANSIT (top-left) lub pod kliknięciem Sansita.
- Prosta, dziecięca, bezsłowna (np. łapanie kamieni emocji / kolorowanie / "nakarm Sansita emocją").
- **NIE blokuje launchu.** Zabawka na później. Stack modułowy (§2.1) ma to umożliwić bez przebudowy.

---

## 7. MODEL KOMUNIKACJI SANSITA
**Sansit NIE mówi — ani głosem, ani tekstem.** Komunikuje się jak w książce: bez słów, kolorem i zachowaniem.
- **Loop:** oddech, mruganie, kołysanie czułków (już w loop.mp4).
- **Reaktywność (kod):** glow za kursorem (Faza 1); śledzenie wzrokiem (Faza 2).
- **Reakcja na klik (Faza 2):** krótki efekt + **piktogram** (serduszko/gwiazdka) — NIGDY tekst.
- ❌ Zero: lektora/głosu, ruszania ustami, dymków z tekstem.

---

## 8. AUDIO — DWA PLAYERY (nowe w v2)

### 8.1 Player TŁA (main theme)
- Mały, w **headerze**, mute/unmute, **default OFF**, loop.
- Plik: main theme instrumentalny (Suno, w `audio/`). Na launch może być placeholder — muzyka wymienna bez zmian w kodzie.
- Nigdy autoplay z dźwiękiem.

### 8.2 Player "Posłuchaj piosenki Sansita"
- Osobny, **rozwijany/otwierany**, przy sekcji opinii rodziców (§5.5, prawa strona).
- Plik: piosenka Sansita z tekstem, **dwujęzyczna** — DO ZROBIENIA później.
- **Na launch: placeholder** (miejsce i UI gotowe, piosenka dojdzie w kolejnej iteracji).

---

## 9. JĘZYKI (EN/PL)
- Obie wersje od launchu. **Domyślny dla nowego wejścia: EN** (target US, QR → US czytelnik).
- Przełącznik EN/PL w headerze. Wybór trzymany w sesji (klik PL nie wraca do EN przy scrollu); nowe wejście = zawsze EN.
- **Treści:** PL gotowe (About brief). **EN do przetłumaczenia** — profesjonalnie, natywnie, ten sam ciepły ton. Do zrobienia przy buildzie (Claude Code) po akceptacji PL przez Sandrę.

---

## 10. FORMULARZ OPINII

### 10.1 Charakter
- **Prywatny feedback dla Kuby, NIE publiczne recenzje.** (Publiczne = tylko Amazon, od niepowiązanych.)
- Jednocześnie **badanie rynku pod S02.**

### 10.2 Pytania (propozycja — do domknięcia)
- Czy Sansit spodobał się dziecku? (skala / emoji)
- Czy pasuje do wieku dziecka?
- Czy chcielibyście kolejną część?
- Którą emocję następną? (ciekawość / smutek / złość) ← dane pod S02
- Otwarte pole komentarza
- Mail rodzica

### 10.3 RODO (Kuba w EU — obowiązkowe)
- Minimum danych: **mail rodzica + opinia. ZERO danych dziecka.**
- Nota o prywatności + checkbox zgody. Jasno: po co mail, jak się wypisać.

### 10.4 Mailer — REUSE Z QUBATURY (domknięte w v2)
- **Claude Code sprawdza, jak zrobił działający formularz na qubatura.\* i robi analogicznie dla Sansita** (ten sam silnik/mechanizm + **zabezpieczenia antybotowe**, które już tam działają).
- Mail z formularza leci na **biuro@qubatura.eu** (jak Qubatura), z **tematem/nagłówkiem `[SANSIT]`** do filtrowania/labelowania w skrzynce.
- Formularz **nie wysyła bezpośrednio z frontu bez zabezpieczeń** — reuse sprawdzonego rozwiązania z antybotem.
- Przyszłość: przy większym ruchu → dedykowana skrzynka / agent obsługi. Na teraz: nie mnożyć bytów.

### 10.5 Wyświetlanie opinii
- Na starcie: **tylko zbieranie, NIE pokazywanie publicznie** (3 opinie = pusta ściana wygląda słabo).
- Później: moderacja + pokazanie wybranych.

---

## 11. PRIORYTETY — FAZY

### 🟢 FAZA 1 — CORE NA LAUNCH
- [ ] Struktura one-page, mobile-first, ciepła perła + typografia (Nunito)
- [ ] Hero: intro.mp4 (raz) → loop.mp4 (pętla), muted autoplay, poster fallback
- [ ] **Blend wideo/tło** (§4.4): kolor tła pod wideo + maska gradientowa górnej krawędzi
- [ ] Glow reagujący na kursor (kod, Faza 1)
- [ ] Header: logo, EN/PL, player tła (default OFF)
- [ ] About (wg `SANSIT_ABOUT_brief.md`) + galeria emocji (Blok 3)
- [ ] **Formularz opinii działający** — reuse mailer Qubatury + antybot, mail na biuro@qubatura.eu `[SANSIT]`, RODO ← krytyczne, tu prowadzi QR
- [ ] Series preview
- [ ] Footer + nota prawna/prywatność
- [ ] CTA Amazon
- [ ] Treści EN + PL
- [ ] Player "piosenka Sansita" — placeholder UI
- [ ] Deploy: GitHub → IQ Host

### 🟡 FAZA 2 — ITERACJA
- [ ] Sansit śledzi kursor wzrokiem/głową (warstwy / rig)
- [ ] Reakcje na klik + piktogramy
- [ ] Easter egg / mini-gra
- [ ] Tapety + ew. mail-capture
- [ ] Piosenka Sansita (dwujęzyczna) — realny plik zamiast placeholdera
- [ ] Publiczne wyświetlanie wybranych opinii (moderacja)
- [ ] Sekcja "od Sandry / posłuchaj" (podcasty/audycje) — patrz §14

---

## 12. OTWARTE PYTANIA / DO DOMKNIĘCIA
1. Nazwa prawna wydawcy w stopce: "Qubatura" czy "Qubatura Group"?
2. Font: Nunito web-font czy zamiennik?
3. Finalna lista pytań w formularzu (§10.2 to propozycja).
4. Tapety: free czy mail-gate? (Faza 2)
5. Analytics: Plausible (privacy-friendly) czy inne? — mierzyć skany QR→wizyty, klik CTA, wypełnienia formularza.
6. Emotion grafiki: wybór jednego wariantu na emocję (§13) + poprawki literówek w nazwach.

---

## 13. ASSETY — STAN

### Gotowe ✅
- **video/intro.mp4**, **video/loop.mp4** — hero (§4.1)
- **emotion/** — 7-8 grafik emocji Sansita (radość, smutek, ciekawość, złość, strach, niechęć, zaskoczenie, spokój). **UWAGA:** niektóre emocje mają po kilka wariantów (smutek sad1/sad3, niechęć disgust1/2, spokój calm1/3) → **wybrać PO JEDNYM na emocję** do galerii (§5.3). Drobne literówki w nazwach do poprawy: `curocity`→`curiosity`, `suprised`→`surprised`. Grafiki z marca — kanon OK, spójne z masterem.
- **character/** — SANSIT_master_1_lock.png, sansit_ref_a/b.png, plecak__ok.png
- **logo/** — logo SANSIT (LOGO_3D_master_nanobanana.png)
- **QR** — już wskazuje na hellosansit.com (zweryfikowany)
- **About** — treść PL gotowa (`SANSIT_ABOUT_brief.md`)

### Do zrobienia / placeholder
- **audio/** main theme (Suno) — w trakcie, wymienne, może być placeholder na launch
- **audio/** piosenka Sansita (dwujęzyczna) — Faza 2
- **Tłumaczenie EN** treści — po akceptacji PL przez Sandrę
- **Tapety** — Faza 2

---

## 14. KIERUNEK STRATEGICZNY (Faza 2+ — zapis, NIE realizować teraz)
Marka ekspercka Sandry wokół Sansita: planowany cykl radiowy (psychologia dla dzieci i rodziców), podcasty/audycje na stronie (sekcja "od Sandry"), docelowo konsultacje wideo (wersja PL). Sansit jako marka parasolowa: warstwa dziecięca (Sansit bawi) + ekspercka (Sandra pomaga), współistnieją, nie zlewają się w przekazie. Konsultacje = osobny projekt z pełnym ogarnięciem prawnym (uprawnienia, RODO dane wrażliwe dzieci, OC zawodowe), Sandra jako świadomy współtwórca. NIE wpychać w launch.

---

*Koniec PRD v2.0. Powiązane: SANSIT_ABOUT_brief.md. Gotowe do buildu w Claude Code (MSI, folder Pulpit/claude/, workflow GitHub → IQ Host).*
