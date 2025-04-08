const express = require('express');
const path = require('path');
const { pool, testConnection } = require('./db');
const app = express();
const PORT = process.env.PORT || 3000;

// Set up template engine (if using EJS)
app.set('view engine', 'ejs');

// Middleware
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Test database connection on startup
(async function() {
  await testConnection();
})();

// Routes
app.get('/', async (req, res) => {
  try {
    // Example query to get featured tracks
    const [featuredTracks] = await pool.query(
      'SELECT * FROM tracks WHERE featured = 1 LIMIT 3'
    );
    res.render('index', { 
      title: 'Music Portfolio', 
      featuredTracks 
    });
  } catch (error) {
    console.error('Error fetching featured tracks:', error);
    res.render('index', { 
      title: 'Music Portfolio', 
      featuredTracks: [] 
    });
  }
});

app.get('/music', async (req, res) => {
  try {
    // Get all tracks from database
    const [tracks] = await pool.query('SELECT * FROM tracks ORDER BY release_date DESC');
    res.render('music', { title: 'My Music', tracks });
  } catch (error) {
    console.error('Error fetching tracks:', error);
    res.render('music', { title: 'My Music', tracks: [] });
  }
});

// More routes...

// Export the app instance
module.exports = app;
