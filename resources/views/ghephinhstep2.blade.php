<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover,user-scalable=no" />
<title>Phần chơi ghép hình</title>
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<style>
  :root{
    /* lưới mảnh */
    --cols: 5;
    --rows: 2;
    /* khay mảnh (số cột/hàng tự tính từ JS để toàn bộ mảnh lọt 1 màn) */
    --tray-cols: 5;
    --tray-rows: 2;

    /* layout 1 màn hình (điện thoại) */
    --header-h: 60px;
    --preview-h: 22vh;
    --board-h: 43vh;
    --tray-h: 35vh;

    --gap: 8px;
    --radius: 14px;

    --bg1:#0b1220; --bg2:#0f1830; --bd:#1c2b4d; --fg:#eef3ff;
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
    color:var(--fg); background:var(--bg1); -webkit-tap-highlight-color:transparent;
  }

  /* Header (rất gọn để chừa chỗ cho 3 section trong 1 màn) */
  header{
    height:var(--header-h); display:flex; align-items:center; justify-content:space-between;
    padding:0 10px; gap:8px; border-bottom:1px solid var(--bd); background:rgba(11,18,32,.7);
    backdrop-filter: blur(6px);
  }
  header h1{font-size:16px; margin:0; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
  .controls{display:flex; align-items:center; gap:6px; flex-wrap:nowrap}
  button, label.btn, .chip{
    appearance:none; border:1px solid #2a3d6a; background:#1a2a50; color:#e7efff;
    padding:8px 10px; border-radius:10px; font-weight:700; line-height:1; cursor:pointer;
    transition:transform .05s ease, background .2s ease, opacity .2s ease; white-space:nowrap;
  }
  button:hover, label.btn:hover, .chip:hover{background:#223767}
  button:active, label.btn:active, .chip:active{transform:translateY(1px)}
  button[disabled]{opacity:.55; cursor:not-allowed}
  input[type=file]{display:none}
  .timer{font-variant-numeric:tabular-nums; font-weight:800; padding:6px 10px; border:1px solid var(--bd); border-radius:10px; background:#111a2e; min-width:70px; text-align:center}
  .sel-wrap{display:flex; align-items:center; gap:6px}
  .sel-wrap select, .sel-wrap input[type=number]{
    height:32px; border-radius:8px; border:1px solid #2a3d6a; background:#0f1830; color:#e7efff; padding:0 8px; width:74px;
  }
  .sound{display:flex; align-items:center; gap:6px; font-weight:600}
  .sound input{accent-color:#4ae28a; width:18px; height:18px}

  /* Main – 3 section không cuộn */
  .main{height:calc(100vh - var(--header-h)); display:grid; grid-template-rows: var(--preview-h) var(--board-h) var(--tray-h); gap:10px; padding:10px}
  .card{background:var(--bg2); border:1px solid var(--bd); border-radius:var(--radius); padding:8px; display:flex; flex-direction:column; min-height:0}
  .title{font-size:13px; font-weight:800; opacity:.9; margin:0 0 6px 0}

  /* Section 1: Preview */
  .preview-img{flex:1; overflow:hidden; border-radius:10px; border:1px solid var(--bd)}
  .preview-img img{width:100%; height:100%; object-fit:cover; display:block}

  /* Section 2: Board */
  .board-wrap{flex:1; display:flex; flex-direction:column; gap:6px; min-height:0}
  .slots{flex:1; display:grid; gap:var(--gap); grid-template-columns:repeat(var(--cols),1fr); grid-template-rows:repeat(var(--rows),1fr); touch-action:none; border-radius:12px}
  .slot{
    position:relative; border:1px dashed #2b3f70; border-radius:10px; overflow:hidden;
    background:linear-gradient(135deg,rgba(255,255,255,.04),transparent 55%); touch-action:none;
    min-height:0; /* để grid co giãn */
  }
  .slot.occupied{border-style:solid}
  .slot.correct{outline:2px solid #3bd16f; outline-offset:-2px}
  .help{font-size:12px; opacity:.8}

  /* Section 3: Tray – luôn hiển thị đầy đủ trong màn hình */
  .tray-grid{
    flex:1; display:grid; gap:var(--gap);
    grid-template-columns:repeat(var(--tray-cols),1fr);
    grid-template-rows:repeat(var(--tray-rows),1fr);
    touch-action:none; min-height:0;
  }

  /* Piece */
  .piece{
    width:100%; height:100%; background-size:cover; background-repeat:no-repeat; background-origin:border-box;
    border-radius:8px; cursor:grab; touch-action:none;
  }
  .dragging{
    position:fixed; left:0; top:0; width:0; height:0; pointer-events:none;
    transform:translate(-50%,-50%); z-index:9999; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,.35)
  }
  .ghost{opacity:.35}

  /* Desktop mở rộng (tùy chọn) */
  @media (min-width: 1024px){
    :root{ --preview-h: 22vh; --board-h: 48vh; --tray-h: 30vh; }
  }
</style>
</head>
<body>
<header>
  <p>Quý khách chọn Bắt đầu để chơi</p>
  <div class="controls">
    <span class="timer" id="timer">00:00</span>
    <button id="startBtn">Bắt đầu</button>
    <button style="display: none;" id="resetBtn" disabled>Chơi lại</button>
    <label style="display: none;" class="btn" for="fileInput">Ảnh…</label><input id="fileInput" type="file" accept="image/*" />
    <div class="sel-wrap">
      <select id="piecePreset" title="Chọn số mảnh nhanh">
        <!-- <option value="4">4</option>
        <option value="6">6</option>
        <option value="8">8</option>
        <option value="9" selected>9</option>
        <option value="10">10</option> -->
        <option value="12">12</option>
        <!-- <option value="15">15</option>
        <option value="16">16</option> -->
      </select>
      <input style="display: none;" id="customPieces" type="number" min="2" max="100" step="1" placeholder="#" title="Tự nhập số mảnh" />
      <button style="display: none;" id="applyPieces" class="chip" title="Áp dụng số mảnh">Áp dụng</button>
    </div>
    <label style="display: none;" class="sound"><input id="soundToggle" type="checkbox" checked/>Âm thanh</label>
  </div>
</header>

<main class="main">
  <!-- Section 1: Preview -->
  <section class="card">
    <p class="title">1) Ảnh xem trước</p>
    <div class="preview-img"><img id="previewImg" alt="Xem trước"></div>
  </section>

  <!-- Section 2: Board -->
  <section class="card">
    <p class="title">2) Bảng ghép</p>
    <div id="slots" class="slots" aria-label="Bảng ghép"></div>
    <div class="help">Kéo một mảnh lên ô đã có mảnh để <b>đổi chỗ</b>.</div>
  </section>

  <!-- Section 3: Tray -->
  <section class="card">
    <p class="title">3) Khay mảnh (đã trộn)</p>
    <div id="tray" class="tray-grid" aria-label="Khay mảnh"></div>
  </section>
</main>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
$(document).ready(function(){
/* =================== CẤU HÌNH ÂM THANH =================== */
/* Điền URL âm thanh nếu muốn (có công tắc bật/tắt trong header) */
const SOUND_URLS = {
  pick:    "{{asset('images/sound/click.mp3')}}",   // URL âm thanh khi nhấc mảnh
  place:   "{{asset('images/sound/place.mp3')}}",   // URL âm thanh khi đặt mảnh
  correct: "{{asset('images/sound/correct.mp3')}}",   // URL âm thanh khi đặt ĐÚNG vào board
  win:     "{{asset('images/sound/win.mp3')}}"   // URL âm thanh khi hoàn thành
};

/* ============== TRẠNG THÁI & THAM CHIẾU DOM ============== */
let COLS = 3, ROWS = 3;             // mặc định 9 mảnh
const slotsEl    = document.getElementById('slots');
const trayEl     = document.getElementById('tray');
const timerEl    = document.getElementById('timer');
const startBtn   = document.getElementById('startBtn');
const resetBtn   = document.getElementById('resetBtn');
const fileInput  = document.getElementById('fileInput');
const previewImg = document.getElementById('previewImg');
const soundToggle= document.getElementById('soundToggle');
const presetSel  = document.getElementById('piecePreset');
const customInp  = document.getElementById('customPieces');
const applyBtn   = document.getElementById('applyPieces');

let imgURL = null;
let started = false;
let startTime = 0;
let timerHandle = null;
let pieceSize = { w: 0, h: 0 };
let bgSize    = { w: 0, h: 0 };
let dragState = { active:false, piece:null, fromSlot:null, ghost:null };
const TOTAL = () => COLS * ROWS;

/* ============== ÂM THANH ============== */
const sounds = {};
function initSounds(){
  for (const [k,url] of Object.entries(SOUND_URLS)){
    if (url){
      const a = new Audio(url);
      a.preload = "auto";
      sounds[k] = a;
    }
  }
}
function playSound(name){
  if (!soundToggle.checked) return;
  const s = sounds[name];
  if (s){ try{ s.currentTime = 0; s.play(); }catch(e){} }
}

/* ============== ĐỒNG HỒ ============== */
function formatMMSS(ms){ const t=Math.floor(ms/1000),mm=String(Math.floor(t/60)).padStart(2,'0'),ss=String(t%60).padStart(2,'0'); return `${mm}:${ss}` }
function startTimer(){ startTime=Date.now(); timerHandle=setInterval(()=>timerEl.textContent=formatMMSS(Date.now()-startTime),250) }
function stopTimer(){ if (timerHandle){ clearInterval(timerHandle); timerHandle=null; } }
function resetTimer(){ stopTimer(); timerEl.textContent="00:00" }

/* ============== ẢNH ============== */
// const DEFAULT_IMG = "{{asset('images/ghephinh/hyundai.jpg')}}";
const DEFAULT_IMG = "{{$data['hinhNenGhepHinh']}}";
function setImage(url){ imgURL=url; previewImg.src=url; previewImg.onload=()=>buildBoardAndPieces() }

/* ============== TÍNH LƯỚI TỐI ƯU TỪ SỐ MẢNH ============== */
/* Chọn (rows, cols) gần hình vuông nhất & ưu tiên cols >= rows */
function bestGrid(n){
  let best = {r:1,c:n,score:Infinity};
  for (let r=1; r<=n; r++){
    if (n % r !== 0) continue;
    const c = n / r;
    const score = Math.abs(c - r); // càng gần vuông càng tốt
    if (score < best.score || (score===best.score && c>=r)) best = {r,c,score};
  }
  // nếu n là số nguyên tố -> chia gần sqrt
  if (!Number.isInteger(best.c)){
    const c = Math.ceil(Math.sqrt(n));
    const r = Math.ceil(n / c);
    return {r, c};
  }
  return {r:best.r, c:best.c};
}
/* Tính khay mảnh để ALL mảnh lọt ngay trong vùng tray */
function computeTrayGrid(n){
  const c = Math.ceil(Math.sqrt(n));
  const r = Math.ceil(n / c);
  return {r, c};
}

/* ============== DỰNG UI ============== */
function clearChildren(el){ while(el.firstChild) el.removeChild(el.firstChild) }

function buildBoardAndPieces(){
  // slots (board)
  clearChildren(slotsEl);
  for (let i=0;i<TOTAL();i++){
    const slot=document.createElement('div');
    slot.className='slot';
    slot.dataset.index=String(i);
    slotsEl.appendChild(slot);
  }
  // tray
  clearChildren(trayEl);
  for (let i=0;i<TOTAL();i++){
    const tslot=document.createElement('div');
    tslot.className='slot';
    tslot.dataset.index='tray-'+i;
    const piece=document.createElement('div');
    piece.className='piece';
    piece.dataset.pieceIndex=String(i);
    piece.addEventListener('pointerdown', onPointerDown);
    tslot.appendChild(piece);
    trayEl.appendChild(tslot);
  }
  // cập nhật CSS grid counts để khay vừa 1 màn
  const tg = computeTrayGrid(TOTAL());
  document.documentElement.style.setProperty('--tray-cols', tg.c);
  document.documentElement.style.setProperty('--tray-rows', tg.r);

  applyBackgrounds();
  started=false;
  startBtn.disabled=false;
  resetBtn.disabled=true;
  resetTimer();
}

function applyBackgrounds(){
  // tính theo kích thước vùng board hiện tại
  const rect = slotsEl.getBoundingClientRect();
  // giữ đúng tỉ lệ theo cols/rows để tile hoàn hảo
  bgSize.w = rect.width;
  bgSize.h = rect.height;
  pieceSize.w = bgSize.w / COLS;
  pieceSize.h = bgSize.h / ROWS;

  for (const piece of document.querySelectorAll('.piece')){
    const idx = Number(piece.dataset.pieceIndex);
    const row = Math.floor(idx / COLS);
    const col = idx % COLS;
    piece.style.backgroundImage = `url("${imgURL}")`;
    piece.style.backgroundSize = `${bgSize.w}px ${bgSize.h}px`;
    piece.style.backgroundPosition = `${-col*pieceSize.w}px ${-row*pieceSize.h}px`;
  }
}

/* ============== TRỘN / KIỂM TRA THẮNG ============== */
function shuffle(arr){ for(let i=arr.length-1;i>0;i--){ const j=(Math.random()*(i+1))|0; [arr[i],arr[j]]=[arr[j],arr[i]] } return arr }
function startGame(){
  const pieces = Array.from(document.querySelectorAll('.piece'));
  const traySlots = Array.from(trayEl.querySelectorAll('.slot'));
  for (const t of traySlots){ t.innerHTML=''; t.classList.remove('occupied') }
  shuffle(pieces).forEach((p,i)=>{ traySlots[i].appendChild(p); traySlots[i].classList.add('occupied') });
  for (const s of slotsEl.querySelectorAll('.slot')) s.classList.remove('occupied','correct');
  started=true; startBtn.disabled=true; resetBtn.disabled=false; resetTimer(); startTimer();
}
function resetGame(){ stopTimer(); resetTimer(); buildBoardAndPieces() }

function applyCorrectHighlights(){
  for (const slot of slotsEl.querySelectorAll('.slot')){
    slot.classList.remove('correct');
    const idx=Number(slot.dataset.index);
    const piece=slot.querySelector('.piece');
    if (piece && Number(piece.dataset.pieceIndex)===idx) slot.classList.add('correct');
  }
}
function checkWin(){
  for (const slot of slotsEl.querySelectorAll('.slot')){
    const idx=Number(slot.dataset.index);
    const piece=slot.querySelector('.piece');
    if (!piece || Number(piece.dataset.pieceIndex)!==idx) return false;
  }
  return true;
}

/* ============== POINTER DRAG & DROP (cảm ứng/chuột) ============== */
function onPointerDown(e){
  e.preventDefault();
  const piece = e.currentTarget;
  const fromSlot = piece.parentElement;
  dragState.active = true; dragState.piece = piece; dragState.fromSlot = fromSlot;

  // ghost
  const r = piece.getBoundingClientRect();
  const ghost = piece.cloneNode(true);
  ghost.classList.add('dragging');
  ghost.style.width = r.width+'px';
  ghost.style.height= r.height+'px';
  ghost.style.left = e.clientX+'px';
  ghost.style.top  = e.clientY+'px';
  document.body.appendChild(ghost);
  dragState.ghost = ghost;

  piece.classList.add('ghost');
  piece.setPointerCapture(e.pointerId);
  playSound('pick');

  piece.addEventListener('pointermove', onPointerMove);
  piece.addEventListener('pointerup', onPointerUp, {once:true});
  piece.addEventListener('pointercancel', onPointerCancel, {once:true});
}
function onPointerMove(e){
  if (!dragState.active || !dragState.ghost) return;
  dragState.ghost.style.left = e.clientX+'px';
  dragState.ghost.style.top  = e.clientY+'px';
}
function onPointerUp(e){
  if (!dragState.active) return;
  const dropSlot = findSlotAtPoint(e.clientX, e.clientY);
  finalizeDrop(dropSlot);
  cleanupPointer(e);
}
function onPointerCancel(e){ finalizeDrop(null); cleanupPointer(e) }

function cleanupPointer(e){
  if (dragState.piece){
    try{ dragState.piece.releasePointerCapture(e.pointerId) }catch(_){}
    dragState.piece.removeEventListener('pointermove', onPointerMove);
  }
  if (dragState.ghost) dragState.ghost.remove();
  if (dragState.piece) dragState.piece.classList.remove('ghost');
  dragState = { active:false, piece:null, fromSlot:null, ghost:null };
}
function findSlotAtPoint(x,y){
  let el = document.elementFromPoint(x,y);
  if (!el) return null;
  if (el.classList.contains('piece')) el = el.parentElement;
  while (el && !el.classList?.contains('slot')) el = el.parentElement;
  return el && el.classList.contains('slot') ? el : null;
}

function setCompleteTime(timeCompleted) {
    $.ajax({
        url: "{{route('setcomplete')}}",
        type: "post",
        dataType: "json",
        data: {
            "_token": "{{csrf_token()}}",
            "timeCompleted": timeCompleted
        },
        success: function(response) {
            console.log(response);
        },
        error: function() {
        }
    });
}

function finalizeDrop(targetSlot){
  const piece = dragState.piece, from = dragState.fromSlot;
  if (!piece || !from){ return; }
  if (!targetSlot){
    from.appendChild(piece); from.classList.add('occupied'); playSound('place'); return;
  }
  if (targetSlot.firstElementChild){
    const swapPiece = targetSlot.firstElementChild;
    targetSlot.replaceChild(piece, swapPiece);
    from.appendChild(swapPiece);
    playSound('place');
  }else{
    targetSlot.appendChild(piece);
    playSound('place');
  }
  targetSlot.classList.add('occupied');
  if (!from.firstElementChild) from.classList.remove('occupied');

  // highlight/âm thanh
  if (targetSlot.parentElement === slotsEl){
    const idx = Number(targetSlot.dataset.index);
    if (Number(piece.dataset.pieceIndex)===idx){ applyCorrectHighlights(); playSound('correct'); }
    else applyCorrectHighlights();
  }else applyCorrectHighlights();

  if (started && checkWin()){
    stopTimer();
    for (const s of slotsEl.querySelectorAll('.slot')) s.classList.add('correct');
    playSound('win');
    setTimeout( () => {
      setCompleteTime(timerEl.textContent);
      alert(`🎉 Chúc mừng Quý khách đã hoàn thành trong thời gian ${timerEl.textContent}. Tiếp theo hãy thực hiện khảo sát của Hyundai An Giang để hoàn tất phần chơi. Cảm ơn quý khách!`);
      open("{{route('khaosat.ghephinh')}}",'_self');
    }, 80);
  }
}

/* ============== THAY ĐỔI SỐ MẢNH ============== */
function setPiecesByCount(n){
  n = Math.max(2, Math.min(100, Number(n)||9)); // giới hạn hợp lý
  // chọn lưới đẹp nhất
  const g = bestGrid(n);
  ROWS = g.r; COLS = g.c;
  document.documentElement.style.setProperty('--rows', ROWS);
  document.documentElement.style.setProperty('--cols', COLS);
  buildBoardAndPieces();
}

/* ============== SỰ KIỆN UI ============== */
startBtn.addEventListener('click', startGame);
resetBtn.addEventListener('click', resetGame);
fileInput.addEventListener('change', (e)=>{
  const f=e.target.files?.[0]; if (!f) return;
  const url=URL.createObjectURL(f); setImage(url);
});
window.addEventListener('resize', ()=>applyBackgrounds());

presetSel.addEventListener('change', ()=> setPiecesByCount(Number(presetSel.value)));
applyBtn.addEventListener('click', ()=>{
  const v = customInp.value ? Number(customInp.value) : Number(presetSel.value);
  setPiecesByCount(v);
});

/* ============== KHỞI ĐỘNG ============== */
initSounds();
setImage(DEFAULT_IMG);
setPiecesByCount(Number(presetSel.value)); // mặc định theo preset (9)
});
</script>
</body>
</html>
