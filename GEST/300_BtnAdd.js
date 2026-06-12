const BtnAdd = document.getElementById('ID_GestAddBtn');
const SecCnt = document.getElementById('ID_DIVCNT');

let inTrascino = false;

let startX = parent.innerWidth*0.8; 
let startY = parent.innerHeight*0.8;

let currentX = parent.innerWidth*0.8;
let currentY = parent.innerHeight*0.8;

// Dimensione della griglia di aggancio (es. si aggancia ogni 40 pixel)
const snapGrid = 40; 

// Funzione unica per gestire l'inizio del tocco o del click
function inizioTrascino(e) 
{
  inTrascino = true;
            
  // Gestione sia di mouse (clientX) che di touch (touches[0].clientX)
  const clientX = e.touches ? e.touches[0].clientX : e.clientX;
  const clientY = e.touches ? e.touches[0].clientY : e.clientY;

  startX = clientX - currentX;
  startY = clientY - currentY;
}

// Funzione unica per gestire il movimento
function inMovimento(e) 
{
  if (!inTrascino) return;

  const clientX = e.touches ? e.touches[0].clientX : e.clientX;
  const clientY = e.touches ? e.touches[0].clientY : e.clientY;

  let x = clientX - startX;
  let y = clientY - startY;

  // Limiti del SecCnt
  //const maxX = SecCnt.clientWidth - BtnAdd.clientWidth;
  //const maxY = SecCnt.clientHeight - BtnAdd.clientHeight;
  const maxX = parent.innerWidth - BtnAdd.clientWidth;
  const maxY = parent.innerHeight - BtnAdd.clientHeight;

  // Vincolo entro i confini
  if (x < 0) x = 0;
  if (y < 0) y = 0;
  if (x > maxX) x = maxX;
  if (y > maxY) y = maxY;

  currentX = x;
  currentY = y;

  aggiornaPosizione(currentX, currentY);
}

// Funzione unica per il rilascio (Applica l'effetto Snap)
function fineTrascino() 
{
  if (!inTrascino) return;
  inTrascino = false;

  // Calcolo del punto di aggancio più vicino (arrotondamento alla griglia)
  currentX = Math.round(currentX / snapGrid) * snapGrid;
  currentY = Math.round(currentY / snapGrid) * snapGrid;

  // Controllo finale per evitare che lo snap porti l'immagine fuori dal bordo destro/basso
  const maxX = SecCnt.clientWidth - BtnAdd.clientWidth;
  const maxY = SecCnt.clientHeight - BtnAdd.clientHeight;
  if (currentX > maxX) currentX = Math.round(maxX / snapGrid) * snapGrid;
  if (currentY > maxY) currentY = Math.round(maxY / snapGrid) * snapGrid;

  // Applica la posizione finale agganciata
  aggiornaPosizione(currentX, currentY);
}

 function aggiornaPosizione(x, y) 
{
  BtnAdd.style.left = x + 'px';
  BtnAdd.style.top = y + 'px';
}

// Eventi MOUSE
BtnAdd.addEventListener('mousedown', inizioTrascino);
document.addEventListener('mousemove', inMovimento);
document.addEventListener('mouseup', fineTrascino);

// Eventi TOUCH (Mobile)
BtnAdd.addEventListener('touchstart', inizioTrascino, { passive: true });
document.addEventListener('touchmove', inMovimento, { passive: false });
document.addEventListener('touchend', fineTrascino);
