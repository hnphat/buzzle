<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<title>Phần chơi ghép hình</title>
<style>
  :root {
    --cols: 5;
    --rows: 2;
    --gap: 8px;
    --board-w: min(90vw, 900px);
    --tray-h: 280px;
    --radius: 14px;
  }
  * { box-sizing: border-box; }
  html, body { height: 100%; }
  body {
    font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
    margin: 0;
    background: #0b1220;
    color: #eef3ff;
    line-height: 1.45;
    -webkit-tap-highlight-color: transparent;
  }
  header { padding: 18px 20px 8px; }
  h1 { font-size: clamp(18px, 2.8vw, 26px); margin: 0 0 6px; font-weight: 700; }
  .bar {
    display: flex; flex-wrap: wrap; gap: 10px 12px; align-items: center; margin-top: 8px;
  }
  .timer {
    font-variant-numeric: tabular-nums; font-weight: 700;
    background: #111a2e; padding: 8px 12px; border-radius: 10px;
    min-width: 84px; text-align: center; border: 1px solid #1c2b4d;
  }
  .controls { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  button, label.btn, .toggle {
    appearance: none; border: 1px solid #2a3d6a; background: #1a2a50; color: #e7efff;
    padding: 10px 14px; border-radius: 12px; cursor: pointer; font-weight: 700;
    transition: transform .05s ease, background .2s ease, border .2s ease, opacity .2s ease;
    user-select: none; line-height: 1;
  }
  button:hover, label.btn:hover, .toggle:hover { background: #223767; }
  button:active, label.btn:active, .toggle:active { transform: translateY(1px); }
  button[disabled] { opacity: .55; cursor: not-allowed; }
  input[type="file"] { display: none; }
  .wrap { max-width: var(--board-w); margin: 14px auto 28px; padding: 0 14px 18px; }
  .board-and-tray { display: grid; grid-template-columns: 1fr; gap: 14px; }
  .board, .tray, .preview {
    background: #0f1830; border: 1px solid #1c2b4d; border-radius: var(--radius); padding: 12px;
  }
  .section-title { font-weight: 700; margin: 4px 0 10px; opacity: .9; font-size: 14px; letter-spacing: .2px; }
  /* Board */
  .slots {
    width: 100%;
    aspect-ratio: calc(var(--cols) / var(--rows));
    display: grid; gap: var(--gap);
    grid-template-columns: repeat(var(--cols), 1fr);
    grid-template-rows: repeat(var(--rows), 1fr);
    touch-action: none; /* quan trọng cho cảm ứng */
  }
  .slot {
    position: relative; border: 1px dashed #2b3f70; border-radius: 10px; overflow: hidden;
    background: linear-gradient(135deg, rgba(255,255,255,.04), rgba(255,255,255,0) 55%);
    touch-action: none; /* ngăn trình duyệt cuộn trong thao tác kéo */
  }
  .slot.occupied { border-style: solid; }
  .slot.correct { outline: 2px solid #3bd16f; outline-offset: -2px; }
  /* Tray */
  .tray-grid {
    width: 100%; min-height: var(--tray-h); display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: var(--gap);
    touch-action: none;
  }
  .tray .slot { min-height: 120px; aspect-ratio: 1.2 / 1; }
  /* Piece */
  .piece {
    width: 100%; height: 100%;
    background-size: cover; background-repeat: no-repeat; background-origin: border-box;
    border-radius: 8px; cursor: grab; touch-action: none;
  }
  .dragging {
    position: fixed; left: 0; top: 0; width: 0; height: 0; pointer-events: none;
    transform: translate(-50%, -50%); z-index: 9999; border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,.35);
  }
  .ghost { opacity: .35; }
  .preview .img { width: 100%; border-radius: 10px; border: 1px solid #1c2b4d; overflow: hidden; }
  .help { opacity: .8; font-size: 13px; margin-top: 6px; }
  .toggle { display: inline-flex; align-items: center; gap: 8px; }
  .toggle input { accent-color: #4ae28a; width: 18px; height: 18px; }
  @media (min-width: 980px) { .board-and-tray { grid-template-columns: 1.1fr .9fr; } }
</style>
</head>
<body>
  <header>
    <h1>PHẦN CHƠI GHÉP HÌNH</h1>
    <h5>Quý khách hãy nhấn Bắt đầu để bắt đầu phần chơi, hoặc Chơi lại để trộn các mảnh ghép</h5>
    <div class="bar">
      <div class="controls">
        <button id="startBtn">Bắt đầu</button>
        <button id="resetBtn" disabled>Chơi lại</button>
        <label class="btn" for="fileInput" style="display:none;">Chọn ảnh…</label>
        <input id="fileInput" type="file" accept="image/*" />
        <label class="toggle" title="Bật/Tắt âm thanh" style="display:none;">
          <input id="soundToggle" type="checkbox" checked />
          Âm thanh
        </label>
      </div>
      <div class="timer" id="timer">00:00</div>
    </div>
  </header>

  <div class="wrap">
    <div class="board-and-tray">
      <section class="board">
        <div class="section-title">Bảng ghép (Hãy đặt mảnh vào đúng vị trí)</div>
        <div id="slots" class="slots" aria-label="Khu vực đặt mảnh"></div>
        <div class="help">Mẹo: Kéo một mảnh lên ô đã có mảnh để <b>đổi chỗ</b>.</div>
      </section>

      <section class="tray">
        <div class="section-title">Khay mảnh đã trộn (bắt đầu ở đây)</div>
        <div id="tray" class="tray-grid" aria-label="Khay mảnh ban đầu"></div>
        <div class="help">Nhấn <b>Bắt đầu</b> để trộn mảnh và chạy đồng hồ. Quý khách có thể chơi lại bất cứ lúc nào.</div>
      </section>
    </div>

    <section class="preview" style="margin-top:16px;">
      <div class="section-title">Xem trước bức hình hoàn chỉnh</div>
      <div class="img">
        <img id="previewImg" alt="Ảnh xem trước" style="display:block;width:100%;height:auto;">
      </div>
    </section>
  </div>

<script>
/*** Cấu hình ************************************************************/
const COLS = 5, ROWS = 2, TOTAL = COLS * ROWS;

/* ĐIỀN ĐƯỜNG DẪN ÂM THANH TẠI ĐÂY (tùy chọn)
   Ví dụ:
   pick:   "https://example.com/sounds/pick.mp3",
   place:  "https://example.com/sounds/place.mp3",
   correct:"https://example.com/sounds/correct.mp3",
   win:    "https://example.com/sounds/win.mp3",
*/
const SOUND_URLS = {
  pick:    "{{asset('images/sound/click.mp3')}}",   // URL âm thanh khi nhấc mảnh
  place:   "{{asset('images/sound/place.mp3')}}",   // URL âm thanh khi đặt mảnh
  correct: "{{asset('images/sound/correct.mp3')}}",   // URL âm thanh khi đặt ĐÚNG vào board
  win:     "{{asset('images/sound/win.mp3')}}"   // URL âm thanh khi hoàn thành
};
const DEFAULT_IMG = "{{asset('images/ghephinh/hyundai.jpg')}}"; // Ảnh mặc định khi load trang
  
/*** Biến trạng thái *****************************************************/
const slotsEl   = document.getElementById('slots');
const trayEl    = document.getElementById('tray');
const startBtn  = document.getElementById('startBtn');
const resetBtn  = document.getElementById('resetBtn');
const timerEl   = document.getElementById('timer');
const fileInput = document.getElementById('fileInput');
const previewImg = document.getElementById('previewImg');
const soundToggle = document.getElementById('soundToggle');

let imgURL = null;
let started = false;
let startTime = 0;
let timerHandle = null;

let pieceSize = { w: 0, h: 0 };
let bgSize    = { w: 0, h: 0 };

let dragState = {
  active: false,
  piece: null,
  fromSlot: null,
  ghost: null
};

/*** Âm thanh *************************************************************/
const sounds = {};
function initSounds() {
  for (const [k, url] of Object.entries(SOUND_URLS)) {
    if (url) {
      const a = new Audio(url);
      a.preload = "auto";
      sounds[k] = a;
    }
  }
}
function playSound(name) {
  if (!soundToggle.checked) return;
  const s = sounds[name];
  if (s) { try { s.currentTime = 0; s.play(); } catch(e){} }
}

/*** Đồng hồ **************************************************************/
function formatMMSS(ms) {
  const total = Math.floor(ms / 1000);
  const mm = String(Math.floor(total / 60)).padStart(2, '0');
  const ss = String(total % 60).padStart(2, '0');
  return `${mm}:${ss}`;
}
function startTimer() {
  startTime = Date.now();
  timerHandle = setInterval(() => {
    timerEl.textContent = formatMMSS(Date.now() - startTime);
  }, 250);
}
function stopTimer() { if (timerHandle) { clearInterval(timerHandle); timerHandle = null; } }
function resetTimer() { stopTimer(); timerEl.textContent = "00:00"; }

/*** Khởi tạo ảnh *********************************************************/
function setImage(url) {
  imgURL = url;
  previewImg.src = url;
  previewImg.onload = () => buildBoardAndPieces();
}

/*** Dựng UI **************************************************************/
function clearChildren(el) { while (el.firstChild) el.removeChild(el.firstChild); }

function buildBoardAndPieces() {
  clearChildren(slotsEl);
  for (let i = 0; i < TOTAL; i++) {
    const slot = document.createElement('div');
    slot.className = 'slot';
    slot.dataset.index = String(i);
    slotsEl.appendChild(slot);
  }
  clearChildren(trayEl);
  for (let i = 0; i < TOTAL; i++) {
    const tslot = document.createElement('div');
    tslot.className = 'slot';
    tslot.dataset.index = 'tray-' + i;
    const piece = document.createElement('div');
    piece.className = 'piece';
    piece.dataset.pieceIndex = String(i);
    // đăng ký Pointer Events
    piece.addEventListener('pointerdown', onPointerDown);
    tslot.appendChild(piece);
    trayEl.appendChild(tslot);
  }
  applyBackgrounds();
  started = false;
  startBtn.disabled = false;
  resetBtn.disabled = true;
  resetTimer();
}

function applyBackgrounds() {
  const rect = slotsEl.getBoundingClientRect();
  bgSize.w = rect.width;
  bgSize.h = rect.width * (ROWS / COLS);
  pieceSize.w = bgSize.w / COLS;
  pieceSize.h = bgSize.h / ROWS;
  for (const piece of document.querySelectorAll('.piece')) {
    const idx = Number(piece.dataset.pieceIndex);
    const row = Math.floor(idx / COLS);
    const col = idx % COLS;
    piece.style.backgroundImage = `url("${imgURL}")`;
    piece.style.backgroundSize = `${bgSize.w}px ${bgSize.h}px`;
    piece.style.backgroundPosition = `${-col * pieceSize.w}px ${-row * pieceSize.h}px`;
  }
}

/*** Trộn & kiểm tra thắng ************************************************/
function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = (Math.random() * (i + 1)) | 0;
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}
function startGame() {
  const pieces = Array.from(document.querySelectorAll('.piece'));
  const traySlots = Array.from(trayEl.querySelectorAll('.slot'));
  for (const t of traySlots) { t.innerHTML = ''; t.classList.remove('occupied'); }
  shuffle(pieces);
  pieces.forEach((p, i) => { traySlots[i].appendChild(p); traySlots[i].classList.add('occupied'); });
  for (const s of slotsEl.querySelectorAll('.slot')) s.classList.remove('occupied','correct');
  started = true;
  startBtn.disabled = true;
  resetBtn.disabled = false;
  resetTimer(); startTimer();
}
function resetGame() { stopTimer(); resetTimer(); buildBoardAndPieces(); }

function applyCorrectHighlights() {
  for (const slot of slotsEl.querySelectorAll('.slot')) {
    slot.classList.remove('correct');
    const idx = Number(slot.dataset.index);
    const piece = slot.querySelector('.piece');
    if (piece && Number(piece.dataset.pieceIndex) === idx) slot.classList.add('correct');
  }
}
function checkWin() {
  for (const slot of slotsEl.querySelectorAll('.slot')) {
    const idx = Number(slot.dataset.index);
    const piece = slot.querySelector('.piece');
    if (!piece || Number(piece.dataset.pieceIndex) !== idx) return false;
  }
  return true;
}

/*** Pointer-based Drag & Drop ********************************************/
function onPointerDown(e) {
  e.preventDefault();
  const piece = e.currentTarget;
  const fromSlot = piece.parentElement;
  dragState.active = true;
  dragState.piece = piece;
  dragState.fromSlot = fromSlot;

  // Tạo "ghost" kéo theo ngón tay/chuột
  const rect = piece.getBoundingClientRect();
  const ghost = piece.cloneNode(true);
  ghost.classList.add('dragging');
  ghost.style.width = rect.width + 'px';
  ghost.style.height = rect.height + 'px';
  ghost.style.left = e.clientX + 'px';
  ghost.style.top = e.clientY + 'px';
  document.body.appendChild(ghost);
  dragState.ghost = ghost;

  piece.classList.add('ghost');
  piece.setPointerCapture(e.pointerId);

  playSound('pick');

  piece.addEventListener('pointermove', onPointerMove);
  piece.addEventListener('pointerup', onPointerUp, { once: true });
  piece.addEventListener('pointercancel', onPointerCancel, { once: true });
}
function onPointerMove(e) {
  if (!dragState.active || !dragState.ghost) return;
  dragState.ghost.style.left = e.clientX + 'px';
  dragState.ghost.style.top  = e.clientY + 'px';
}
function onPointerUp(e) {
  if (!dragState.active) return;
  const dropSlot = findSlotAtPoint(e.clientX, e.clientY);
  finalizeDrop(dropSlot);
  cleanupPointer(e);
}
function onPointerCancel(e) {
  finalizeDrop(null); // thả vào hư không -> trả về chỗ cũ
  cleanupPointer(e);
}
function cleanupPointer(e) {
  if (dragState.piece) {
    try { dragState.piece.releasePointerCapture(e.pointerId); } catch(_) {}
    dragState.piece.removeEventListener('pointermove', onPointerMove);
  }
  if (dragState.ghost) dragState.ghost.remove();
  if (dragState.piece) dragState.piece.classList.remove('ghost');
  dragState = { active:false, piece:null, fromSlot:null, ghost:null };
}

function findSlotAtPoint(x, y) {
  let el = document.elementFromPoint(x, y);
  if (!el) return null;
  // nếu là piece thì leo lên slot cha
  if (el.classList.contains('piece')) el = el.parentElement;
  // tìm slot gần nhất
  while (el && !el.classList?.contains('slot')) el = el.parentElement;
  return el && el.classList.contains('slot') ? el : null;
}

function finalizeDrop(targetSlot) {
  const piece = dragState.piece;
  const from  = dragState.fromSlot;
  if (!piece || !from) return;

  if (!targetSlot) {
    // không thả vào slot -> trả về chỗ cũ
    from.appendChild(piece);
    from.classList.add('occupied');
    playSound('place');
    return;
  }

  // Cho phép thả vào bất kỳ slot (board hoặc tray). Nếu đã có mảnh -> hoán đổi.
  if (targetSlot.firstElementChild) {
    const swapPiece = targetSlot.firstElementChild;
    targetSlot.replaceChild(piece, swapPiece);
    from.appendChild(swapPiece);
    playSound('place');
  } else {
    targetSlot.appendChild(piece);
    playSound('place');
  }
  targetSlot.classList.add('occupied');
  if (!from.firstElementChild) from.classList.remove('occupied');

  // Nếu thả vào board -> highlight đúng sai, âm thanh "correct" khi đúng
  if (targetSlot.parentElement === slotsEl) {
    const idx = Number(targetSlot.dataset.index);
    if (Number(piece.dataset.pieceIndex) === idx) {
      applyCorrectHighlights();
      playSound('correct');
    } else {
      applyCorrectHighlights();
    }
  } else {
    applyCorrectHighlights();
  }

  if (started && checkWin()) {
    stopTimer();
    for (const s of slotsEl.querySelectorAll('.slot')) s.classList.add('correct');
    playSound('win');
    setTimeout(() => {
      alert(`🎉 Tuyệt vời! Quý khách đã ghép xong trong ${timerEl.textContent}. Vui lòng thực hiện khảo sát của Hyundai An Giang để hoàn tất phần chơi!`);
    }, 80);
  }
}

/*** Sự kiện UI ***********************************************************/
startBtn.addEventListener('click', startGame);
resetBtn.addEventListener('click', resetGame);
fileInput.addEventListener('change', (e) => {
  const f = e.target.files?.[0];
  if (!f) return;
  const url = URL.createObjectURL(f);
  setImage(url);
});

window.addEventListener('resize', () => applyBackgrounds());

/*** Khởi động ************************************************************/
initSounds();
setImage(DEFAULT_IMG);
</script>
</body>
</html>
