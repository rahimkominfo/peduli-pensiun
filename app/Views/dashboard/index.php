<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full gap-6 mt-2">
  
  <!-- Greeting -->
  <section class="flex flex-col gap-2">
    <h1 class="text-headline-lg-mobile text-on-surface">Selamat Datang, <?= esc(session('user_nama') ?? 'Admin') ?></h1>
    <p class="text-body-md text-on-surface-variant">Berikut adalah ringkasan progres usulan pensiun dari database server lokal hari ini.</p>
  </section>

  <!-- Stat Cards Grid (Live Data from pensiun_db) -->
  <section class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <!-- Stat Card 1: Total Usul -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center">
        <i class="fa-solid fa-file-lines text-on-primary-fixed text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Total Usul</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['total'] ?></span>
      </div>
    </div>

    <!-- Stat Card 2: Pengusulan -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-secondary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center">
        <i class="fa-solid fa-file-pen text-on-secondary-fixed text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Pengusulan</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['pengusulan'] ?></span>
      </div>
    </div>

    <!-- Stat Card 3: Verifikasi -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-tertiary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center">
        <i class="fa-solid fa-circle-check text-on-tertiary-fixed text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Verifikasi</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['verifikasi'] ?></span>
      </div>
    </div>

    <!-- Stat Card 4: Terbit SK -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center">
        <i class="fa-solid fa-file-circle-check text-on-primary-container text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Terbit SK</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['terbit_sk'] ?></span>
      </div>
    </div>

    <!-- Stat Card 5: Cetak SK -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-secondary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center">
        <i class="fa-solid fa-print text-on-secondary-container text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Cetak SK</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['cetak_sk'] ?></span>
      </div>
    </div>

    <!-- Stat Card 6: Terima SK -->
    <div class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-tertiary/5 rounded-full transition-transform group-hover:scale-150"></div>
      <div class="w-10 h-10 rounded-full bg-tertiary-container flex items-center justify-center">
        <i class="fa-solid fa-check-double text-on-tertiary-container text-[20px]"></i>
      </div>
      <div class="flex flex-col">
        <span class="text-label-sm text-on-surface-variant uppercase">Terima SK</span>
        <span class="text-headline-lg-mobile text-on-surface"><?= $stats['terima_sk'] ?></span>
      </div>
    </div>
  </section>

  <!-- Recent Submissions List -->
  <section class="bg-surface-container rounded-xl p-4 shadow-sm flex flex-col gap-4">
    <div class="flex justify-between items-center">
      <h2 class="text-headline-sm text-on-surface">Usulan Terbaru</h2>
      <?php if (can_access_persiapan()): ?>
      <a href="<?= base_url('data-persiapan') ?>" class="text-primary text-label-md hover:underline flex items-center gap-1">
        Lihat Semua
        <i class="fa-solid fa-arrow-right text-[14px]"></i>
      </a>
      <?php endif; ?>
    </div>
    
    <div class="flex flex-col gap-3">
      <?php foreach ($candidates as $item): ?>
        <div class="bg-surface-container-lowest p-3 rounded-lg shadow-sm flex justify-between items-center">
          <div class="flex flex-col">
            <span class="text-body-md font-semibold text-on-surface"><?= esc($item['NAMA']) ?></span>
            <span class="text-label-sm text-on-surface-variant">NIP: <?= esc($item['NIP']) ?> • <?= esc($item['UNIT_NAMA']) ?></span>
          </div>
          <a href="<?= base_url('data-verifikasi/' . $item['PP_ID']) ?>" class="px-3 py-1.5 bg-primary/10 text-primary text-label-sm font-semibold rounded-lg hover:bg-primary/20 transition-colors">
            Detail
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FAQ Section (Embedded Iframe from KB Sinjai) -->
  <section class="bg-surface-container rounded-xl p-4 md:p-5 shadow-sm flex flex-col gap-4 overflow-hidden mb-6">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center shrink-0">
        <i class="fa-solid fa-circle-question text-on-primary-fixed text-[20px]"></i>
      </div>
      <h2 class="text-headline-sm text-on-surface">Tanya Jawab (FAQ)</h2>
    </div>
    <div class="w-full rounded-xl overflow-hidden bg-white shadow-inner border border-outline-variant/30 relative">
      <iframe src="https://kb.sinjaikab.go.id/dilan/embed/faq/6" 
              class="w-full h-[500px] md:h-[600px] border-0 block" 
              title="FAQ Peduli Pensiun" 
              loading="lazy">
      </iframe>
    </div>
  </section>

</div>
<?= $this->endSection() ?>
