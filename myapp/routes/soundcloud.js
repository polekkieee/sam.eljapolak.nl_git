const express = require('express');
const router = express.Router();
const axios = require('axios');
const fs = require('fs');
const path = require('path');
const { pool } = require('../db');

const ENV_PATH = path.resolve(__dirname, '../.env');
const SOUNDCLOUD_USER_ID = process.env.SOUNDCLOUD_USER_ID;

// Fetch fresh client_id if needed
async function getFreshClientId() {
    const resp = await axios.get('https://soundcloud.com');
    const match = resp.data.match(/client_id=([a-zA-Z0-9]{32})/);
    if (match) return match[1];
    throw new Error('No client_id found in SoundCloud page');
}

// Update .env
function updateEnvFile(newClientId) {
    let envContent = fs.readFileSync(ENV_PATH, 'utf8');
    if (envContent.includes('SOUNDCLOUD_CLIENT_ID=')) {
        envContent = envContent.replace(
            /SOUNDCLOUD_CLIENT_ID=.*/g,
            `SOUNDCLOUD_CLIENT_ID=${newClientId}`
        );
    } else {
        envContent += `\nSOUNDCLOUD_CLIENT_ID=${newClientId}`;
    }
    fs.writeFileSync(ENV_PATH, envContent);
    console.log('✅ .env updated with new client_id');
}

async function fetchTracks(userId, clientId) {
    const url = `https://api-v2.soundcloud.com/users/${userId}/tracks?client_id=${clientId}`;
    return axios.get(url);
}

// Import SoundCloud tracks
router.post('/import-soundcloud', async (req, res) => {
    try {
        let clientId = process.env.SOUNDCLOUD_CLIENT_ID;

        let response;
        try {
            response = await fetchTracks(SOUNDCLOUD_USER_ID, clientId);
        } catch (err) {
            if (err.response && err.response.status === 401) {
                console.log('⚠️ Client ID expired, fetching a new one...');
                clientId = await getFreshClientId();
                updateEnvFile(clientId);
                response = await fetchTracks(SOUNDCLOUD_USER_ID, clientId);
            } else {
                throw err;
            }
        }

        const tracks = response.data.collection;

        // Replace the loop with this:
        await Promise.all(tracks.map(track =>
            pool.query(
                `INSERT INTO tracks 
      (soundcloud_id, title, genre, description, release_date, soundcloud_url, soundcloud_embed_code, featured, cover_image)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE 
      title=VALUES(title),
      genre=VALUES(genre),
      description=VALUES(description),
      release_date=VALUES(release_date),
      cover_image=VALUES(cover_image)`,
                [
                    track.id,
                    track.title,
                    track.genre || null,
                    track.description || null,
                    track.release_date ? new Date(track.release_date) : null,
                    track.permalink_url,
                    `<iframe width="100%" height="166" scrolling="no" frameborder="no" 
          src="https://w.soundcloud.com/player/?url=${encodeURIComponent(track.permalink_url)}"></iframe>`,
                    0,
                    track.artwork_url || null
                ]
            )
        ));


        // Fetch updated data for dashboard
        const [dbTracks] = await pool.query(
            'SELECT id, title, genre, release_date FROM tracks ORDER BY release_date DESC'
        );
        const [messageCount] = await pool.query(
            'SELECT COUNT(*) as count FROM messages WHERE read = 0'
        );

        res.render('admin/dashboard', {
            title: 'Admin Dashboard',
            tracks: dbTracks,
            unreadMessages: messageCount[0].count,
            success: `✅ Imported ${tracks.length} tracks from SoundCloud!`,
            error: null
        });
    } catch (error) {
        console.error('Error importing tracks:', error.response?.data || error.message);

        const [dbTracks] = await pool.query(
            'SELECT id, title, genre, release_date FROM tracks ORDER BY release_date DESC'
        );
        const [messageCount] = await pool.query(
            'SELECT COUNT(*) as count FROM messages WHERE read = 0'
        );

        res.render('admin/dashboard', {
            title: 'Admin Dashboard',
            tracks: dbTracks,
            unreadMessages: messageCount[0].count,
            success: null,
            error: '❌ Failed to import tracks. Please try again.'
        });
    }
});

module.exports = router;
