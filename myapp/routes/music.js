const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// Music listing page
router.get('/', async (req, res) => {
  try {
    const [tracks] = await pool.query('SELECT * FROM tracks ORDER BY release_date DESC');
    console.log('Fetched tracks:', tracks); // Debugging line
    res.render('music', { title: 'My Music', tracks });
  } catch (error) {
    console.log('Error fetching tracks:', error);
    res.render('music', { title: 'My Music', tracks: [] });
  }
});

// Individual track page
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

// -------------------
// API Endpoint
// -------------------
router.get('/api/tracks', async (req, res) => {
  try {
    let { limit, exclude } = req.query;

    // Default limit to 10 if not provided
    limit = parseInt(limit) || 10;

    // Convert exclude to array of integers if provided
    let excludeIds = [];
    if (exclude) {
      excludeIds = exclude.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));
    }

    // Build query
    let query = 'SELECT * FROM tracks';
    let params = [];

    if (excludeIds.length > 0) {
      query += ` WHERE id NOT IN (${excludeIds.map(() => '?').join(',')})`;
      params = excludeIds;
    }

    query += ' ORDER BY release_date DESC LIMIT ?';
    params.push(limit);

    const [tracks] = await pool.query(query, params);

    res.json(tracks);
  } catch (error) {
    console.error('Error fetching API tracks:', error);
    res.status(500).json({ error: 'Failed to fetch tracks' });
  }
});

module.exports = router;
