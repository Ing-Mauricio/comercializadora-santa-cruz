<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/public/js/main.js"></script>

<?php if (!empty($_SESSION['flash_ok'])): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1200;">
  <div class="toast align-items-center text-white border-0 show" style="background: linear-gradient(135deg,#22c55e,#16a34a);">
    <div class="d-flex">
      <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_ok']) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php unset($_SESSION['flash_ok']); endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1200;">
  <div class="toast align-items-center text-white border-0 show" style="background: linear-gradient(135deg,#ef4444,#dc2626);">
    <div class="d-flex">
      <div class="toast-body"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php unset($_SESSION['flash_error']); endif; ?>

</body>
</html>
