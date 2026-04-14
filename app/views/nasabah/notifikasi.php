<?php
ob_start();
?>

<!-- Notifikasi -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Notifikasi</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($notifikasi)): ?>
            <div class="text-center py-5">
                <i class="bi bi-bell-slash display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada notifikasi</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifikasi as $notif): ?>
                <div class="list-group-item <?= ($notif['is_read'] ?? 0) ? '' : 'bg-light' ?>">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <?php
                                $iconMap = [
                                    'success' => 'check-circle-fill text-success',
                                    'info' => 'info-circle-fill text-info',
                                    'warning' => 'exclamation-triangle-fill text-warning',
                                    'error' => 'x-circle-fill text-danger'
                                ];
                                $icon = $iconMap[$notif['tipe'] ?? 'info'] ?? 'bell-fill text-primary';
                                ?>
                                <i class="bi bi-<?= $icon ?> me-2"></i>
                                <h6 class="mb-0"><?= htmlspecialchars($notif['judul'] ?? 'Notifikasi') ?></h6>
                                <?php if (!($notif['is_read'] ?? 0)): ?>
                                <span class="badge bg-primary ms-2">Baru</span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-2 text-muted"><?= htmlspecialchars($notif['pesan'] ?? '') ?></p>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                            </small>
                        </div>
                        <div class="ms-3">
                            <?php if (!($notif['is_read'] ?? 0) && isset($notif['id_notifikasi'])): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="mark_read" value="1">
                                <input type="hidden" name="id_notifikasi" value="<?= $notif['id_notifikasi'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai sudah dibaca">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php if (!empty($notif['link_terkait'])): ?>
                            <a href="<?= BASE_URL . $notif['link_terkait'] ?>" class="btn btn-sm btn-primary ms-1">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($notifikasi)): ?>
    <div class="card-footer bg-white border-top">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Total <?= count($notifikasi) ?> notifikasi
        </small>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();

$pageTitle = 'Notifikasi';
$pageSubtitle = 'Pemberitahuan dan update pengajuan kredit';

require_once BASE_PATH . 'app/views/layouts/dashboard.php';
?>
