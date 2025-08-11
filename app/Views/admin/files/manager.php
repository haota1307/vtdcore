<?= $this->extend('admin/layout/main') ?>
<?= $this->section('content') ?>
<div class="file-manager-wrapper">
  <div class="row g-0">
    <!-- Sidebar -->
    <div class="col-12 col-lg-3 col-xl-2 border-end mb-3 mb-lg-0">
      <div class="p-3 h-100 d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0">Library</h6>
          <button class="btn btn-sm btn-outline-secondary d-lg-none" data-bs-toggle="collapse" data-bs-target="#fm-folders"><i class="ri-menu-2-line"></i></button>
        </div>
        <div id="fm-folders" class="flex-grow-1 collapse show">
          <ul class="list-unstyled small" id="folder-tree">
            <li class="mb-1"><a href="?" class="fw-bold <?= empty($currentFolder)?'text-primary':'' ?>">All Files</a></li>
            <?php if(!empty($folders)): foreach($folders as $f): ?>
              <li class="mb-1"><a href="?folder=<?= urlencode($f) ?><?= $mimeFilter?('&mime='.urlencode($mimeFilter)) : '' ?>" class="<?= $currentFolder===$f?'text-primary fw-bold':'' ?>">📁 <?= esc($f) ?></a></li>
            <?php endforeach; endif; ?>
            <li class="mt-3 text-muted">MIME Groups</li>
            <li class="mb-1"><a href="?<?= $currentFolder?('folder='.urlencode($currentFolder)) : '' ?>" class="<?= empty($mimeFilter)?'text-primary fw-bold':'' ?>">All</a></li>
            <?php foreach(($mimeGroups??[]) as $mg): ?>
              <li class="mb-1"><a href="?<?= $currentFolder?('folder='.urlencode($currentFolder).'&'):'' ?>mime=<?= urlencode($mg) ?>" class="<?= $mimeFilter===$mg?'text-primary fw-bold':'' ?>"><?= esc($mg) ?></a></li>
            <?php endforeach; ?>
            <li class="mt-3 text-muted">Storage: <span id="storage-usage"><?= number_format(($storageTotal??0)/1024,1) ?> KB</span></li>
          </ul>
        </div>
        <div class="mt-3">
          <form id="upload-form" class="d-flex flex-column gap-2" enctype="multipart/form-data" method="post" action="<?= base_url('media/upload') ?>">
            <input type="file" name="file" class="form-control form-control-sm" required>
            <button class="btn btn-sm btn-primary w-100">Upload</button>
          </form>
        </div>
      </div>
    </div>
    <!-- Main list -->
    <div class="col-12 col-lg-6 col-xl-7 px-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Files</h5>
        <div class="d-flex gap-2">
          <input type="text" class="form-control form-control-sm" id="search-input" placeholder="Search..." value="">
        </div>
      </div>
      <div class="table-responsive" style="max-height:60vh;overflow:auto;">
        <table class="table table-sm table-hover align-middle mb-0" id="media-table">
          <thead class="table-light"><tr><th>ID</th><th>Name</th><th>MIME</th><th>Size</th><th>Scan</th><th>Owner</th><th>Created</th></tr></thead>
          <tbody id="media-rows">
            <?php if (!empty($items)): foreach ($items as $m): ?>
              <tr data-id="<?= esc($m['id']) ?>" data-url="<?= esc($m['full_url'] ?? '') ?>" data-path="<?= esc($m['path'] ?? '') ?>" class="media-row">
                <td><?= esc($m['id']) ?></td>
                <td class="text-truncate" style="max-width:180px;"><?= esc($m['original_name']) ?></td>
                <td><?= esc($m['mime']) ?></td>
                <td><?= esc((string)$m['size']) ?></td>
                <td><?php $ss=$m['scan_status']??''; if($ss): ?><span class="badge bg-<?= str_contains($ss,'clean')?'success':'secondary' ?>"><?= esc($ss) ?></span><?php endif; ?></td>
                <td><?= esc((string)$m['owner_id']) ?></td>
                <td><?= esc($m['created_at'] ?? '') ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="6" class="text-center text-muted">No files.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
  <?php if (isset($meta)): ?>
      <div class="d-flex justify-content-between small text-muted py-2 border-top">
        <div>Total: <?= esc($meta['total']) ?></div>
        <div>Page <?= esc($meta['page']) ?> / <?= esc($meta['page_count']) ?></div>
      </div>
      <?php if(isset($pagerObj)): ?>
      <div class="mt-2">
        <?= $pagerObj->links() ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>
    <!-- Detail panel -->
    <div class="col-12 col-lg-3 col-xl-3 border-start px-3 mt-4 mt-lg-0" id="detail-panel">
      <h6 class="mt-2">Details</h6>
      <div id="detail-empty" class="text-muted small">Select a file to preview.</div>
      <div id="detail-content" class="d-none">
        <div class="mb-2" id="preview-box"></div>
        <dl class="row mb-0 small">
          <dt class="col-4">Name</dt><dd class="col-8" id="d-name"></dd>
          <dt class="col-4">MIME</dt><dd class="col-8" id="d-mime"></dd>
          <dt class="col-4">Size</dt><dd class="col-8" id="d-size"></dd>
          <dt class="col-4">Scan</dt><dd class="col-8" id="d-scan"></dd>
          <dt class="col-4">Owner</dt><dd class="col-8" id="d-owner"></dd>
          <dt class="col-4">Created</dt><dd class="col-8" id="d-created"></dd>
          <dt class="col-4">ID</dt><dd class="col-8" id="d-id"></dd>
        </dl>
        <div class="mt-3 d-flex gap-2">
          <a href="#" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1" id="d-open">Open</a>
          <button class="btn btn-sm btn-outline-danger" id="d-delete" data-id="">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->section('js') ?>
<script>
(function(){
  const table = document.getElementById('media-rows');
  const searchInput = document.getElementById('search-input');
  const detailPanel = {
    empty: document.getElementById('detail-empty'),
    content: document.getElementById('detail-content'),
    name: document.getElementById('d-name'),
    mime: document.getElementById('d-mime'),
    size: document.getElementById('d-size'),
  scan: document.getElementById('d-scan'),
  owner: document.getElementById('d-owner'),
    created: document.getElementById('d-created'),
    id: document.getElementById('d-id'),
    open: document.getElementById('d-open'),
    del: document.getElementById('d-delete'),
    preview: document.getElementById('preview-box')
  };
  function humanSize(bytes){
    const b = parseInt(bytes,10)||0; if(!b) return '0 B';
    const u=['B','KB','MB','GB','TB']; let i=0, val=b; while(val>=1024 && i<u.length-1){ val/=1024; i++; }
    return val.toFixed(val<10&&i>0?1:0)+' '+u[i];
  }
  function selectRow(tr){
    table.querySelectorAll('tr').forEach(r=>r.classList.remove('table-active'));
    tr.classList.add('table-active');
    const cells = tr.querySelectorAll('td');
    const data = {
      id: cells[0].textContent.trim(),
      name: cells[1].textContent.trim(),
      mime: cells[2].textContent.trim(),
      size: cells[3].textContent.trim(),
  scan: cells[4].innerText.trim(),
  owner: cells[5].textContent.trim(),
  created: cells[6].textContent.trim(),
      url: tr.dataset.url,
      path: tr.dataset.path,
    };
    detailPanel.empty.classList.add('d-none');
    detailPanel.content.classList.remove('d-none');
    detailPanel.name.textContent = data.name;
    detailPanel.mime.textContent = data.mime;
    detailPanel.size.textContent = humanSize(data.size);
  detailPanel.scan.textContent = data.scan || '';
  detailPanel.owner.textContent = data.owner;
    detailPanel.created.textContent = data.created;
    detailPanel.id.textContent = data.id;
  detailPanel.open.href = data.url || '#';
    detailPanel.del.dataset.id = data.id;
    // basic preview for images
    detailPanel.preview.innerHTML = '';
    if (data.mime.startsWith('image/') && data.url) {
      const img = document.createElement('img'); img.className='img-fluid rounded border'; img.alt=data.name; img.src=data.url; detailPanel.preview.appendChild(img);
    } else if (data.mime === 'application/pdf') {
      const iframe = document.createElement('iframe'); iframe.style.width='100%'; iframe.style.height='200px'; iframe.src=data.url; iframe.className='border rounded'; detailPanel.preview.appendChild(iframe);
    } else if (data.mime.startsWith('text/')) {
      const a = document.createElement('a'); a.href=data.url; a.target='_blank'; a.textContent='Open Text'; detailPanel.preview.appendChild(a);
    } else {
      const a = document.createElement('a'); a.href=data.url; a.target='_blank'; a.textContent='Download'; detailPanel.preview.appendChild(a);
    }
  }
  table?.addEventListener('click', e=>{
    const tr = e.target.closest('tr');
    if(tr) selectRow(tr);
  });
  searchInput?.addEventListener('input', e=>{
    const q=e.target.value.toLowerCase();
    table.querySelectorAll('tr').forEach(tr=>{
      const txt=tr.textContent.toLowerCase();
      tr.style.display = txt.includes(q)?'':'none';
    });
  });
  detailPanel.del?.addEventListener('click', function(){
    const id = this.dataset.id; if(!id) return;
    if(!confirm('Delete file #' + id + '?')) return;
    fetch('<?= base_url('media/item') ?>/' + id, {method:'DELETE', headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json()).then(js=>{ location.reload(); });
  });
  // Drag & drop / chunk upload
  const uploadInput = document.querySelector('#upload-form input[type=file]');
  const form = document.getElementById('upload-form');
  form.addEventListener('change', async (e)=>{
    if(!uploadInput.files.length) return;
    [...uploadInput.files].forEach(f=> handleFile(f));
    uploadInput.value='';
  });
  async function handleFile(file){
    if(file.size > 3*1024*1024){ // chunked
      const initFd = new FormData(); initFd.append('name', file.name); initFd.append('mime', file.type);
      const initRes = await fetch('/media/chunk/init', {method:'POST', body:initFd});
      if(!initRes.ok){ console.error('chunk init failed'); return; }
      const {upload_id} = await initRes.json();
      const chunkSize = 256*1024; const total = Math.ceil(file.size/chunkSize);
      for(let i=0;i<total;i++){
        const fd = new FormData(); fd.append('upload_id', upload_id); fd.append('index', i); fd.append('total', total); fd.append('chunk', file.slice(i*chunkSize,(i+1)*chunkSize), file.name+'.part');
        const res = await fetch('/media/chunk/put', {method:'POST', body: fd});
        if(!res.ok){ console.error('chunk failed', i); return; }
        if(i===total-1){ const js = await res.json(); if(js.complete){ location.reload(); } }
      }
    } else {
      const fd = new FormData(); fd.append('file', file);
      const r = await fetch('/media/upload',{method:'POST', body:fd}); if(r.ok){ location.reload(); }
    }
  }
})();
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
