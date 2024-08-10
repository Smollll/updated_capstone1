// server.js

const express = require('express');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Serve static files (optional, for any frontend you might have)
app.use(express.static('public'));

// Endpoint to calculate storage used in 'courses' folder
app.get('/api/storage', (req, res) => {
    const coursesFolderPath = path.join(__dirname, 'courses');
    calculateFolderSize(coursesFolderPath)
        .then(size => {
            res.json({ size }); // Send size in bytes
        })
        .catch(err => {
            console.error('Error calculating folder size:', err);
            res.status(500).json({ error: 'Internal Server Error' });
        });
});

// Function to calculate folder size recursively
function calculateFolderSize(folderPath) {
    return new Promise((resolve, reject) => {
        let totalSize = 0;

        fs.readdir(folderPath, { withFileTypes: true }, (err, files) => {
            if (err) {
                reject(err);
                return;
            }

            const filePromises = files.map(file => {
                const filePath = path.join(folderPath, file.name);

                return new Promise((resolveFile, rejectFile) => {
                    fs.stat(filePath, (err, stats) => {
                        if (err) {
                            rejectFile(err);
                            return;
                        }

                        if (stats.isDirectory()) {
                            calculateFolderSize(filePath)
                                .then(folderSize => {
                                    totalSize += folderSize;
                                    resolveFile();
                                })
                                .catch(err => rejectFile(err));
                        } else {
                            totalSize += stats.size;
                            resolveFile();
                        }
                    });
                });
            });

            Promise.all(filePromises)
                .then(() => resolve(totalSize))
                .catch(err => reject(err));
        });
    });
}

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
