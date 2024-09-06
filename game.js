const canvas = document.getElementById('musicCanvas');
const ctx = canvas.getContext('2d');
const scoreBoard = document.getElementById('scoreBoard');
const scoreText = document.getElementById('scoreText');

let currentMode = '';
let score = 0;

const notes = ['B', 'A', 'G', 'F', 'E'];

const audioFiles = {
    'B': new Audio('sounds/B.mp3'),
    'A': new Audio('sounds/A.mp3'),
    'G': new Audio('sounds/G.mp3'),
    'F': new Audio('sounds/F.mp3'),
    'E': new Audio('sounds/E.mp3')
};

// เสียงดนตรีพื้นหลัง
const backgroundAudio = new Audio('sounds/background.mp3');
backgroundAudio.loop = true;  // ให้เสียงเล่นซ้ำอย่างต่อเนื่อง
backgroundAudio.volume = 0.1;  // ตั้งค่าเสียงเบา (0.0 ถึง 1.0)

function drawLines() {
    for (let i = 0; i < 5; i++) {
        let y = 50 + i * 30;
        ctx.beginPath();
        ctx.moveTo(50, y);
        ctx.lineTo(750, y);
        ctx.stroke();
    }
}

function showNoteAndPlaySound(event) {
    if (currentMode === 'learn') {
        let y = event.offsetY;
        let noteIndex = Math.floor((y - 35) / 30);

        if (noteIndex >= 0 && noteIndex < notes.length) {
            let note = notes[noteIndex];
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawLines();
            drawNoteOnLine(noteIndex);
            showNoteLabel(noteIndex, event.offsetX, y);
        }
    }
}

function drawNoteOnLine(noteIndex) {
    let y = 50 + noteIndex * 30;
    ctx.beginPath();
    ctx.arc(400, y, 15, 0, Math.PI * 2);
    ctx.fillStyle = 'black';
    ctx.fill();
    ctx.stroke();
    ctx.font = '20px Arial';
    ctx.fillText(notes[noteIndex], 425, y + 5);
}

function showNoteLabel(noteIndex, x, y) {
    ctx.font = '16px Arial';
    ctx.fillStyle = 'red';
    ctx.textAlign = 'center';
    ctx.fillText(notes[noteIndex], x, y - 10);
}

function playSoundOnClick(event) {
    if (currentMode === 'learn' || currentMode === 'game') {
        let y = event.offsetY;
        let noteIndex = Math.floor((y - 35) / 30);

        if (noteIndex >= 0 && noteIndex < notes.length) {
            let note = notes[noteIndex];
            audioFiles[note].play();

            if (currentMode === 'game') {
                score++;
                scoreText.innerText = `คะแนน: ${score}`;
            }
        }
    }
}

function switchMode(mode) {
    currentMode = mode;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (mode === 'learn') {
        canvas.style.display = 'block';
        scoreBoard.style.display = 'none';
        drawLines();
        backgroundAudio.pause();  // หยุดเสียงพื้นหลังถ้าไม่ได้อยู่ในโหมดเกม
    } else if (mode === 'game') {
        canvas.style.display = 'block';
        scoreBoard.style.display = 'block';
        score = 0;
        scoreText.innerText = 'คะแนน: 0';
        drawLines();
        backgroundAudio.play();  // เล่นเสียงพื้นหลังเมื่อเข้าโหมดเกม
    } else if (mode === 'score') {
        canvas.style.display = 'none';
        scoreBoard.style.display = 'block';
        scoreText.innerText = `คะแนนที่คุณได้: ${score}`;
        backgroundAudio.pause();  // หยุดเสียงพื้นหลังเมื่อเข้าสู่โหมดคะแนน
    }
}

canvas.addEventListener('mousemove', showNoteAndPlaySound);
canvas.addEventListener('click', playSoundOnClick);
