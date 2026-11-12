<style>
:root{
  --ink:#1A1C29;
  --muted:#6B7089;
  --line:#E6E7F0;
  --page-bg:#F4F5FA;
  --card-bg:#FFFFFF;
  --accent:#4C3AE3;
  --accent-ink:#3728B5;
  --accent-soft:#EEEBFC;
  --side-1:#14151F;
  --side-2:#1C1E2C;
  --side-line:rgba(255,255,255,.08);
  --side-text:#A7A9C0;
  --side-text-active:#FFFFFF;
  --ok:#178A4C;
  --ok-soft:#E3F7EC;
  --warn:#B8650F;
  --warn-soft:#FCEEDD;
  --bad:#C5304A;
  --bad-soft:#FBE7EB;
  --off:#5E6178;
  --off-soft:#EEEEF3;
  --radius:12px;
  --radius-sm:9px;
  --shadow:0 1px 2px rgba(20,22,31,.04), 0 8px 24px -12px rgba(20,22,31,.10);
}
*{box-sizing:border-box;}
html,body{overflow-x:hidden;}
body{
  font-family:'Inter',system-ui,sans-serif;
  background:var(--page-bg);
  color:var(--ink);
  font-size:14.5px;
  -webkit-font-smoothing:antialiased;
}
h1,h2,h3,h4,h5,.font-display{font-family:'Sora',system-ui,sans-serif;}
a{text-decoration:none;}
.small-caps{letter-spacing:.02em;}

/* ---------- Layout ---------- */
.app-shell{display:flex;min-height:100vh;}
.sidebar{
  width:264px;flex-shrink:0;
  background:linear-gradient(190deg,var(--side-1),var(--side-2));
  position:fixed;top:0;left:0;bottom:0;
  display:flex;flex-direction:column;
  z-index:1040;
  border-right:1px solid var(--side-line);
}
.main-col{flex:1;margin-left:264px;min-width:0;display:flex;flex-direction:column;min-height:100vh;}
@media (max-width:991.98px){
  .sidebar{display:none;}
  .main-col{margin-left:0;}
}

.brand{
  display:flex;align-items:center;gap:10px;
  padding:22px 22px 18px 22px;
}
.brand-mark{
  width:34px;height:34px;border-radius:9px;
  background:var(--accent);
  display:flex;align-items:center;justify-content:center;
  font-family:'Sora';font-weight:700;color:#fff;font-size:15px;flex-shrink:0;
}
.brand-text{color:#fff;font-family:'Sora';font-weight:600;font-size:15px;line-height:1.15;}
.brand-logo{display:flex;align-items:center;justify-content:center;background:#fff;border-radius:10px;padding:6px 12px;width:100%;max-width:200px;}
.brand-logo img{max-height:46px;max-width:100%;object-fit:contain;display:block;}
.offcanvas-header .brand-logo{max-width:180px;}
.brand-text small{display:block;color:var(--side-text);font-weight:400;font-size:11px;font-family:'Inter';margin-top:1px;}

.side-scroll{flex:1;overflow-y:auto;padding:6px 14px 14px;}
.side-scroll::-webkit-scrollbar{width:5px;}
.side-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:10px;}

.nav-label{
  font-size:10.5px;color:#585B76;text-transform:uppercase;letter-spacing:.09em;
  padding:14px 12px 6px;font-weight:600;
}
.side-link{
  display:flex;align-items:center;gap:11px;
  padding:9.5px 12px;border-radius:var(--radius-sm);
  color:var(--side-text);font-size:14px;font-weight:500;
  cursor:pointer;transition:background .15s ease,color .15s ease;
  margin-bottom:2px;position:relative;
}
.side-link i.side-ic{font-size:16px;width:18px;text-align:center;flex-shrink:0;}
.side-link:hover{background:rgba(255,255,255,.055);color:#fff;}
.side-link.active{background:var(--accent);color:#fff;}
.side-link .side-badge{
  margin-left:auto;background:#E5484D;color:#fff;font-size:11px;font-weight:700;
  min-width:20px;height:20px;border-radius:99px;display:inline-flex;align-items:center;justify-content:center;padding:0 6px;
}
.side-link .chev{margin-left:auto;font-size:12px;transition:transform .25s ease;color:#75798F;}
.side-link[aria-expanded="true"] .chev{transform:rotate(90deg);color:#fff;}
.side-sub{padding-left:16px;}
.side-sub .side-link{padding:8px 12px 8px 34px;font-size:13.3px;}
.side-sub .side-link::before{
  content:"";position:absolute;left:20px;top:50%;width:4px;height:4px;border-radius:50%;
  background:#54566E;transform:translateY(-50%);
}
.side-sub .side-link.active::before{background:#fff;}
.side-foot{padding:12px 20px 18px;border-top:1px solid var(--side-line);}

/* ---------- Topbar ---------- */
.topbar{
  position:sticky;top:0;z-index:1030;
  background:rgba(244,245,250,.86);backdrop-filter:blur(10px);
  border-bottom:1px solid var(--line);
  padding:13px 24px;
  display:flex;align-items:center;gap:14px;
}
.icon-btn{
  width:38px;height:38px;border-radius:10px;border:1px solid var(--line);background:#fff;
  display:flex;align-items:center;justify-content:center;color:var(--ink);font-size:16px;
  transition:background .15s ease;flex-shrink:0;
}
.icon-btn:hover{background:#F0F0F7;}
.page-title{font-family:'Sora';font-weight:600;font-size:18px;margin:0;line-height:1.2;}
.page-title-sub{font-size:12px;color:var(--muted);}
.badge-dot{
  position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:50%;
  background:var(--bad);border:2px solid #fff;
}
.avatar{
  width:36px;height:36px;border-radius:50%;
  background:var(--accent-soft);color:var(--accent-ink);
  display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:600;font-size:13px;
}
.profile-btn{display:flex;align-items:center;gap:9px;border:1px solid var(--line);background:#fff;border-radius:12px;padding:5px 12px 5px 5px;}
.profile-name{font-size:13.3px;font-weight:600;line-height:1.1;}
.profile-role{font-size:11px;color:var(--muted);}

.content{padding:24px;flex:1;}
@media (max-width:575.98px){.content{padding:16px;} .topbar{padding:12px 16px;}}

/* ---------- Cards / generic ---------- */
.card{border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);background:var(--card-bg);}
.card-header{background:transparent;border-bottom:1px solid var(--line);padding:16px 18px;}
.card-body{padding:18px;}
/* Chart height — canvas er parent e fixed height na thakle Chart.js render hoy na */
.chart-box{position:relative;width:100%;height:280px;}
.chart-box canvas{position:absolute;inset:0;width:100%!important;height:100%!important;}
.chart-box.sm{height:240px;}

.stat-card{padding:18px;display:flex;flex-direction:column;gap:12px;}
.stat-top{display:flex;align-items:flex-start;justify-content:space-between;}
.stat-ic{
  width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;
}
.stat-num{font-family:'Sora';font-weight:700;font-size:25px;line-height:1;}
.stat-label{color:var(--muted);font-size:12.5px;margin-top:4px;}
.stat-change{font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:3px;}
.stat-change.up{color:var(--ok);}
.stat-change.down{color:var(--bad);}

.section-title{font-family:'Sora';font-weight:600;font-size:15px;margin:0;}
.section-sub{color:var(--muted);font-size:12px;}

/* ---------- Badges ---------- */
.status-pill{
  display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:99px;font-size:11.5px;font-weight:600;white-space:nowrap;
}
.status-pill::before{content:"";width:6px;height:6px;border-radius:50%;}
.status-completed{background:var(--ok-soft);color:var(--ok);}
.status-completed::before{background:var(--ok);}
.status-pending{background:var(--warn-soft);color:var(--warn);}
.status-pending::before{background:var(--warn);}
.status-rejected{background:var(--bad-soft);color:var(--bad);}
.status-rejected::before{background:var(--bad);}
.status-canceled{background:var(--off-soft);color:var(--off);}
.status-canceled::before{background:var(--off);}
.status-active{background:var(--ok-soft);color:var(--ok);}
.status-active::before{background:var(--ok);}
.status-inactive{background:var(--off-soft);color:var(--off);}
.status-inactive::before{background:var(--off);}
/* Messages / Seminar venue / Applications */
.status-new{background:var(--warn-soft);color:var(--warn);}
.status-new::before{background:var(--warn);}
.status-read{background:#E1F5F5;color:#0F8B8D;}
.status-read::before{background:#0F8B8D;}
.status-replied{background:var(--ok-soft);color:var(--ok);}
.status-replied::before{background:var(--ok);}
.status-online{background:var(--ok-soft);color:var(--ok);}
.status-online::before{background:var(--ok);}
.status-offline{background:#FCEEDD;color:#B8650F;}
.status-offline::before{background:#B8650F;}
.status-contacted{background:#E1F5F5;color:#0F8B8D;}
.status-contacted::before{background:#0F8B8D;}
.status-enrolled{background:var(--ok-soft);color:var(--ok);}
.status-enrolled::before{background:var(--ok);}
.status-paid{background:var(--ok-soft);color:var(--ok);}
.status-paid::before{background:var(--ok);}
.status-due{background:var(--warn-soft);color:var(--warn);}
.status-due::before{background:var(--warn);}

/* ---------- Tables ---------- */
.table-card{overflow:hidden;}
.table-wrap{overflow-x:auto;}
table.dtable{width:100%;margin:0;border-collapse:separate;border-spacing:0;min-width:760px;}
.dtable thead th{
  font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);font-weight:600;
  background:#FAFAFD;padding:11px 16px;border-bottom:1px solid var(--line);white-space:nowrap;
}
.dtable tbody td{padding:12px 16px;border-bottom:1px solid var(--line);vertical-align:middle;font-size:13.4px;}
.dtable tbody tr:last-child td{border-bottom:none;}
.dtable tbody tr:hover{background:#FAFAFD;}
.row-photo{width:34px;height:34px;border-radius:9px;object-fit:cover;flex-shrink:0;}
.cell-name{font-weight:600;}
.cell-sub{color:var(--muted);font-size:12px;}
.act-btn{
  width:31px;height:31px;border-radius:8px;border:1px solid var(--line);background:#fff;color:var(--muted);
  display:inline-flex;align-items:center;justify-content:center;font-size:13.5px;transition:.15s;
}
.act-btn:hover{background:var(--accent-soft);color:var(--accent-ink);border-color:var(--accent-soft);}
.act-btn.danger:hover{background:var(--bad-soft);color:var(--bad);border-color:var(--bad-soft);}
.act-btn.ok:hover{background:var(--ok-soft);color:var(--ok);border-color:var(--ok-soft);}

/* ---------- Filter bar ---------- */
.filter-bar{
  background:var(--card-bg);border:1px solid var(--line);border-radius:var(--radius);
  padding:14px 16px;box-shadow:var(--shadow);
}
.form-label{font-size:12px;font-weight:600;color:var(--muted);margin-bottom:5px;}
.form-control,.form-select{
  border-radius:var(--radius-sm);border-color:var(--line);font-size:13.5px;padding:9px 12px;
}
.form-control:focus,.form-select:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-soft);}

/* ---------- Buttons ---------- */
.btn{border-radius:var(--radius-sm);font-size:13.5px;font-weight:600;padding:9px 16px;}
.btn-accent{background:var(--accent);border-color:var(--accent);color:#fff;}
.btn-accent:hover{background:var(--accent-ink);border-color:var(--accent-ink);color:#fff;}
.btn-outline-accent{border-color:var(--line);color:var(--ink);background:#fff;}
.btn-outline-accent:hover{background:#F0F0F7;color:var(--ink);}
.btn-soft-danger{background:var(--bad-soft);color:var(--bad);border:1px solid var(--bad-soft);}
.btn-soft-danger:hover{background:var(--bad);color:#fff;}

/* ---------- Upload box ---------- */
.upload-box{
  border:1.5px dashed var(--line);border-radius:var(--radius);padding:22px;text-align:center;
  background:#FAFAFD;cursor:pointer;transition:.15s;
}
.upload-box:hover{border-color:var(--accent);background:var(--accent-soft);}
.upload-preview{position:relative;display:inline-block;}
.upload-preview img{width:100%;max-width:220px;border-radius:var(--radius);border:1px solid var(--line);object-fit:cover;}
.upload-remove{
  position:absolute;top:-8px;right:-8px;width:26px;height:26px;border-radius:50%;background:var(--bad);color:#fff;
  border:2px solid #fff;display:flex;align-items:center;justify-content:center;font-size:12px;
}
.avatar-upload{width:110px;height:110px;border-radius:50%;}

/* ---------- Page nav / header ---------- */
.page-header{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;}
.page-header h2{font-size:21px;font-weight:700;margin:0;}
.page-header p{color:var(--muted);font-size:13px;margin:2px 0 0;}

/* Course info card */
.course-thumb{width:100%;height:150px;object-fit:cover;border-radius:var(--radius) var(--radius) 0 0;}
.meta-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px dashed var(--line);font-size:13px;}
.meta-row:last-child{border-bottom:none;}
.meta-row span:first-child{color:var(--muted);}
.meta-row span:last-child{font-weight:600;}

/* Gallery grid */
.gallery-card{overflow:hidden;position:relative;}
.gallery-card img{width:100%;height:150px;object-fit:cover;display:block;transition:transform .3s ease;}
.gallery-card:hover img{transform:scale(1.06);}
.gallery-overlay{
  position:absolute;inset:0;background:linear-gradient(0deg,rgba(10,10,20,.75),transparent 55%);
  display:flex;align-items:flex-end;padding:10px;opacity:0;transition:opacity .2s;
}
.gallery-card:hover .gallery-overlay{opacity:1;}

/* Nav pills */
.seg{display:inline-flex;background:#F0F0F7;border-radius:10px;padding:3px;}
.seg button{border:none;background:transparent;padding:6px 14px;font-size:12.5px;font-weight:600;border-radius:8px;color:var(--muted);}
.seg button.active{background:#fff;color:var(--ink);box-shadow:0 1px 3px rgba(0,0,0,.08);}

.empty-state{padding:46px 20px;text-align:center;color:var(--muted);}
.empty-state i{font-size:34px;opacity:.35;}

.page-sec{display:none;animation:fadein .25s ease;}
.page-sec.show{display:block;}
@keyframes fadein{from{opacity:0;transform:translateY(4px);}to{opacity:1;transform:translateY(0);}}

.modal-content{border-radius:16px;border:none;}
.modal-header{border-bottom:1px solid var(--line);}
.modal-footer{border-top:1px solid var(--line);}

::-webkit-scrollbar{height:8px;}
.offcanvas-dark{background:linear-gradient(190deg,var(--side-1),var(--side-2));color:#fff;width:270px;}
.offcanvas-dark .side-scroll{padding:6px 14px 14px;}

/* ---------- Laravel additions (same design language) ---------- */
.flash{border-radius:var(--radius);padding:12px 16px;font-size:13.5px;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:18px;border:1px solid transparent;}
.flash-ok{background:var(--ok-soft);color:var(--ok);border-color:var(--ok-soft);}
.flash-bad{background:var(--bad-soft);color:var(--bad);border-color:var(--bad-soft);}
.invalid-feedback{font-size:12px;}
.form-control.is-invalid,.form-select.is-invalid{border-color:var(--bad);}
.table-foot{border-top:1px solid var(--line);padding:12px 16px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;font-size:12.5px;color:var(--muted);}
.pager{display:flex;gap:4px;flex-wrap:wrap;margin:0;padding:0;list-style:none;}
.pager a,.pager span{min-width:31px;height:31px;padding:0 9px;border-radius:8px;border:1px solid var(--line);background:#fff;color:var(--ink);display:inline-flex;align-items:center;justify-content:center;font-size:12.5px;font-weight:600;}
.pager a:hover{background:var(--accent-soft);color:var(--accent-ink);border-color:var(--accent-soft);}
.pager .on{background:var(--accent);border-color:var(--accent);color:#fff;}
.pager .off{color:#B9BBCC;}
.notify-menu{width:320px;padding:0;overflow:hidden;}
.notify-menu .n-head{padding:12px 14px;border-bottom:1px solid var(--line);font-family:'Sora';font-weight:600;font-size:13.5px;}
.notify-menu .n-item{display:block;padding:10px 14px;border-bottom:1px solid var(--line);color:var(--ink);font-size:12.8px;}
.notify-menu .n-item:hover{background:#FAFAFD;}
.notify-menu .n-item:last-child{border-bottom:none;}
/* extra status colors (existing project data) */
.status-due,.status-new,.status-partial{background:var(--warn-soft);color:var(--warn);}
.status-due::before,.status-new::before,.status-partial::before{background:var(--warn);}
.status-paid,.status-enrolled{background:var(--ok-soft);color:var(--ok);}
.status-paid::before,.status-enrolled::before{background:var(--ok);}
.status-contacted{background:var(--accent-soft);color:var(--accent-ink);}
.status-contacted::before{background:var(--accent);}
.status-failed{background:var(--bad-soft);color:var(--bad);}
.status-failed::before{background:var(--bad);}
button.side-link{width:100%;border:none;background:transparent;text-align:left;}
/* Order "Deleted" status */
.status-deleted{background:var(--off-soft);color:var(--off);text-decoration:line-through;}
.status-deleted::before{background:var(--off);}
/* Add Payment — student picker */
.pick-list{max-height:360px;overflow-y:auto;border:1px solid var(--line);border-radius:var(--radius-sm);}
.pick-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-bottom:1px solid var(--line);cursor:pointer;transition:background .12s;}
.pick-item:last-child{border-bottom:none;}
.pick-item:hover{background:#FAFAFD;}
.pick-item.on{background:var(--accent-soft);}
.pick-item img{width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;}
.due-box{border-radius:var(--radius);padding:14px;text-align:center;border:1px solid var(--line);}
.due-box .v{font-family:'Sora';font-weight:700;font-size:19px;}
</style>
