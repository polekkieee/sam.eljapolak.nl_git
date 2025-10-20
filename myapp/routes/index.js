const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// Home page
router.get('/', async (req, res) => {
  try {
    const [featuredTracks] = await pool.query(
      'SELECT * FROM tracks WHERE featured = 1 ORDER BY release_date DESC LIMIT 3'
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

// About page
router.get('/about', (req, res) => {
  res.render('about', { title: 'About Me' });
});

module.exports = router;