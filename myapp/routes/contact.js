const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// Contact form
router.get('/', (req, res) => {
  res.render('contact', { 
    title: 'Contact Me',
    success: null,
    error: null
  });
});

// Process contact form
router.post('/', async (req, res) => {
  try {
    const { name, email, subject, message } = req.body;
    
    // Basic validation
    if (!name || !email || !message) {
      return res.render('contact', {
        title: 'Contact Me',
        error: 'Please fill out all required fields',
        success: null
      });
    }
    
    // Save message to database
    await pool.query(
      'INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)',
      [name, email, subject, message]
    );
    
    res.render('contact', {
      title: 'Contact Me',
      success: 'Your message has been sent! I\'ll get back to you soon.',
      error: null
    });
  } catch (error) {
    console.error('Error saving contact message:', error);
    res.render('contact', {
      title: 'Contact Me',
      error: 'Failed to send message. Please try again later.',
      success: null
    });
  }
});

module.exports = router;