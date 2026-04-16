// ...existing code...
document.addEventListener('DOMContentLoaded', function () {
  const listEl = document.getElementById('destList');

  const baseApi = (function() {
    const origin = window.location.origin;
    const path = window.location.pathname;
    // prefer explicit /public segment if present
    const publicIndex = path.indexOf('/public');
    if (publicIndex !== -1) return origin + path.slice(0, publicIndex + 7); // include '/public'
    // fallback: try to detect TravelMate/public or use default
    const match = path.match(/(\/[^\/]+\/public)/);
    if (match) return origin + match[1];
    return origin + '/TravelMate/public';
  })();

  function loadList() {
    listEl.innerHTML = '<p>Loading...</p>';
    fetch(baseApi + '/api/destination/list', { credentials: 'same-origin' })
      .then(r => r.json())
      .then(resp => {
        if (!resp.success) { listEl.innerHTML = '<p>Failed to load</p>'; return; }
        const rows = resp.data || [];
        if (!rows.length) { listEl.innerHTML = '<p>No destinations yet</p>'; return; }
        listEl.innerHTML = rows.map(d => {
          const baseUrl = window.location.origin + '/TravelMate/public';  
          const img = d.image ? (d.image) : 'assets/images/default-dest.png';

          const fimage = baseUrl + img;

          return `
            <div class="content-card" data-id="${d.id}" style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);border:1px solid #e5e7eb;overflow:hidden;transition:all 0.3s ease;" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 25px rgba(0,0,0,0.15)';" onmouseleave="this.style.transform='translateY(0)';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
              <div class="content-image" style="width:100%;height:220px;overflow:hidden;position:relative;background:#f3f4f6;">
                ${fimage ? ('<img src="' + fimage + '" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s ease;" onmouseenter="this.style.transform=\'scale(1.05)\';" onmouseleave="this.style.transform=\'scale(1)\';"/>') : ''}
                <div style="position:absolute;top:12px;right:12px;background:rgba(26,188,91,0.95);color:#fff;padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Destination</div>
              </div>
              <div class="content-info" style="padding:20px;">
                <h3 style="font-size:18px;font-weight:700;color:#1f2937;line-height:1.4;margin:0 0 12px 0;">${escapeHtml(d.title)}</h3>
                <p style="color:#4b5563;font-size:14px;line-height:1.6;margin:0 0 16px 0;">${escapeHtml(d.description ? d.description.substring(0,120) : '')}${d.description && d.description.length > 120 ? '...' : ''}</p>
                <div class="content-actions" style="display:flex;gap:8px;padding-top:16px;border-top:1px solid #f3f4f6;">
                  <button class="btn-view" data-id="${d.id}" style="flex:1;background:#f3f4f6;color:#374151;border:none;padding:10px 12px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.2s ease;display:flex;align-items:center;justify-content:center;gap:6px;" onmouseenter="this.style.background='#e5e7eb';this.style.transform='translateY(-2px)';" onmouseleave="this.style.background='#f3f4f6';this.style.transform='translateY(0)';">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    View Full
                  </button>
                  <button class="btn-edit" data-id="${d.id}" style="flex:1;background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);color:#fff;border:none;padding:10px 12px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.2s ease;display:flex;align-items:center;justify-content:center;gap:6px;" onmouseenter="this.style.transform='translateY(-2px)';" onmouseleave="this.style.transform='translateY(0)';">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    Edit
                  </button>
                  <button class="btn-delete" data-id="${d.id}" style="flex:1;background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff;border:none;padding:10px 12px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.2s ease;display:flex;align-items:center;justify-content:center;gap:6px;" onmouseenter="this.style.transform='translateY(-2px)';" onmouseleave="this.style.transform='translateY(0)';">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Delete
                  </button>
                </div>
              </div>
            </div>
          `;
        }).join('');
        attachHandlers();
      })
      .catch(err => { console.error(err); listEl.innerHTML = '<p>Error loading</p>'; });
  }

  // ...existing attachHandlers, escapeHtml...
  let destinationToDelete = null;

  function attachHandlers() {
    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=> window.location.href = 'editDestination?id=' + btn.dataset.id);
    });
    document.querySelectorAll('.btn-view').forEach(btn=>{
      btn.addEventListener('click', ()=> {
        if (typeof showViewModal === 'function') {
          showViewModal(btn.dataset.id);
        } else {
          window.location.href = 'beachdetail?dest=' + btn.dataset.id;
        }
      });
    });
    document.querySelectorAll('.btn-delete').forEach(btn=>{
      btn.addEventListener('click', function(){
        destinationToDelete = btn.dataset.id;
        document.getElementById('deleteModal').style.display = 'block';
      });
    });
  }

  // delete confirmation handler
  const confirmDeleteBtn = document.getElementById('btnConfirmDelete');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (!destinationToDelete) return;
      
      const fd = new FormData();
      fd.append('id', destinationToDelete);
      fetch(baseApi + '/api/destination/delete', { method:'POST', body: fd, credentials:'same-origin' })
        .then(r=>r.json()).then(resp=>{
          document.getElementById('deleteModal').style.display = 'none';
          if (resp.success) { 
            if (typeof showSuccessModal === 'function') {
              showSuccessModal('Destination deleted successfully!');
            } else {
              alert('Deleted');
            }
            loadList();
          }
          else alert('Delete failed');
        }).catch(()=> alert('Network error'));
      
      destinationToDelete = null;
    });
  }

  function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#039;"}[m]));
  }

  loadList();
});