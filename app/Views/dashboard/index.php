<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full gap-6 mt-2">
  
  <!-- Greeting -->
  <section class="flex flex-col gap-2">
    <h1 class="text-headline-lg-mobile text-on-surface">Selamat Datang, <?= esc(session('user_nama') ?? 'Admin') ?></h1>
    <!-- <p class="text-body-md text-on-surface-variant">Berikut adalah ringkasan progres usulan pensiun dari database server lokal hari ini.</p> -->
  </section>

  <!-- Stat Cards Grid (Live Data from pensiun_db) -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
    <!-- Card 1: JUMLAH PENGUSULAN OPD -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#0066FF] tracking-wider uppercase">JUMLAH PENGUSULAN OPD</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['pengusulan_opd'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
      </div>
    </div>

    <!-- Card 2: VERIFIKASI BKPSDMA -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#16a34a] tracking-wider uppercase">VERIFIKASI BKPSDMA</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['verifikasi'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
      </div>
    </div>

    <!-- Card 3: PERTEK BKN -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#0891b2] tracking-wider uppercase">PERTEK BKN</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['pertek_bkn'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
      </div>
    </div>

    <!-- Card 4: PENERBITAN SK -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#d97706] tracking-wider uppercase">PENERBITAN SK</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['penerbitan_sk'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
      </div>
    </div>

    <!-- Card 5: SELESAI -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#dc2626] tracking-wider uppercase">SELESAI</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['selesai'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
      </div>
    </div>

    <!-- Card 6: JUMLAH PNS YANG AKTIF PENSIUN -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition-shadow">
      <span class="text-[13px] md:text-[14px] font-bold text-[#1e293b] tracking-wider uppercase">JUMLAH PNS YANG AKTIF PENSIUN</span>
      <div class="mt-3">
        <span class="text-2xl md:text-[26px] font-extrabold text-on-surface tracking-tight"><?= esc($stats['total_aktif'] ?? 0) ?> <span class="text-xl md:text-2xl font-bold text-on-surface">Orang</span></span>
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
