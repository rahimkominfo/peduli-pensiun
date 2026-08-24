<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title>Masuk - Peduli Pensiun</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>"/>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>"/>
  <link rel="apple-touch-icon" href="<?= base_url('assets/img/logo.png') ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>"/>
  
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
          "borderRadius": { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
          "spacing": { "margin-mobile": "16px", "gutter": "24px", "margin-desktop": "32px", "max-width": "1280px", "base": "4px" },
          "fontFamily": {
            "headline-lg-mobile": ["Inter"], "headline-lg": ["Inter"], "label-md": ["Inter"],
            "label-sm": ["Inter"], "headline-md": ["Inter"], "body-md": ["Inter"],
            "headline-sm": ["Inter"], "body-sm": ["Inter"], "body-lg": ["Inter"]
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
    body { min-height: max(884px, 100dvh); }
  </style>
</head>
<body class="bg-surface min-h-screen pt-safe pb-safe flex flex-col justify-center">
  <main class="flex-1 flex flex-col relative w-full">
    <div class="flex flex-col w-full h-full min-h-[calc(100vh-64px)] justify-center items-center px-margin-mobile md:px-margin-desktop py-8 bg-surface">
      
      <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden relative">
        <div class="py-8 px-6 bg-primary-container relative overflow-hidden flex flex-col items-center justify-center text-center">
          <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
          <div class="z-10 flex flex-col items-center text-center">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Kabupaten Sinjai" class="h-16 w-auto mb-3 object-contain drop-shadow-md">
            <span class="text-white text-xs font-medium uppercase tracking-widest opacity-80 mb-1">Badan Kepegawaian dan Pengembangan SDM Aparatur</span>
            <h1 class="text-white headline-md font-bold tracking-tight">Peduli Pensiun</h1>
          </div>
        </div>

        <div class="p-8">
          <div class="text-center mb-8">
            <h2 class="headline-sm text-on-surface mb-2">Selamat Datang</h2>
            <p class="body-sm text-on-surface-variant">Silakan masuk menggunakan NIP dan kata sandi Anda untuk mengakses layanan pensiun.</p>
          </div>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-body-sm flex items-center gap-2">
              <i class="fa-solid fa-circle-exclamation text-[18px]"></i>
              <span><?= session()->getFlashdata('error') ?></span>
            </div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 text-body-sm flex items-center gap-2">
              <i class="fa-solid fa-circle-check text-[18px]"></i>
              <span><?= session()->getFlashdata('success') ?></span>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('login') ?>" method="POST" class="space-y-6 flex flex-col w-full">
            <?= csrf_field() ?>
            <div class="flex flex-col gap-1 w-full">
              <label class="label-sm text-on-surface" for="nip">Nomor Induk Pegawai (NIP)</label>
              <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-on-surface-variant">
                  <i class="fa-solid fa-id-card text-[18px]"></i>
                </span>
                <input autocomplete="username" 
                       class="w-full pl-10 pr-4 py-3 bg-surface rounded-xl outline-none text-on-surface body-md transition-shadow focus:shadow-[0_0_0_2px_rgba(49,46,129,0.2)] hover:bg-surface-container-low" 
                       id="nip" name="nip" 
                       placeholder="Masukkan 18 digit NIP" 
                       type="text" 
                       value="<?= old('nip', '199408132019031008') ?>"
                       required />
              </div>
            </div>

            <div class="flex flex-col gap-1 w-full">
              <div class="flex justify-between items-center w-full">
                <label class="label-sm text-on-surface" for="password">Kata Sandi</label>
                <a class="label-sm text-primary-container hover:underline" href="#">Lupa sandi?</a>
              </div>
              <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-on-surface-variant">
                  <i class="fa-solid fa-lock text-[18px]"></i>
                </span>
                <input autocomplete="current-password" 
                       class="w-full pl-10 pr-12 py-3 bg-surface rounded-xl outline-none text-on-surface body-md transition-shadow focus:shadow-[0_0_0_2px_rgba(49,46,129,0.2)] hover:bg-surface-container-low" 
                       id="password" name="password" 
                       placeholder="••••••••" 
                       type="password" 
                       value="123456"
                       required />
                <button class="absolute inset-y-0 right-0 flex items-center pr-3 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer outline-none" 
                        id="togglePassword" type="button">
                  <i class="fa-solid fa-eye-slash text-[18px]" id="visibilityIcon"></i>
                </button>
              </div>
            </div>

            <button class="w-full py-4 mt-4 bg-primary-container text-white rounded-xl label-md uppercase tracking-wider hover:bg-primary-container/90 transition-colors active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
              Masuk Aplikasi
              <i class="fa-solid fa-arrow-right text-[18px]"></i>
            </button>
          </form>

          <div class="mt-8 pt-6 border-t-0 bg-gradient-to-r from-transparent via-outline-variant/30 to-transparent h-px w-full"></div>

          <div class="mt-6 flex flex-col items-center gap-3">
            <p class="body-sm text-on-surface-variant text-center">Butuh bantuan masuk?</p>
            <button class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface label-sm hover:bg-surface-container transition-colors flex items-center gap-2" type="button">
              <i class="fa-solid fa-headset text-[18px]"></i>
              Hubungi Pusat Bantuan
            </button>
          </div>
        </div>
      </div>

      <div class="mt-12 text-center">
        <p class="label-sm text-on-surface-variant/60 uppercase tracking-widest">© 2024 BKPSDM Kabupaten Sinjai</p>
      </div>

    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const passwordInput = document.getElementById('password');
      const toggleButton = document.getElementById('togglePassword');
      const visibilityIcon = document.getElementById('visibilityIcon');

      toggleButton.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        visibilityIcon.className = isPassword ? 'fa-solid fa-eye text-[18px]' : 'fa-solid fa-eye-slash text-[18px]';
      });

      const inputs = document.querySelectorAll('input');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.add('text-primary-container');
        });
        input.addEventListener('blur', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.remove('text-primary-container');
        });
      });
    });
  </script>
</body>
</html>
