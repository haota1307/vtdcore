<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Media Library</h5>
    <table class="table table-sm table-striped align-middle mb-3">
      <thead><tr><th>ID</th><th>Original Name</th><th>MIME</th><th>Size</th><th>Owner</th><th>Created</th></tr></thead>
      <tbody>
        <?php if (!empty($items)): foreach ($items as $m): ?>
          <tr>
            <td><?= esc($m['id']) ?></td>
            <td><?= esc($m['original_name']) ?></td>
            <td><?= esc($m['mime']) ?></td>
            <td><?= esc((string)$m['size']) ?></td>
            <td><?= esc((string)$m['owner_id']) ?></td>
            <td><?= esc($m['created_at'] ?? '') ?></td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center text-muted">No media found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <?php if (isset($meta)): ?>
      <div class="d-flex justify-content-between small text-muted">
        <div>Total: <?= esc($meta['total']) ?></div>
        <div>Page <?= esc($meta['page']) ?> / <?= esc($meta['page_count']) ?></div>
      </div>
    <?php endif; ?>
  </div>
</div>
