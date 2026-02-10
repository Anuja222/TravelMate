// activities.js - JavaScript for Activities Management
document.addEventListener('DOMContentLoaded', function () {
  const listEl = document.getElementById('activityList');

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
    listEl.innerHTML = '<p style="text-align:center;padding:40px;color:#666;">Loading...</p>';
    fetch(baseApi + '/api/activity/list', { credentials: 'same-origin' })
      .then(r => r.json())
      .then(resp => {
        if (!resp.success) { 
          listEl.innerHTML = '<p style="text-align:center;padding:40px;color:#e74c3c;">Failed to load activities</p>'; 
          return; 
        }
        const rows = resp.data || [];
        if (!rows.length) { 
          document.getElementById('emptyState').style.display = 'block';
          listEl.innerHTML = ''; 
          return; 
        }
        
        document.getElementById('emptyState').style.display = 'none';
        listEl.innerHTML = rows.map(a => {
          const baseUrl = window.location.origin + '/TravelMate/public';  
          const img = a.image ? (a.image) : 'assets/images/default-activity.png';
          const fimage = baseUrl + img;

          return `
            <div class="content-card" data-id="${a.id}" style="background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;transition:all 0.3s ease;" onmouseenter="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.15)';" onmouseleave="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)';">
              <div class="content-image" style="width:100%;height:240px;overflow:hidden;">
                ${fimage ? ('<img src="' + fimage + '" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s ease;" onmouseenter="this.style.transform=\'scale(1.05)\';" onmouseleave="this.style.transform=\'scale(1)\';"/>') : ''}
              </div>
              <div class="content-info" style="padding:20px;">
                <div class="content-header" style="margin-bottom:10px;">
                  <h3 style="font-size:20px;font-weight:600;color:#2c3e50;margin:0 0 10px 0;">${escapeHtml(a.title)}</h3>
                </div>
                <p style="color:#666;font-size:14px;line-height:1.6;margin:0 0 15px 0;">${escapeHtml(a.description ? a.description.substring(0,160) : '')}${a.description && a.description.length > 160 ? '...' : ''}</p>
                <div class="content-actions" style="display:flex;gap:10px;padding-top:15px;border-top:1px solid #eee;">
                  <button class="btn-view" data-id="${a.id}" style="flex:1;background:#1abc5b;color:#fff;border:none;padding:10px 15px;border-radius:6px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.3s ease;display:flex;align-items:center;justify-content:center;gap:5px;" onmouseenter="this.style.background='#16a085';this.style.transform='translateY(-2px)';" onmouseleave="this.style.background='#1abc5b';this.style.transform='translateY(0)';">
                    <i class="fas fa-eye" style="font-size:14px;"></i> View
                  </button>
                  <button class="btn-edit" data-id="${a.id}" style="flex:1;background:#3498db;color:#fff;border:none;padding:10px 15px;border-radius:6px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.3s ease;display:flex;align-items:center;justify-content:center;gap:5px;" onmouseenter="this.style.background='#2980b9';this.style.transform='translateY(-2px)';" onmouseleave="this.style.background='#3498db';this.style.transform='translateY(0)';">
                    <i class="fas fa-edit" style="font-size:14px;"></i> Edit
                  </button>
                  <button class="btn-delete" data-id="${a.id}" style="flex:1;background:#e74c3c;color:#fff;border:none;padding:10px 15px;border-radius:6px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.3s ease;display:flex;align-items:center;justify-content:center;gap:5px;" onmouseenter="this.style.background='#c0392b';this.style.transform='translateY(-2px)';" onmouseleave="this.style.background='#e74c3c';this.style.transform='translateY(0)';">
                    <i class="fas fa-trash" style="font-size:14px;"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          `;
        }).join('');
        attachHandlers();
      })
      .catch(err => { 
        console.error(err); 
        listEl.innerHTML = '<p style="text-align:center;padding:40px;color:#e74c3c;">Error loading activities</p>'; 
      });
  }

  let activityToDelete = null;

  function attachHandlers() {
    document.querySelectorAll('.btn-edit').forEach(btn=>{
      btn.addEventListener('click', ()=> window.location.href = 'editActivity?id=' + btn.dataset.id);
    });
    document.querySelectorAll('.btn-view').forEach(btn=>{
      btn.addEventListener('click', ()=> {
        if (typeof showViewModal === 'function') {
          showViewModal(btn.dataset.id);
        } else {
          // Fallback to edit page if showViewModal is not available
          window.location.href = 'editActivity?id=' + btn.dataset.id;
        }
      });
    });
    document.querySelectorAll('.btn-delete').forEach(btn=>{
      btn.addEventListener('click', function(){
        activityToDelete = btn.dataset.id;
        document.getElementById('deleteModal').style.display = 'block';
      });
    });
  }

  // Delete confirmation handler
  const confirmDeleteBtn = document.getElementById('btnConfirmDelete');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (!activityToDelete) return;
      
      const fd = new FormData();
      fd.append('id', activityToDelete);
      fetch(baseApi + '/api/activity/delete', { method:'POST', body: fd, credentials:'same-origin' })
        .then(r=>r.json()).then(resp=>{
          document.getElementById('deleteModal').style.display = 'none';
          if (resp.success) { 
            if (typeof showSuccessModal === 'function') {
              showSuccessModal('Activity deleted successfully!');
            } else {
              alert('Activity deleted successfully!');
            }
            loadList();
          }
          else alert('Delete failed');
        }).catch(()=> alert('Network error'));
      
      activityToDelete = null;
    });
  }

  function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#039;"}[m]));
  }

  loadList();
});
