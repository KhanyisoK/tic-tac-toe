    <!-- Specify the viewport settings for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Include the JavaScript file generated by Vite bundler -->
    @vite('resources/js/app.js')
    
    <!-- Set the title of the page -->
    <title>Laravel</title>

    <!-- Include Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    
</head>
<body id="app">
    <!-- Create a row with two columns -->
    <div class="row">
        <div class="column" style="margin-right: 100px;  margin-top: 20px">
            <!-- Include the 'game-board-component' -->
            <game-board-component ></game-board-component>
        </div>
        <div class="column" style="margin-right: 10px;">
            <!-- Include the 'score-board-component' -->
            <score-board-component ></score-board-component>
        </div>
    </div>
    
    
</body>