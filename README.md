# 🔄 Swapping Marketplace

A modern, full-stack web application for swapping items between users. Built with React, Node.js, Express, and MongoDB.

## 🚀 Features

### Core Functionality
- **User Authentication**: Registration, login, and secure JWT-based authentication
- **Product Listings**: Create, edit, and manage product listings
- **Categories**: Browse products by categories (Clothing, Electronics, Books, Home, Sports, Others)
- **Search & Filter**: Advanced search and filtering capabilities
- **User Dashboard**: Manage your listings and track your activity
- **Admin Panel**: Full admin control for managing users, products, and swaps

### User Experience
- **Landing Page**: Beautiful hero section with featured items and categories
- **Product Details**: Detailed product view with images and descriptions
- **Interest System**: Express interest in items and connect with owners
- **Responsive Design**: Mobile-friendly interface

## 🛠️ Tech Stack

### Backend
- **Node.js** - Runtime environment
- **Express.js** - Web framework
- **MongoDB** - Database
- **Mongoose** - ODM for MongoDB
- **JWT** - Authentication
- **bcryptjs** - Password hashing
- **Express Validator** - Input validation
- **Multer** - File uploads
- **CORS** - Cross-origin resource sharing

### Frontend
- **React** - Frontend library
- **React Router** - Client-side routing
- **Axios** - HTTP client
- **Modern CSS** - Styling

## 📁 Project Structure

```
swapping-marketplace/
├── models/
│   ├── User.js          # User schema
│   ├── Product.js       # Product schema
│   └── Swap.js          # Swap transaction schema
├── routes/
│   ├── auth.js          # Authentication routes
│   ├── products.js      # Product management routes
│   └── admin.js         # Admin panel routes
├── middleware/
│   └── auth.js          # Authentication middleware
├── frontend/            # React application
│   ├── src/
│   ├── public/
│   └── package.json
├── uploads/             # File uploads directory
├── .env                 # Environment variables
├── package.json         # Backend dependencies
└── index.js            # Main server file
```

## 🚀 Quick Start

### Prerequisites
- Node.js (v14 or higher)
- MongoDB (running locally or MongoDB Atlas)
- npm or yarn

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd swapping-marketplace
   ```

2. **Install backend dependencies**
   ```bash
   npm install
   ```

3. **Install frontend dependencies**
   ```bash
   cd frontend
   npm install
   cd ..
   ```

4. **Set up environment variables**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

5. **Start MongoDB**
   ```bash
   # If running locally
   mongod
   ```

6. **Run the application**
   
   **Development mode (both backend and frontend):**
   ```bash
   # Terminal 1: Start backend
   npm run dev
   
   # Terminal 2: Start frontend
   npm run client
   ```
   
   **Production mode:**
   ```bash
   npm start
   ```

## 🔧 Environment Variables

Create a `.env` file in the root directory:

```env
PORT=3000
MONGODB_URI=mongodb://127.0.0.1:27017/hackathonDB
JWT_SECRET=your-secret-key-change-this-in-production
NODE_ENV=development
```

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get current user

### Product Endpoints
- `GET /api/products` - Get all products (with filtering)
- `GET /api/products/:id` - Get single product
- `POST /api/products` - Create new product (auth required)
- `PUT /api/products/:id` - Update product (auth required)
- `DELETE /api/products/:id` - Delete product (auth required)
- `GET /api/products/user/my-listings` - Get user's listings (auth required)
- `POST /api/products/:id/interest` - Express interest (auth required)

### Admin Endpoints
- `GET /api/admin/dashboard` - Get admin dashboard stats
- `GET /api/admin/users` - Get all users
- `PUT /api/admin/users/:id` - Update user
- `DELETE /api/admin/users/:id` - Delete user
- `GET /api/admin/products` - Get all products
- `PUT /api/admin/products/:id` - Update product
- `DELETE /api/admin/products/:id` - Delete product

## 🎨 UI/UX Features

Based on your wireframes, the application includes:

### Screen 1: Landing Page
- Hero section with call-to-action buttons
- Featured items carousel
- Categories section
- Product listings grid
- Search and filter functionality

### Screen 2: Registration Page
- User registration form
- Social login options
- Form validation
- Redirects to dashboard upon success

### Screen 3: Product Listing
- Product image gallery
- Detailed product information
- Related products section
- Interest/swap buttons

### Screen 4: User Dashboard
- User profile information
- My Listings section
- My Purchases section
- Account management

### Screen 5: Product Detail Page
- Image upload interface
- Product description editor
- Previous listings display
- Available/Swap status toggle

### Screen 6: Admin Panel
- User management interface
- Product management
- Swap transaction monitoring
- Analytics dashboard

## 🔒 Security Features

- JWT-based authentication
- Password hashing with bcrypt
- Input validation and sanitization
- CORS protection
- Rate limiting ready
- Admin role-based access control

## 📱 Responsive Design

The application is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones

## 🚀 Deployment

### Backend Deployment
1. Set up MongoDB Atlas or use your preferred MongoDB hosting
2. Configure environment variables
3. Deploy to platforms like Heroku, Railway, or DigitalOcean

### Frontend Deployment
1. Build the React app: `npm run build`
2. Deploy to platforms like Netlify, Vercel, or serve from your backend

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📝 License

This project is licensed under the MIT License.

## 🛟 Support

For support, email your-email@example.com or create an issue in the repository.

## 🎯 Future Enhancements

- Real-time chat between users
- Push notifications
- Advanced search with geolocation
- Rating and review system
- Payment integration
- Mobile app development
- Social media integration

---

Built with ❤️ for the swapping community!