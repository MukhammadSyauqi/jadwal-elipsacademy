---
name: Academic Precision & Warmth
colors:
  surface: '#fcf8fb'
  surface-dim: '#dcd9dc'
  surface-bright: '#fcf8fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3f5'
  surface-container: '#f0edef'
  surface-container-high: '#eae7ea'
  surface-container-highest: '#e4e2e4'
  on-surface: '#1b1b1d'
  on-surface-variant: '#554336'
  inverse-surface: '#303032'
  inverse-on-surface: '#f3f0f2'
  outline: '#887364'
  outline-variant: '#dbc2b0'
  surface-tint: '#904d00'
  primary: '#904d00'
  on-primary: '#ffffff'
  primary-container: '#f28e2b'
  on-primary-container: '#5e3000'
  inverse-primary: '#ffb77c'
  secondary: '#944a00'
  on-secondary: '#ffffff'
  secondary-container: '#fc8f34'
  on-secondary-container: '#663100'
  tertiary: '#5f5e60'
  on-tertiary: '#ffffff'
  tertiary-container: '#a8a6a8'
  on-tertiary-container: '#3c3c3e'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdcc2'
  primary-fixed-dim: '#ffb77c'
  on-primary-fixed: '#2e1500'
  on-primary-fixed-variant: '#6d3900'
  secondary-fixed: '#ffdcc5'
  secondary-fixed-dim: '#ffb783'
  on-secondary-fixed: '#301400'
  on-secondary-fixed-variant: '#713700'
  tertiary-fixed: '#e4e2e4'
  tertiary-fixed-dim: '#c8c6c8'
  on-tertiary-fixed: '#1b1b1d'
  on-tertiary-fixed-variant: '#474649'
  background: '#fcf8fb'
  on-background: '#1b1b1d'
  surface-variant: '#e4e2e4'
  canvas-pure: '#FFFFFF'
  canvas-parchment: '#F5F5F7'
  surface-pearl: '#FAFAFC'
  hairline: '#E0E0E0'
  divider-soft: '#F0F0F0'
  ink-body: '#1D1D1F'
  ink-muted: '#6E6E73'
  ink-subtle: '#86868B'
  accent-focus: '#F59E0B'
  accent-subtle: '#FEF3C7'
  schedule-conflict: '#EF4444'
  schedule-verified: '#10B981'
  schedule-pending: '#6366F1'
typography:
  display-hero:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '600'
    lineHeight: 52px
    letterSpacing: -0.022em
  display-hero-mobile:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 38px
    letterSpacing: -0.019em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 38px
    letterSpacing: -0.019em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 26px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.016em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 30px
    letterSpacing: -0.014em
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 26px
    letterSpacing: -0.011em
  title-card:
    fontFamily: Inter
    fontSize: 17px
    fontWeight: '600'
    lineHeight: 22px
    letterSpacing: -0.016em
  body-default:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 22px
    letterSpacing: -0.008em
  body-subtle:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: -0.006em
  body-strong:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 22px
    letterSpacing: -0.008em
  label-capsule:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: -0.004em
  label-timetable-time:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.02em
  micro-caption:
    fontFamily: Inter
    fontSize: 10px
    fontWeight: '500'
    lineHeight: 12px
    letterSpacing: 0.01em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-timetable: 0.5rem
  margin: 1.5rem
  margin-mobile: 1rem
  margin-wide: 3rem
  space-xxs: 0.25rem
  space-xs: 0.5rem
  space-sm: 0.75rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  space-2xl: 3rem
---

## Brand & Style

This design system establishes an academic management and class scheduling ecosystem built on the principles of Apple's disciplined, human-centered clarity combined with the warm, optimistic energy of educational pursuit. Designed for academic administrators, faculty instructors, and students, the interface transforms complex logistical friction—timetabling, room assignments, recurring schedule conflicts, and enrollment workflows—into an effortless, calm, and orderly digital workspace.

The design movement bridges **Modern Minimalism** and **Tactile Precision**. The UI chrome recedes deliberately, relying on clean parchment surfaces, razor-sharp 1px hairlines, and typographic hierarchy rather than heavy borders or layered drop shadows. The visual identity exchanges conventional institutional slate-blue for an authoritative, warm golden-orange interactive accent. The resulting atmosphere feels institutional yet human: rigorous and methodical for admin tools, yet inviting, open, and distraction-free for students and educators reviewing their daily academic journeys.

## Colors

The palette establishes an intentional dual-surface structure: crisp, clinical white (`#FFFFFF`) for high-focus working canvases (timetables, interactive forms, and schedule matrices) grounded against Apple-inspired parchment (`#F5F5F7`) for backgrounds, global navigational bars, and auxiliary sidebars.

Interaction is unified under the signature warm orange primary hue (`#F28E2B`), evoking intellectual vitality and academic discovery without the generic coldness of corporate blue. The interactive hierarchy functions with strict discipline:
- **Primary Interactive**: `#F28E2B` drives key calls to action, selected schedule blocks, active tabs, and primary action capsules. The deeper shade `#E67E22` serves as the active/pressed state.
- **Surface Neutrals**: Ink `#1D1D1F` handles text and high-contrast editorial headlines. Muted informational metadata sits in `#6E6E73` and `#86868B`.
- **Dividers & Structural Separators**: `#E0E0E0` and `#F0F0F0` produce quiet, 1px structural boundaries without visual clutter.
- **Functional Academic Semantics**: Timetable conflict detection relies on soft crimson `#EF4444`, verified enrollment on `#10B981`, and student waitlists/pending states on `#6366F1`, each tinted down to 10–15% opacity fills for clean grid scanning.

## Typography

The typographic engine uses **Inter**, configured to emulate Apple's SF Pro typographic metrics: controlled optical heights, humanist proportions, and distinctive negative tracking applied systematically to larger body and display sizes.

Typographic hierarchy is governed by strict rules:
- **Optical Tracking**: Any type sized 17px and above incorporates deliberate negative letter-spacing (`-0.011em` to `-0.022em`) to maintain editorial tension and typographic authority. Sub-12px micro-labels use neutral or slightly relaxed tracking (`0.01em` to `0.02em`) to guarantee legibility in high-density schedule matrices.
- **Weight Restraint**: The system restricts usage to three purposeful weights: `400` (Regular) for body and schedule metadata, `500` (Medium) for navigation labels and interactive chips, and `600` (Semibold) for hierarchy anchors.
- **Tabular Numerals**: Numeric displays, lecture time brackets (`09:00 - 10:30`), room codes, and student capacity figures must enforce `font-feature-settings: "tnum" 1` to guarantee vertical column alignment across dense multi-column schedule grids.

## Layout & Spacing

Layout geometry follows an 8-point structural system, ensuring rhythm across dense timetable schedules and open administrative analytics dashboards.

- **Grid Architecture**:
  - **Desktop (≥ 1280px)**: A 12-column dynamic grid with 24px gutters. Calendar and timetable views employ an asymmetric 240px fixed rail for instructor/room filters paired with a 7-column or 5-day continuous fluid schedule block.
  - **Tablet (768px - 1279px)**: 8 columns with 16px gutters; filters collapse into a slide-over sheet or top horizontal scroll ribbon.
  - **Mobile (≤ 767px)**: 4 columns with 16px outer margins. The multi-column timetable transforms into a single-day segmented card sequence with swipe-based day navigation.
- **Vertical Rhythm**: Section blocks maintain generous whitespace (32px to 48px), while interior calendar blocks and class schedule cells compress down to `space-xs` (8px) and `space-sm` (12px) to maximize schedule density without sacrificing clarity.

## Elevation & Depth

Visual hierarchy rejects exaggerated, tinted drop shadows and decorative surface bevels. Depth is articulated purely through three calibrated techniques:

1. **Tonal Contrast Surfaces**: The base page rests on soft parchment (`#F5F5F7`). White cards (`#FFFFFF`) sit directly atop this parchment, framed by an ultra-thin hairline border (`1px solid #E0E0E0` or `rgba(0, 0, 0, 0.06)`). Visual separation is achieved by color distinction rather than cast shadows.
2. **Apple-style Frosted Translucency**: Sticky top headers, sub-navigation tabs, and floating calendar action bars apply `backdrop-filter: blur(20px) saturate(180%)` backed by `rgba(255, 255, 255, 0.82)`. This anchors floating controls while allowing timetable colors to subtly bleed through as the user scrolls.
3. **Modal & Floating Menus**: Only elevated contextual elements (dropdown selectors, schedule conflict resolution popovers, and dialogs) leverage a delicate shadow: `0 8px 24px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04)`. All other dashboard widgets remain flat.

## Shapes

The design system adopts a refined, rounded geometric language with distinct tiered corner radii:

- **Interactive Capsule & Pills (`9999px`)**: Primary action buttons, secondary ghost actions, search bars, and active status tags employ full pill styling, creating friendly, touch-encouraging targets that stand out against rectangular grids.
- **Card Containers (`12px` to `16px`)**: Timetable day groupings, class profile cards, modal dialogs, and analytics stat tiles use a standard `12px` or `16px` radius (`rounded-lg`), softening technical scheduling data.
- **Schedule Time Blocks (`8px`)**: Individual class session blocks within the weekly grid use an `8px` corner radius, harmonizing between the container curves and tight internal grid boundaries.
- **Form Inputs & Search (`9999px` for search; `10px` for standard inputs)**: Class search and global instructor lookups use full pill styling; date pickers and schedule duration selectors use crisp `10px` soft rounding.

## Components

### Buttons & Interactive Controls
- **Primary CTA**: Full capsule (`border-radius: 9999px`), solid `#F28E2B` background with `#FFFFFF` semibold text. Focus state employs a 2px offset ring in `#F59E0B`. Active state executes a subtle scale-down (`transform: scale(0.97)`), echoing Apple's tactile response.
- **Secondary Ghost Capsule**: Full pill outline with `1px solid #E0E0E0`, pure `#FFFFFF` background, and `#1D1D1F` text. On hover, background shifts to `#F5F5F7`.
- **Utility / Icon Actions**: 36px or 44px circular pills with `rgba(0, 0, 0, 0.04)` fill for timetable navigation (previous/next week, calendar/list view toggle).

### Timetable & Calendar Blocks
- **Class Session Cards**: Placed inside weekly grid cells with `8px` radius and a 3px vertical indicator strip on the left edge denoting subject category (e.g., `#F28E2B` for core studios, `#6366F1` for lectures). Background fill sits at 10% opacity of the category color with `#1D1D1F` title text.
- **Schedule Conflict State**: Flashing hairline border in `#EF4444` with a subtle striped diagonal warning background and actionable pill badge ("Conflict Detected").
- **Current Time Marker**: A 2px horizontal hairline in `#F28E2B` spanning the active day with a 6px circular dot on the time axis.

### Form Inputs & Search Fields
- **Search Bar**: 44px height capsule pill with a 14px magnifying glyph, `#F5F5F7` background, and `1px solid rgba(0, 0, 0, 0.06)` border. On focus, transitions to `#FFFFFF` with a 2px `#F28E2B` border ring.
- **Dropdown & Time Selectors**: 40px height with `10px` corner radius, clean parchment surface, and minimal chevron iconography.

### Chips & Filter Pills
- **Filter Chips**: 32px height pills. Unselected state uses `#FAFAFC` background with a hairline border and `#6E6E73` text. Selected state inverts to `#1D1D1F` background with `#FFFFFF` text or `#F28E2B` background with white text for active batch selections.

### Authentication & Dashboard Cards
- **Auth Card (Login/Registration)**: Centered 420px card on clean parchment `#F5F5F7` with `#FFFFFF` fill, 18px radius, delicate hairline border, prominent Elips Academy warm orange emblem, and generous 36px internal padding.
- **Dashboard Stat Metric Cards**: 14px radius, white fill, hairline border, featuring a 28px Semibold numeric metric in `#1D1D1F` and a 12px muted label in `#6E6E73`.