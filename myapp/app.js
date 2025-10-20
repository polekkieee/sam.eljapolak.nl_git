const fs = require('fs');
const logStream = fs.createWriteStream('app.log', { flags: 'a' });
console.log = function (message) {
  logStream.write(`${new Date().toISOString()} - ${message}\n`);
};

const express = require('express');
const path = require('path');
const session = require('express-session');
const { pool, testConnection } = require('./db');

// Import routers
const indexRouter = require('./routes/index');
const musicRouter = require('./routes/music');
const adminRouter = require('./routes/admin');
const soundcloudRouter = require('./routes/soundcloud');
const contactRouter = require('./routes/contact');
const { isAuthenticated } = require('./middleware/auth');

// Create Express application
const app = express();

// Set up template engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Middleware
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Session management
app.use(session({
  secret: process.env.SESSION_SECRET || 'your-secure-session-secret',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 3600000 } // 1 hour
}));

// Test database connection on startup
(async function() {
  await testConnection();
})();

// Login routes (kept in app.js for simplicity)
app.get('/login', (req, res) => {
  res.render('admin/login', { title: 'Admin Login', error: null });
});

app.post('/login', (req, res) => {
  const { password } = req.body;
  const adminPassword = process.env.ADMIN_PASSWORD || 'your-secure-password';
  
  if (password === adminPassword) {
    req.session.isAuthenticated = true;
    res.redirect('/admin');
  } else {
    res.render('admin/login', { title: 'Admin Login', error: 'Invalid password' });
  }
});

app.get('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/');
});

// Set up routers
app.use('/', indexRouter);
app.use('/music', musicRouter);
app.use('/admin', isAuthenticated, adminRouter);
app.use('/admin', isAuthenticated, soundcloudRouter); 
app.use('/contact', contactRouter);

// 404 handler
app.use((req, res) => {
  res.status(404).render('404', { title: 'Page Not Found' });
});

// Error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).render('error', { 
    title: 'Error',
    message: 'Something went wrong!'
  });
});

// Export the app
module.exports = app;