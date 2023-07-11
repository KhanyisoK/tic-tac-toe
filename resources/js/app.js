// Importing the necessary dependencies
import './bootstrap'; // Importing the bootstrap styles
import { createApp } from 'vue'; // Importing the createApp function from Vue framework

const app = createApp({}); // Creating a new Vue application instance

import GameBoardComponent from './components/GameBoard.vue'; // Importing the GameBoard component
import ScoreBoardComponent from './components/ScoreBoard.vue'; // Importing the ScoreBoard component

// Registering the components globally
app.component('game-board-component', GameBoardComponent); 
app.component('score-board-component', ScoreBoardComponent);

app.mount('#app'); // Mounting the application to the element with the id 'app'
