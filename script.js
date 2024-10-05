const sudokuPuzzle = [
    [0, 0, 0, 0, 2, 0, 0, 1, 6, 3, 0, 4, 5, 0, 0, 0],
    [0, 4, 0, 3, 0, 0, 0, 8, 0, 0, 0, 0, 5, 0, 1, 2],
    [0, 2, 0, 0, 1, 0, 0, 7, 0, 4, 3, 0, 0, 9, 0, 0],
    [6, 3, 0, 7, 0, 4, 0, 0, 0, 0, 0, 0, 8, 2, 0, 0],
    [0, 1, 0, 6, 0, 0, 2, 0, 0, 0, 5, 7, 0, 4, 0, 8],
    [0, 8, 5, 0, 0, 6, 0, 0, 2, 1, 9, 0, 7, 0, 0, 4],
    [0, 0, 6, 0, 3, 0, 0, 0, 0, 0, 0, 1, 0, 2, 0, 9],
    [0, 0, 0, 1, 4, 0, 3, 5, 9, 0, 0, 0, 0, 0, 0, 8],
    [3, 5, 0, 4, 0, 0, 7, 0, 2, 6, 0, 0, 9, 0, 1, 0],
    [0, 0, 1, 0, 8, 0, 4, 0, 0, 0, 6, 5, 0, 0, 3, 7],
    [7, 6, 9, 0, 0, 3, 0, 0, 1, 8, 0, 2, 0, 4, 5, 9],
    [0, 8, 0, 9, 0, 0, 0, 0, 5, 0, 0, 3, 6, 0, 2, 7],
    [0, 0, 0, 5, 7, 2, 0, 6, 3, 0, 0, 0, 0, 0, 0, 1],
    [4, 0, 0, 0, 0, 5, 0, 1, 7, 8, 9, 0, 0, 0, 6, 0],
    [0, 0, 7, 8, 0, 0, 0, 4, 0, 0, 2, 0, 6, 5, 1, 0],
    [0, 0, 2, 6, 9, 1, 0, 3, 0, 0, 4, 8, 7, 0, 0, 0],
];

const sudokuBoard = document.getElementById("sudoku-board");
const checkButton = document.getElementById("check-solution");
const resetButton = document.getElementById("reset");

function drawSudoku() {
    sudokuBoard.innerHTML = ""; // Clear the board
    for (let row = 0; row < sudokuPuzzle.length; row++) {
        const tr = document.createElement("tr");
        for (let col = 0; col < sudokuPuzzle[row].length; col++) {
            const td = document.createElement("td");
            if (sudokuPuzzle[row][col] === 0) {
                const input = document.createElement("input");
                input.type = "text";
                input.maxLength = "2"; // Allow only one character input
                td.appendChild(input);
            } else {
                td.innerText = sudokuPuzzle[row][col];
            }
            tr.appendChild(td);
        }
        sudokuBoard.appendChild(tr);
    }
}

function checkSolution() {
    const inputs = document.querySelectorAll("input[type='text']");
    let isCorrect = true;

    inputs.forEach((input, index) => {
        const row = Math.floor(index / 16);
        const col = index % 16;
        if (input.value !== "" && parseInt(input.value) !== sudokuPuzzle[row][col]) {
            isCorrect = false;
            input.style.backgroundColor = "red"; // Highlight incorrect inputs
        } else {
            input.style.backgroundColor = ""; // Reset style for correct inputs
        }
    });

    if (isCorrect) {
        alert("Congratulations! You solved the Sudoku!");
    }
}

function resetGame() {
    drawSudoku(); // Redraw the original puzzle
}

// Initialize the Sudoku board
drawSudoku();

// Add event listeners
checkButton.addEventListener("click", checkSolution);
resetButton.addEventListener("click", resetGame);
