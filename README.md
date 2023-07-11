Clone the Laravel Project

1. First, you should have a copy of the Laravel project on your local machine. You can clone the project from your Git repository using the git clone command, for example: git clone https://github.com/yourusername/yourlaravelproject.git.
Navigate into the project directory: cd yourlaravelproject.
2. Install Composer Dependencies.
Laravel projects usually have a number of PHP dependencies that need to be installed using Composer. Run the following command in the project directory: composer install.
3. Install NPM Dependencies.
Vue.js and its dependencies in Laravel projects are managed through NPM (Node Package Manager). Run the following command to install these dependencies: npm install.
4. Generate an application key: Laravel requires an application key for encrypting user sessions and other sensitive data. Generate it with this command: php artisan key:generate.
5. Compile the assets: Use the Laravel Mix to compile and run all your JavaScript and CSS files. This will compile your Vue.js components into a single JavaScript file: npm run dev.
6. Run migrations run them with this command: php artisan migrate.
7. Start the local development server: Start Laravel's built-in local development server: php artisan serve.

Now, you should be able to access the project at http://127.0.0.1:8000 in your web browser. Make sure your Vue.js components are loaded correctly.

Remember, every time you make changes to your Vue.js components, you need to recompile the assets with npm run dev or use npm run watch to automatically compile your assets whenever changes are made.


![image](https://github.com/KhanyisoK/tic-tac-toe/assets/38207293/393fe2c6-62a0-4ca4-9ea6-21278aebc7c4)

AI LOGIC ON THE CLIENT SIDE

The AI logic for games like Tic-Tac-Toe is often implemented on the front end (client side) for Simplicity and Efficiency. Tic-Tac-Toe is a relatively simple game with limited possible moves, which means the AI logic isn't computationally intensive. It's more efficient to handle this on the client side and avoid extra server requests.
