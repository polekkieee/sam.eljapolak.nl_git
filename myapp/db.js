const { Pool } = require('pg');
require('dotenv').config();

// Create a PostgreSQL connection pool
const pool = new Pool({
  host: process.env.DB_HOST,        // Render PostgreSQL host
  user: process.env.DB_USER,        // DB username
  password: process.env.DB_PASSWORD,// DB password
  database: process.env.DB_NAME,    // DB name
  port: process.env.DB_PORT || 5432,// Default PostgreSQL port
  ssl: {
    rejectUnauthorized: false       // Required for Render PostgreSQL
  },
  max: 10,                          // Maximum number of connections
  idleTimeoutMillis: 30000,         // Close idle clients after 30s
  connectionTimeoutMillis: 2000     // Return error after 2s if connection fails
});

// Test the connection
async function testConnection() {
  try {
    const client = await pool.connect();
    console.log('Database connection established successfully!');
    client.release();
    return true;
  } catch (error) {
    console.error('Error connecting to the database:', error.message);
    return false;
  }
}

module.exports = {
  pool,
  testConnection
};
