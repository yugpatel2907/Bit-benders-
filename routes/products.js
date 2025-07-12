const express = require('express');
const { body, validationResult } = require('express-validator');
const Product = require('../models/Product');
const { auth } = require('../middleware/auth');

const router = express.Router();

// Get all products (for landing page and browsing)
router.get('/', async (req, res) => {
  try {
    const { category, search, page = 1, limit = 12, featured } = req.query;
    const query = { isAvailable: true };
    
    if (category && category !== 'all') {
      query.category = category;
    }
    
    if (search) {
      query.$text = { $search: search };
    }
    
    if (featured === 'true') {
      query.featured = true;
    }

    const products = await Product.find(query)
      .populate('owner', 'username profileImage location rating')
      .sort({ createdAt: -1 })
      .limit(limit * 1)
      .skip((page - 1) * limit);

    const total = await Product.countDocuments(query);

    res.json({
      products,
      totalPages: Math.ceil(total / limit),
      currentPage: page,
      total
    });

  } catch (error) {
    console.error('Get products error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get single product
router.get('/:id', async (req, res) => {
  try {
    const product = await Product.findById(req.params.id)
      .populate('owner', 'username profileImage location rating completedSwaps')
      .populate('interestedUsers.user', 'username profileImage');

    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    // Increment views
    product.views += 1;
    await product.save();

    res.json(product);

  } catch (error) {
    console.error('Get product error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Create new product
router.post('/', [auth, [
  body('title').isLength({ min: 3 }).withMessage('Title must be at least 3 characters'),
  body('description').isLength({ min: 10 }).withMessage('Description must be at least 10 characters'),
  body('category').isIn(['Clothing', 'Electronics', 'Books', 'Home', 'Sports', 'Others']).withMessage('Invalid category'),
  body('condition').isIn(['New', 'Like New', 'Good', 'Fair', 'Poor']).withMessage('Invalid condition'),
  body('location').isLength({ min: 2 }).withMessage('Location is required')
]], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { title, description, category, condition, images, location, estimatedValue, tags } = req.body;

    const product = new Product({
      title,
      description,
      category,
      condition,
      images: images || [],
      location,
      estimatedValue,
      tags: tags || [],
      owner: req.user._id
    });

    await product.save();
    await product.populate('owner', 'username profileImage location rating');

    res.status(201).json({
      message: 'Product created successfully',
      product
    });

  } catch (error) {
    console.error('Create product error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Update product
router.put('/:id', auth, async (req, res) => {
  try {
    const product = await Product.findById(req.params.id);
    
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    // Check if user owns the product
    if (product.owner.toString() !== req.user._id.toString()) {
      return res.status(403).json({ message: 'Not authorized' });
    }

    const { title, description, category, condition, images, location, estimatedValue, tags, isAvailable } = req.body;

    const updatedProduct = await Product.findByIdAndUpdate(
      req.params.id,
      {
        title,
        description,
        category,
        condition,
        images,
        location,
        estimatedValue,
        tags,
        isAvailable
      },
      { new: true }
    ).populate('owner', 'username profileImage location rating');

    res.json({
      message: 'Product updated successfully',
      product: updatedProduct
    });

  } catch (error) {
    console.error('Update product error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Delete product
router.delete('/:id', auth, async (req, res) => {
  try {
    const product = await Product.findById(req.params.id);
    
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    // Check if user owns the product
    if (product.owner.toString() !== req.user._id.toString()) {
      return res.status(403).json({ message: 'Not authorized' });
    }

    await Product.findByIdAndDelete(req.params.id);
    res.json({ message: 'Product deleted successfully' });

  } catch (error) {
    console.error('Delete product error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get user's products (for dashboard)
router.get('/user/my-listings', auth, async (req, res) => {
  try {
    const products = await Product.find({ owner: req.user._id })
      .sort({ createdAt: -1 });

    res.json(products);

  } catch (error) {
    console.error('Get user products error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Express interest in a product
router.post('/:id/interest', auth, async (req, res) => {
  try {
    const { message } = req.body;
    const product = await Product.findById(req.params.id);

    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    if (product.owner.toString() === req.user._id.toString()) {
      return res.status(400).json({ message: 'Cannot express interest in your own product' });
    }

    // Check if user already expressed interest
    const existingInterest = product.interestedUsers.find(
      interest => interest.user.toString() === req.user._id.toString()
    );

    if (existingInterest) {
      return res.status(400).json({ message: 'Already expressed interest' });
    }

    product.interestedUsers.push({
      user: req.user._id,
      message: message || ''
    });

    await product.save();
    res.json({ message: 'Interest expressed successfully' });

  } catch (error) {
    console.error('Express interest error:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get categories
router.get('/categories/all', async (req, res) => {
  try {
    const categories = await Product.distinct('category');
    res.json(categories);
  } catch (error) {
    res.status(500).json({ message: 'Server error' });
  }
});

module.exports = router;