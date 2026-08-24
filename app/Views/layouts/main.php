<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title><?= esc($title ?? 'Peduli Pensiun') ?></title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>"/>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>"/>
  <link rel="apple-touch-icon" href="<?= base_url('assets/img/logo.png') ?>"/>

  <!-- Fonts & Local FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>"/>

  <!-- Tailwind CDN with custom config matching prototype -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          "colors": {
            "on-tertiary-container": "#f18a21",
            "primary-fixed-dim": "#c3c0ff",
            "on-surface": "#1b1b21",
            "on-primary": "#ffffff",
            "surface-container-high": "#eae7ef",
            "surface-container": "#F1F5F9",
            "secondary-container": "#82f5c1",
            "on-error-container": "#93000a",
            "on-secondary-fixed": "#002114",
            "surface-container-lowest": "#ffffff",
            "primary-container": "#312e81",
            "primary-fixed": "#e2dfff",
            "tertiary-fixed-dim": "#ffb77d",
            "error-red": "#E11D48",
            "on-primary-container": "#9c9af4",
            "on-tertiary-fixed": "#2f1500",
            "surface-container-low": "#f6f2fa",
            "tertiary-fixed": "#ffdcc3",
            "tertiary-container": "#5b2e00",
            "surface-bright": "#fcf8ff",
            "error-container": "#ffdad6",
            "outline-variant": "#c8c5d3",
            "inverse-on-surface": "#f3eff7",
            "on-error": "#ffffff",
            "surface": "#fcf8ff",
            "tertiary": "#3b1c00",
            "secondary": "#006c4a",
            "on-surface-variant": "#474651",
            "on-primary-fixed": "#100563",
            "on-secondary": "#ffffff",
            "surface-container-highest": "#e5e1e9",
            "outline": "#777682",
            "surface-dim": "#dcd9e0",
            "primary": "#1a146b",
            "surface-variant": "#e5e1e9",
            "error": "#ba1a1a",
            "indigo-tint": "#E0E7FF",
            "background": "#fcf8ff",
            "inverse-primary": "#c3c0ff",
            "surface-tint": "#5654a8",
            "on-secondary-fixed-variant": "#005137",
            "on-background": "#1b1b21",
            "on-secondary-container": "#00714e",
            "secondary-fixed-dim": "#68dba9",
            "inverse-surface": "#303036",
            "on-primary-fixed-variant": "#3e3c8f",
            "on-tertiary-fixed-variant": "#6e3900",
            "on-tertiary": "#ffffff",
            "secondary-fixed": "#85f8c4"
          },
          "borderRadius": {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
          "spacing": {
            "margin-mobile": "16px",
            "gutter": "24px",
            "margin-desktop": "32px",
            "max-width": "1280px",
            "base": "4px"
          },
          "fontFamily": {
            "headline-lg-mobile": ["Inter"],
            "headline-lg": ["Inter"],
            "label-md": ["Inter"],
            "label-sm": ["Inter"],
            "headline-md": ["Inter"],
            "body-md": ["Inter"],
            "headline-sm": ["Inter"],
            "body-sm": ["Inter"],
            "body-lg": ["Inter"]
          },
          "fontSize": {
            "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
            "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
            "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600" }],
            "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
            "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
            "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
            "headline-sm": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
            "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
            "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }]
          }
        }
      }
    }
  </script>
  <style>
    @layer base {
      html, body {
        width: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }
      .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
      .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
    }
  </style>
</head>
<body class="bg-surface font-body-md text-on-surface flex flex-col min-h-screen">

  <!-- Fixed Top Header -->
  <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-xl pt-safe shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-16 px-margin-mobile max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex flex-col">
        <a href="<?= base_url('dashboard') ?>" class="font-headline-sm text-headline-sm text-primary tracking-tight leading-tight hover:opacity-90">PEDULI PENSIUN</a>
        <span class="text-label-sm font-label-sm text-on-surface-variant">NIP: <?= esc(session('user_nip') ?? '19850421 201001 1 004') ?></span>
      </div>
      <div class="flex items-center gap-2">
        <a href="<?= base_url('logout') ?>" title="Keluar" class="w-10 h-10 flex items-center justify-center text-error hover:bg-error-container/20 rounded-full transition-colors">
          <i class="fa-solid fa-right-from-bracket text-[18px]"></i>
        </a>
        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center" title="<?= esc(session('user_nama') ?? 'User') ?>">
          <i class="fa-solid fa-user text-on-primary text-[16px]"></i>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Container -->
  <main class="flex-1 flex flex-col relative w-full pt-20 pb-32 bg-surface px-margin-mobile max-w-7xl mx-auto">
    
    <!-- Flash Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="mt-4 p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-2 text-body-sm shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-600 text-[18px]"></i>
        <span><?= session()->getFlashdata('success') ?></span>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="mt-4 p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 flex items-center gap-2 text-body-sm shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-red-600 text-[18px]"></i>
        <span><?= session()->getFlashdata('error') ?></span>
      </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
  </main>

  <!-- Fixed Bottom Navigation Bar -->
  <nav class="fixed bottom-0 inset-x-0 z-50 pb-safe bg-white/95 backdrop-blur-xl shadow-[0_-1px_8px_rgba(0,0,0,0.04)]">
    <div class="flex justify-around items-center h-16 px-2 max-w-7xl mx-auto">
      <a href="<?= base_url('dashboard') ?>" 
         class="flex flex-col items-center justify-center gap-1 min-w-[64px] h-full transition-all <?= ($activeMenu ?? '') === 'dashboard' ? 'text-primary font-bold' : 'text-on-surface-variant' ?>">
        <i class="fa-solid fa-border-all text-[18px]"></i>
        <span class="text-label-sm">Dashboard</span>
      </a>
      <?php if (can_access_persiapan()): ?>
      <a href="<?= base_url('data-persiapan') ?>" 
         class="flex flex-col items-center justify-center gap-1 min-w-[64px] h-full transition-all <?= ($activeMenu ?? '') === 'data-persiapan' ? 'text-primary font-bold' : 'text-on-surface-variant' ?>">
        <i class="fa-solid fa-clipboard-list text-[18px]"></i>
        <span class="text-label-sm">Persiapan</span>
      </a>
      <?php endif; ?>
      <a href="<?= base_url('data-verifikasi') ?>" 
         class="flex flex-col items-center justify-center gap-1 min-w-[64px] h-full transition-all <?= ($activeMenu ?? '') === 'verifikasi' ? 'text-primary font-bold' : 'text-on-surface-variant' ?>">
        <i class="fa-solid fa-file-circle-check text-[18px]"></i>
        <span class="text-label-sm">Verifikasi</span>
      </a>
      <a href="<?= base_url('dokumen') ?>" 
         class="flex flex-col items-center justify-center gap-1 min-w-[64px] h-full transition-all <?= ($activeMenu ?? '') === 'dokumen' ? 'text-primary font-bold' : 'text-on-surface-variant' ?>">
        <i class="fa-solid fa-folder-open text-[18px]"></i>
        <span class="text-label-sm">Dokumen</span>
      </a>
      <a href="<?= base_url('logout') ?>" 
         class="flex flex-col items-center justify-center gap-1 min-w-[64px] h-full text-on-surface-variant hover:text-error transition-all">
        <i class="fa-solid fa-right-from-bracket text-[18px]"></i>
        <span class="text-label-sm">Keluar</span>
      </a>
    </div>
  </nav>

</body>
</html>
