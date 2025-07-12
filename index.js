const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');

const app = express();
const PORT = 3000;

app.use(cors());
app.use(express.json());

// MongoDB connect without deprecated options
mongoose.connect('mongodb://127.0.0.1:27017/hackathonDB')
  .then(() => console.log('✅ Connected to MongoDB 💚'))
  .catch(err => console.error('❌ MongoDB connection failed:', err));

// Sample route
app.get('/', (req, res) => {
  res.send('Backend is up and running 🚀');
});

app.listen(PORT, () => {
  console.log(`🟢 Server running at http://localhost:${PORT}`);
});