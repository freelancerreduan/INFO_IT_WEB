<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo e(asset('vendor/chartjs/chart.umd.min.js')); ?>"></script>
<script>
  /* Local file kono karone load na hole CDN theke fallback */
  if (!window.Chart) {
    document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"><\/script>');
  }
</script>
<script>
/* =========================================================
   HELPERS (same as original)
========================================================= */
const CURRENCY = <?php echo json_encode(currency_symbol(), 15, 512) ?>;
function money(n){ return CURRENCY + Number(n).toLocaleString('en-US'); }
function esc(v){ return String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
function statusPill(s){ return `<span class="status-pill status-${esc(String(s).toLowerCase())}">${esc(s)}</span>`; }

/* AJAX JSON — server error / session sesh hole clear message dey (age "Loading…" e atke thakto) */
function fetchJSON(url){
  return fetch(url, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin'})
    .then(r => {
      const isJson = (r.headers.get('content-type') || '').includes('application/json');
      if (r.status === 401 || r.status === 419 || (r.redirected && !isJson)) throw new Error('Your session has expired. Please reload the page and log in again.');
      if (!r.ok) throw new Error('Server error (' + r.status + '). Please try again in a moment.');
      if (!isJson) throw new Error('The server did not send valid data. Please reload the page.');
      return r.json();
    })
    .catch(e => { throw (e instanceof TypeError ? new Error('Cannot connect to the server. Check your internet and try again.') : e); });
}

if (window.Chart) {
  Chart.defaults.font.family = "'Inter',sans-serif";
  Chart.defaults.color = '#6B7089';
  Chart.defaults.borderColor = '#EEEEF3';
}

/* =========================================================
   SIDEBAR GROUPS
========================================================= */
function toggleGroup(id, el){
  document.querySelectorAll(`[id^="grp-${id}"]`).forEach(c=>{
    const inst = bootstrap.Collapse.getOrCreateInstance(c, {toggle:false});
    if(c.classList.contains('show')) inst.hide(); else inst.show();
  });
  document.querySelectorAll(`[data-toggle-group="${id}"]`).forEach(l=>{
    l.setAttribute('aria-expanded', l.getAttribute('aria-expanded')==='true' ? 'false':'true');
  });
}
function adminLogout(){ document.getElementById('logoutForm').submit(); }

/* =========================================================
   IMAGE UPLOAD PREVIEW (file input stays inside the form)
========================================================= */
function handlePhoto(evt, zoneId){
  const file = evt.target.files[0];
  if(!file) return;
  const reader = new FileReader();
  reader.onload = e=>{
    const zone = document.getElementById(zoneId);
    zone.querySelector('[data-upload-preview] img').src = e.target.result;
    zone.querySelector('[data-upload-preview]').classList.remove('d-none');
    zone.querySelector('[data-upload-label]').classList.add('d-none');
  };
  reader.readAsDataURL(file);
}
function resetUpload(zoneId){
  const zone = document.getElementById(zoneId);
  zone.querySelector('input[type=file]').value = '';
  zone.querySelector('[data-upload-preview]').classList.add('d-none');
  zone.querySelector('[data-upload-label]').classList.remove('d-none');
}

/* =========================================================
   CONFIRM MODAL (generic) -> submits hidden form
   <button class="js-confirm" data-url="" data-method="DELETE|PATCH"
           data-status="" data-title="" data-text="" data-label="">
========================================================= */
function openConfirm(title, text, label, url, method, status){
  document.getElementById('confirmTitle').textContent = title;
  document.getElementById('confirmText').textContent = text;
  const btn = document.getElementById('confirmActionBtn');
  btn.textContent = label;
  btn.className = 'btn btn-soft-danger px-4';
  const form = document.getElementById('confirmForm');
  form.action = url;
  document.getElementById('confirmFormMethod').value = method || 'DELETE';
  document.getElementById('confirmFormStatus').value = status || '';
  bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmModal')).show();
}
function confirmDelete(type, label, url){
  openConfirm(`Delete ${type}?`, `“${label}” will be permanently removed. This action cannot be undone.`, 'Delete', url, 'DELETE');
}
function confirmAction(title, text, actionLabel, url, status){
  openConfirm(title, text, actionLabel, url, 'PATCH', status);
}
document.getElementById('confirmActionBtn').addEventListener('click', function(){
  this.disabled = true;
  document.getElementById('confirmForm').submit();
});
document.getElementById('confirmModal').addEventListener('hidden.bs.modal', ()=>{
  document.getElementById('confirmActionBtn').disabled = false;
});
document.addEventListener('click', e=>{
  const b = e.target.closest('.js-confirm');
  if(!b) return;
  e.preventDefault();
  if(b.dataset.method === 'PATCH'){
    confirmAction(b.dataset.title, b.dataset.text, b.dataset.label, b.dataset.url, b.dataset.status);
  } else if(b.dataset.title){
    /* custom text delete (e.g. order soft delete) */
    openConfirm(b.dataset.title, b.dataset.text, b.dataset.label || 'Delete', b.dataset.url, 'DELETE');
  } else {
    confirmDelete(b.dataset.type, b.dataset.name, b.dataset.url);
  }
});

/* =========================================================
   VIEW MODALS
========================================================= */
function viewStudent(s){
  document.getElementById('viewStudentBody').innerHTML = `
    <div class="text-center mb-3"><img src="${esc(s.photo)}" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--accent-soft)"></div>
    <div class="meta-row"><span>Student ID</span><span>${esc(s.sid || '—')}</span></div>
    <div class="meta-row"><span>Name</span><span>${esc(s.name)}</span></div>
    <div class="meta-row"><span>Email</span><span>${esc(s.email)}</span></div>
    <div class="meta-row"><span>Phone</span><span>${esc(s.phone)}</span></div>
    <div class="meta-row"><span>Parent Number</span><span>${esc(s.parent)}</span></div>
    <div class="meta-row"><span>NID / Birth Reg.</span><span>${esc(s.nid)}</span></div>
    <div class="meta-row"><span>Course</span><span>${esc(s.course)}</span></div>
    <div class="meta-row"><span>Enrollment Date</span><span>${esc(s.date)}</span></div>
    <div class="meta-row"><span>Status</span><span>${statusPill(s.status.toLowerCase())}</span></div>`;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('viewStudentModal')).show();
}
function viewCourse(c){
  document.getElementById('viewCourseBody').innerHTML = `
    <img src="${esc(c.thumb)}" class="course-thumb mb-3" style="border-radius:12px">
    <div class="meta-row"><span>Name</span><span>${esc(c.name)}</span></div>
    <div class="meta-row"><span>Category</span><span>${esc(c.category)}</span></div>
    <div class="meta-row"><span>Description</span><span style="max-width:60%;text-align:right">${esc(c.desc)}</span></div>
    <div class="meta-row"><span>Price</span><span>${money(c.discount)}</span></div>
    <div class="meta-row"><span>Duration</span><span>${esc(c.duration)}</span></div>
    <div class="meta-row"><span>Modules</span><span>${esc(c.modules)}</span></div>
    <div class="meta-row"><span>Classes</span><span>${esc(c.classes)}</span></div>
    <div class="meta-row"><span>Students</span><span>${esc(c.students)}</span></div>
    <div class="meta-row"><span>Status</span><span>${statusPill(c.status.toLowerCase())}</span></div>`;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('viewCourseModal')).show();
}
/* d = {title:'', rows:[[label,value],...], status:'Pending'} */
function viewDetails(d){
  document.getElementById('viewDetailsTitle').textContent = d.title;
  document.getElementById('viewDetailsBody').innerHTML =
    d.rows.map(r=>`<div class="meta-row"><span>${esc(r[0])}</span><span>${esc(r[1])}</span></div>`).join('') +
    (d.status ? `<div class="meta-row"><span>Status</span><span>${statusPill(d.status)}</span></div>` : '');
  bootstrap.Modal.getOrCreateInstance(document.getElementById('viewDetailsModal')).show();
}
document.addEventListener('click', e=>{
  const b = e.target.closest('[data-view]');
  if(!b) return;
  const data = JSON.parse(b.dataset.payload);
  if(b.dataset.view === 'student') viewStudent(data);
  else if(b.dataset.view === 'course') viewCourse(data);
  else viewDetails(data);
});

/* live filter (course list: oninput / onchange like the original) */
let _ft;
function autoSubmit(form, delay){ clearTimeout(_ft); _ft = setTimeout(()=>form.submit(), delay||0); }
</script>
<?php /**PATH /home/infoitcom/public_html/resources/views/admin/layouts/partials/scripts.blade.php ENDPATH**/ ?>