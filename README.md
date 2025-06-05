This is a simple Laravel + Vue.js application 
    
    User Registration/Authentication
        • Users are able to register an account and log in using Laravel’s built-in authentication
        system.
        • Only authenticated users can use the system(create, view, and interact with posts).
    Post Creation and Management
        • Users are able to Create, Read, Update, and Delete their own posts.
        • Each post include:
            • Title
            • Content (text body)
            • Creation Date and Time
            • The Author’s Name (auto-assigned based on the logged-in user).
        • Posts are assigned to one or more predefined categories. For
        example, categories might include "Technology," "Health," or "Lifestyle."
        • Only the author of a post can edit or delete their own post.
    Categories
        • Categories are predefined (seeded) and cannot be edited or added by users.
        • Posts can belong to multiple categories, and categories can
        include multiple posts.
        • Users are able to filter posts in the main feed by category.
    Comments
        • Users are able to comment on posts.
        • Only registered users can add or delete their own comments.
        • Comments display the author’s name and the date/time they were posted.
    User Profile
        • Each user has a profile page where their own posts are displayed.
        • A profile page should only show posts created by the profile owner, ordered by the most recent
        first.
    Main Feed
        • The main feed should display posts from all users, including the post's title, content preview,
        author’s name, categories, and the number of comments.
        • Users should be able to filter posts by category and see posts from all categories by default.
        Search Functionality
        • Users should be able to search posts by keywords found in the post's title or content.
        • The search results should include posts that match the search term in their title or body content.
    Security & Access Control
        • Input Validation: All user inputs are validated before processing. Posts have a
        valid title, content, and assigned categories.
        • Middleware for Access Control: Laravel’s Middleware is used to ensure:
        	• Only the author of a post can edit or delete their post.
        	• Only the author of a comment can delete their comment.
        • XSS Prevention: All user inputs and displayed data are sanitised to prevent cross-site
            scripting (XSS) attacks.
        
    Key Features:
        • Users can create posts with titles, content, and assign multiple predefined categories.
        • Users can view and filter posts by category in the main feed.
        • Users have profiles displaying their posts.
        • Users can post comments on others' posts and delete their own comments.

## Folder Structure

```
project-root/
├── back/    ← Laravel backend
└── front-vue/   ← Vue.js frontend
```


### Prerequisites

## Backend
- Docker Desktop (Mac, Windows, Linux) or Docker + Docker Compose(Linux)
-(Optional) PHP
- Composer

## Frontend
- Node.js (v16+ recommended)
- npm

---

### 🛠️ Installation Instructions

## 1. Backend Setup (Laravel)

cd back
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed


## 2. Frontend Setup (Vue.js)

cd front
npm install
npm run dev
```

### Access & Test the App

- **Frontend**: Open your browser at http://localhost:5173
- **Backend API**: Accessible at http://localhost:8000/api

Login -email "janis@janis" -password "password" or register new user


