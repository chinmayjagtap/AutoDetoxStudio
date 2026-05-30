# Auto Detox Studio — Landing Page

A premium, dark-themed, single-page site for **Auto Detox Studio** built with vanilla **HTML / CSS / JS**. No build step. Just open `index.html`.

> Tagline: **WE DON'T WASH, WE DETOX.**

## Features

- Cinematic hero with parallax orbs, animated background particles & shimmer text
- Sticky glass nav with mobile menu
- Animated counters, scroll-reveal, hover tilt + spotlight on service cards
- Bilingual content (English + Marathi) — matches the client's creatives
- 8-stage services grid, Normal Wash vs Detox comparison
- **Google Calendar Appointment** embed, themed to match the site
- Floating "Book" CTA, animated marquee, full responsive layout

## Project structure

```
AutoDetoxStudio/
├── index.html
├── css/styles.css
├── js/main.js
└── assets/favicon.svg
```

## Run locally

Open `index.html` directly in a browser, or serve it:

```bash
# from the project root
python3 -m http.server 5173
# then visit http://localhost:5173
```

---

## Embedding your Google Calendar Appointment

The site is already wired up. You only need to paste your **Appointment Schedule URL** in one place.

### 1. What I need from you

To finish the booking integration, please share **one** of the following:

| Option | What it is | Where you find it |
| --- | --- | --- |
| **A. Embed URL** *(preferred)* | The `src` URL inside the iframe Google gives you (looks like `https://calendar.google.com/calendar/appointments/schedules/AcZssZ.....?gv=true`) | In Google Calendar → your appointment schedule → **Share** → **Embed** |
| **B. Booking page URL** | The public booking page link | In Google Calendar → your appointment schedule → **Share** → **Open booking page** → copy the page URL |

If you can also share the following, I can polish things further:

- **Business name** to display *(currently "Auto Detox Studio")*
- **Address** *(footer placeholder)*
- **Phone** *(currently `+91 00000 00000`)*
- **Email** *(currently `hello@autodetoxstudio.in`)*
- **Social media links** (Instagram, Facebook, YouTube, WhatsApp)
- **Any high‑res photos** of the studio / logo PNG / interior shots if you'd like a gallery section added later

### 2. How to get the embed URL from Google Calendar

1. Open **Google Calendar** on desktop.
2. Click your **Appointment Schedule** (the one you created for bookings).
3. Click **Share** → **Embed**. *(If you only see "Open booking page", click that, then on the booking page open the share / embed option.)*
4. Google shows an `<iframe>` snippet. Copy **only the `src`** value, e.g.:
   ```
   https://calendar.google.com/calendar/appointments/schedules/AcZssZ1abcDEF...etc?gv=true
   ```

### 3. Where to paste it

Open `index.html`, search for `REPLACE_WITH_YOUR_SCHEDULE_ID`, and replace the entire `src` value with your URL:

```html
<!-- Before -->
<iframe id="booking-iframe"
  src="https://calendar.google.com/calendar/appointments/schedules/REPLACE_WITH_YOUR_SCHEDULE_ID?gv=true"
  ...>
</iframe>

<!-- After -->
<iframe id="booking-iframe"
  src="https://calendar.google.com/calendar/appointments/schedules/AcZssZ1abc...?gv=true"
  ...>
</iframe>
```

That's it. The fallback message will disappear automatically once a real URL is pasted in.

### 4. (Optional) Make sure embeds are allowed

In Google Calendar → your **Appointment Schedule settings**, ensure:

- **Booking page** is set to **Public** (or at minimum allowing your domain).
- The schedule has **availability windows** configured.
- If you have a custom domain (e.g. `autodetoxstudio.in`), Google may ask you to whitelist it for embeds — accept the prompt the first time you load the site there.

---

## Customising

- **Colours**: edit the CSS variables at the top of `css/styles.css` (`--accent`, `--bg`, etc.)
- **Sections**: every section in `index.html` is clearly commented and isolated — easy to reorder or remove.
- **Logo**: drop a transparent PNG into `assets/` and replace the inline SVG inside the `.brand-mark` blocks.
- **Hero stats**: change the `data-count` numbers in the `.hero-stats` block.

## Notes

- Pure HTML/CSS/JS — no dependencies, no build, fully static (deploys to Netlify / Vercel / GitHub Pages / shared hosting in seconds).
- Respects `prefers-reduced-motion` for accessibility.
- Works offline except for the Google Fonts and the Google Calendar iframe.
