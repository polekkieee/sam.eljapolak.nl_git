const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// Music listing page
router.get('/', async (req, res) => {
  try {
    const [tracks] = await pool.query('SELECT * FROM tracks ORDER BY release_date DESC');
    res.render('music', { title: 'My Music', tracks });
  } catch (error) {
    console.error('Error fetching tracks:', error);
    res.render('music', { title: 'My Music', tracks: [] });
  }
});

// Individual track page (if needed)
router.get('/:id', async (req, res) => {
  try {
    const [tracks] = await pool.query('SELECT * FROM tracks WHERE id = ?', [req.params.id]);
    
    if (tracks.length === 0) {
      return res.status(404).render('404', { title: 'Track Not Found' });
    }
    
    res.render('track-detail', { 
      title: tracks[0].title, 
      track: tracks[0] 
    });
  } catch (error) {
    console.error('Error fetching track:', error);
    res.status(500).render('error', { 
      title: 'Error', 
      message: 'Failed to load track' 
    });
  }
});

module.exports = router;