<?php
/**
 * Flash Messages Component
 * Include this in admin views to display one-time notifications
 * Usage: <?php include __DIR__ . '/flash_messages.php'; ?>
 */

require_once __DIR__ . '/../../helpers/SessionHelper.php';

$flashMessages = SessionHelper::getFlashMessages();
if (!empty($flashMessages)): ?>
<div class="flash-messages" id="flashMessages">
  <?php foreach ($flashMessages as $flash): ?>
    <div class="flash-message flash-<?= htmlspecialchars($flash['type']); ?>">
      <div class="flash-icon">
        <?php
          $iconMap = [
            'success' => 'fa-check-circle',
            'error' => 'fa-times-circle',
            'warning' => 'fa-exclamation-triangle',
            'info' => 'fa-info-circle'
          ];
          $icon = $iconMap[$flash['type']] ?? 'fa-info-circle';
        ?>
        <i class="fas <?= $icon; ?>"></i>
      </div>
      <span class="flash-text"><?= htmlspecialchars($flash['message']); ?></span>
      <button class="flash-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    </div>
  <?php endforeach; ?>
</div>
<style>
.flash-messages { 
  position: fixed; top: 80px; right: 20px; z-index: 10000; 
  display: flex; flex-direction: column; gap: 10px; max-width: 420px; width: 100%;
}
.flash-message { 
  display: flex; align-items: center; gap: 12px; padding: 14px 18px;
  border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.12);
  animation: flashSlideIn 0.35s ease-out; font-size: 14px; font-weight: 500;
  backdrop-filter: blur(10px);
}
.flash-success { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
.flash-error { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
.flash-warning { background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }
.flash-info { background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; }
.flash-icon { font-size: 18px; flex-shrink: 0; }
.flash-text { flex: 1; line-height: 1.4; }
.flash-close { 
  background: none; border: none; cursor: pointer; font-size: 14px; 
  opacity: 0.5; padding: 4px; color: inherit; transition: opacity 0.2s;
}
.flash-close:hover { opacity: 1; }
@keyframes flashSlideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>
<script>
// Auto-dismiss flash messages after 5 seconds
document.querySelectorAll('.flash-message').forEach(function(el) {
  setTimeout(function() {
    el.style.transition = 'opacity 0.3s, transform 0.3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(100%)';
    setTimeout(function() { el.remove(); }, 300);
  }, 5000);
});
</script>
<?php endif; ?>
