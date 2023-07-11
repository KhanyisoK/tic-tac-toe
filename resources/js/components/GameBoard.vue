<template>
    <div id="game-board">
        <button @click="toggleAI">
            Switch to {{ isPlayingWithAI ? 'Player' : 'AI' }} mode
        </button>
        <br />
        <div>
            <label for="player1">Player 1:</label>
            <input id="player1" v-model="player1" type="text" @input="validateInput" />
        </div>
        <div>
            <label for="player2">Player 2:</label>
            <input id="player2" v-model="player2" type="text"  @input="validateInput" v-if="!isPlayingWithAI" />
        </div>
        <br />
        <div class="row" v-for="(row, i) in board" :key="i">
            <div id="board" class="cell" :class="{ 'winning-cell': isWinningCell(i, j), disabled: !isValid() }" v-for="(cell, j) in row" :key="j"
                @click="makeMove(i, j)">
                {{ cell }}
            </div>
        </div>
        <button @click="resetBoard">Reset</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            board: Array(3).fill().map(() => Array(3).fill(null)), // Initialize the game board as a 3x3 array filled with null values
            playerTurn: 'X', // Current player turn, starts with player 'X'
            winningCells: [], // Stores the winning cells for highlighting
            aiPlayer: 'O', // Represents the AI player, uses 'O'
            isPlayingWithAI: false, // Indicates if the game is being played against AI
            player1: '', // Name of Player 1
            player2: '', // Name of Player 2
            isValidPlayerNames: false,
        };
    },
    methods: {
        validateInput() {
            this.isValidPlayerNames = this.player1.trim() !== '' && (this.player2.trim() !== '' || this.isPlayingWithAI);
        },
        isValid() {
            return this.isValidPlayerNames;
            },
        async makeMove(i, j) {
            if (!this.board[i][j] && !this.checkWin()) { // If the selected cell is empty and there is no winner yet
                this.board[i][j] = this.playerTurn; // Set the selected cell with the current player's mark
                if (this.checkWin()) { // If the current player wins after the move
                    await this.incrementScore(this.playerTurn); // Increment the score of the winning player
                    alert(`Player ${this.playerTurn} has won!`); // Show an alert indicating the winner
                    this.$emit('game-won', this.playerTurn); // Emit a custom event to notify the parent component of the game result
                    await this.fetchScores(); // Fetch and update the scores from the server
                    location.reload(); // Reload the page to start a new game
                } else if (this.isPlayingWithAI) { // If playing against AI
                    this.playerTurn = this.playerTurn === 'X' ? 'O' : 'X'; // Switch the player turn
                    this.makeRandomMove(); // Make a move for the AI player
                } else {
                    this.playerTurn = this.playerTurn === 'X' ? 'O' : 'X'; // Switch the player turn
                }
            }
        },
        makeRandomMove() {
            let emptyCells = [];
            for (let i = 0; i < this.board.length; i++) {
                for (let j = 0; j < this.board[i].length; j++) {
                    if (this.board[i][j] === null) {
                        emptyCells.push([i, j]); // Collect all empty cells on the board
                    }
                }
            }

            if (emptyCells.length > 0) {
                const randomIndex = Math.floor(Math.random() * emptyCells.length);
                const [i, j] = emptyCells[randomIndex];
                this.board[i][j] = this.playerTurn; // Set the randomly selected empty cell with the current player's mark

                if (this.checkWin()) { // If the AI player wins after the move
                    this.playerTurn = this.playerTurn === 'X' ? 'O' : 'X'; // Switch the player turn
                    this.incrementScore(this.playerTurn); // Increment the score of the winning player
                    alert(`Player ${this.playerTurn} has won!`); // Show an alert indicating the winner
                    this.resetBoard(); // Reset the board to start a new game
                } else {
                    this.playerTurn = this.playerTurn === 'X' ? 'O' : 'X'; // Switch the player turn
                }
            }
        },
        async incrementScore(player) {
            let playerName;
            if (player === 'X') {
                playerName = this.player1;
            } else {
                playerName = this.isPlayingWithAI ? 'AI' : this.player2;
            }
            await axios.post('http://127.0.0.1:8000/api/scores', { // Send a POST request to update the player's score on the server
                player: playerName,
                score: 1
            });

        },
        isWinningCell(i, j) {
            return this.winningCells.some(cells => cells[0] === i && cells[1] === j); // Check if the given cell is part of the winning cells
        },
        checkWin() {
            const lines = [
                // Horizontal lines
                [[0, 0], [0, 1], [0, 2]],
                [[1, 0], [1, 1], [1, 2]],
                [[2, 0], [2, 1], [2, 2]],

                // Vertical lines
                [[0, 0], [1, 0], [2, 0]],
                [[0, 1], [1, 1], [2, 1]],
                [[0, 2], [1, 2], [2, 2]],

                // Diagonal lines
                [[0, 0], [1, 1], [2, 2]],
                [[0, 2], [1, 1], [2, 0]],
            ];

            for (const line of lines) {
                const [cell1, cell2, cell3] = line;
                if (
                    this.board[cell1[0]][cell1[1]] &&
                    this.board[cell1[0]][cell1[1]] === this.board[cell2[0]][cell2[1]] &&
                    this.board[cell1[0]][cell1[1]] === this.board[cell3[0]][cell3[1]]
                ) {
                    return this.board[cell1[0]][cell1[1]]; // Return the winning player mark (either 'X' or 'O')
                }
            }

            // Check for a draw
            if (this.board.flat().every(cell => cell !== null)) { // If all cells are filled and there is no winner, it's a draw
                alert("It's a draw!");
                this.resetBoard(); // Reset the board to start a new game
                return null;
            }

            return null; // Return null if there is no winner yet
        },
        resetBoard() {
            this.board = [
                ['', '', ''],
                ['', '', ''],
                ['', '', ''],
            ];
            this.currentPlayer = 'X'; // Reset the current player to 'X'
            this.winningCells = []; // Clear the winning cells
        },
        async fetchScores() {
            const response = await axios.get('http://127.0.0.1:8000/api/scores'); // Fetch the scores from the server
            this.scores = response.data; // Update the scores data in the component
            console.log(this.scores);
        },
        toggleAI() {
            this.isPlayingWithAI = !this.isPlayingWithAI; // Toggle the AI mode
            if (this.isPlayingWithAI && this.playerTurn === this.aiPlayer) {
                this.makeRandomMove(); // If playing against AI and it's AI's turn, make the AI move
            }
        },
    },
};
</script>

<style>
.row {
    display: flex;
    height: 50px; /* Add this line to increase the height of each row */
}

.cell {
    border: 1px solid black;
    width: 50px; /* Add this line to increase the width of each column */
    height: 50px; /* Increase the height of each column */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.winning-cell {
    background-color: green; /* Style for the winning cells */
}

#board.disabled {
  pointer-events: none;
  opacity: 0.5;
}
</style>


<!-- The code above is a Vue.js component for a tic-tac-toe game. It represents the game board, handles player moves, checks for winning conditions, and interacts with an AI player. The code also includes some UI elements for player input and a button to reset the game.
The component's data consists of the game board represented by a 2D array (board), the current player turn (playerTurn), the winning cells (winningCells), the AI player mark (aiPlayer), a flag indicating whether the game is played against AI (isPlayingWithAI), and the names of Player 1 and Player 2 (player1 and player2).
The component contains several methods:
makeMove(i, j): Handles a player's move on the game board. If the selected cell is empty and there is no winner yet, it updates the board with the player's mark, checks for a win, and makes a move for the AI player if applicable.
makeRandomMove(): Makes a random move for the AI player. It collects all empty cells on the board, randomly selects one, and updates the board with the AI player's mark. It then checks for a win and switches the player turn.
incrementScore(player): Increments the score of the winning player. It sends a POST request to a server API to update the player's score.
isWinningCell(i, j): Checks if a given cell is part of the winning cells. It returns true if the cell coordinates match any of the winning combinations.
checkWin(): Checks if there is a winner on the game board. It defines all possible winning combinations and checks if any of them have the same mark ('X' or 'O') in all three cells. It also checks for a draw if all cells are filled without a winner.
resetBoard(): Resets the game board, current player, and winning cells to start a new game.
fetchScores(): Fetches the scores from a server API and updates the component's scores data.
toggleAI(): Toggles the AI mode. If playing against AI and it's the AI player's turn, it makes the AI move.
The component also includes a <style> block for styling the game board and winning cells. -->