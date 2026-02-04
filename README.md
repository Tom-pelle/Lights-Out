# Lights Out 

**Lights Out** is a web-based memory game built with PHP, designed to provide a complete interactive experience with user authentication and data persistence.
Developed as an academic project to demonstrate core full-stack web development concepts.

##  How to Play
The game is based on the classic **Memory** mechanics:
1. You are presented with a grid of hidden cards.
2. The goal is to uncover all matching pairs by remembering their positions.
3. Try to complete the level in the **fewest moves possible** to climb the leaderboard!

##  Key Features
- **User System**: Secure Registration and Login to access your personal profile.
- **Game Save State**: You can pause a match and resume it later exactly where you left off.
- **Global Leaderboard**: Compete with other users and see who completed the game with the fewest moves.
- **Responsive Interface**: Polished design using CSS and smooth JavaScript interactions.

##  Project Structure
The code is organized modularly:
- **`Lights Out/`**: Application root.
  - `index.php`: Main entry point (landing/login page).
  - `pages/`: Internal pages (signup, game dashboard, leaderboard).
  - `phputils/`: PHP libraries for database connection and session management.
  - `css/` & `img/`: Frontend static assets.
  - `documentazione.html`: Detailed technical documentation.

##  Installation & Setup

### Prerequisites
- Web Server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL Database

### Local Setup
1. Clone the repository into your web server's root folder (e.g., `htdocs` for XAMPP or `/var/www/html`):
   
   git clone https://github.com/Tom-pelle/Lights-Out.git

2. Import the database schema (refer to `documentazione.html` for the table structure).
3. Configure the database connection by editing **`Lights Out/phputils/dbparams.php`** with your local credentials.
4. Start your server and navigate to `http://localhost/Lights-Out`.

## 👥 Authors
- **Tommaso Pellegrini** - [Tom-pelle](https://github.com/Tom-pelle)
