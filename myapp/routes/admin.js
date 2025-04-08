const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// Admin dashboard
router.get('/', async (req, res) => {
  try {
    const [tracks] = await pool.query('SELECT id, title, genre, release_date FROM tracks ORDER BY release_date DESC');
    const [messageCount] = await pool.query('SELECT COUNT(*) as count FROM messages WHERE read = 0');
    
    res.render('admin/dashboard', { 
      title: 'Admin Dashboard',
      tracks,
      unreadMessages: messageCount[0].count
    });
  } catch (error) {
    console.error('Error fetching admin data:', error);
    res.render('admin/dashboard', { 
      title: 'Admin Dashboard',
      tracks: [],
      unreadMessages: 0
    });
  }
});

// Add track form
router.get('/add-track', (req, res) => {
  res.render('admin/add-track', { 
    title: 'Add New Track',
    error: null,
    success: null
  });
});

// Process add track form
router.post('/add-track', async (req, res) => {
  try {
    const { 
      title, 
      genre, 
      description, 
      release_date, 
      soundcloud_url, 
      soundcloud_embed_code,
      featured 
    } = req.body;
    
    // Basic validation
    if (!title || !soundcloud_url) {
      return res.render('admin/add-track', { 
        title: 'Add New Track',
        error: 'Title and SoundCloud URL are required',
        success: null
      });
    }
    
    const isFeatured = featured === 'on' ? 1 : 0;
    
    // Insert the track into the database
    await pool.query(
      'INSERT INTO tracks (title, genre, description, release_date, soundcloud_url, soundcloud_embed_code, featured) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [title, genre, description, release_date, soundcloud_url, soundcloud_embed_code, isFeatured]
    );
    
    res.render('admin/add-track', {
      title: 'Add New Track',
      error: null,
      success: 'Track added successfully!'
    });
  } catch (error) {
    console.error('Error adding track:', error);
    res.render('admin/add-track', { 
      title: 'Add New Track',
      error: 'Failed to add track. Please try again.',
      success: null
    });
  }
});

// More admin routes here (edit track, delete track, messages, etc.)

module.exports = router;