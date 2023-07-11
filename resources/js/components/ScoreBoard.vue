<template>
    <!-- Component for displaying a scoreboard -->
    <div id="scoreboard">
      <h2>Scores Top 10</h2>
      <!-- Loop through scores and display player names and scores -->
      <div v-for="score in scores" :key="score.id">
        Player {{ score.player }}: {{ score.score }}
      </div>
    </div>
  </template>
   
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        // Array to store the scores data
        scores: [],
      };
    },
  
    async created() {
      // When the component is created, fetch the scores data
      await this.fetchScores();
    },
  
    methods: {
      async fetchScores() {
        // Make an asynchronous request to the API endpoint to fetch scores data
        const response = await axios.get('http://127.0.0.1:8000/api/scores');
        
        // Update the scores array with the data received from the API response
        this.scores = response.data;
        
        // Log the scores data to the console
        console.log(this.scores);
      },
    },
  };
  </script>
  
  <!-- The <template> section defines the structure and content of the component. It includes an HTML container with the id "scoreboard" that displays the top 10 scores fetched from the API. It uses the v-for directive to iterate over the scores array and display each player's name and score.
    The <script> section contains the JavaScript logic for the component. It imports the axios library for making HTTP requests. The component is exported as the default export.
    Inside the data function, the scores array is initialized as an empty array. This array will store the fetched scores data.
    The created lifecycle hook is used to fetch the scores data when the component is created. It calls the fetchScores method.
    The fetchScores method is an asynchronous function that uses axios to make a GET request to the specified API endpoint (http:127.0.0.1:8000/api/scores). It awaits the response and assigns the response data to the scores array. Finally, it logs the scores data to the console. -->