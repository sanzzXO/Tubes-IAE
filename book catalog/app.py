from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_jwt_extended import JWTManager, jwt_required, get_jwt_identity
import logging
from datetime import datetime
import os

app = Flask(__name__)

# Configuration
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///books.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['JWT_SECRET_KEY'] = os.environ.get('JWT_SECRET_KEY', 'default-secret-key')
app.config['JSON_SORT_KEYS'] = False

# Initialize extensions
db = SQLAlchemy(app)
jwt = JWTManager(app)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Book Model
class Book(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(200), nullable=False)
    author = db.Column(db.String(100), nullable=False)
    isbn = db.Column(db.String(13), unique=True, nullable=False)
    description = db.Column(db.Text)
    category = db.Column(db.String(50))
    stock = db.Column(db.Integer, default=0)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

# Routes
@app.route('/api/books', methods=['GET'])
def get_books():
    try:
        books = Book.query.all()
        return jsonify([{
            'id': book.id,
            'title': book.title,
            'author': book.author,
            'isbn': book.isbn,
            'category': book.category,
            'stock': book.stock
        } for book in books]), 200
    except Exception as e:
        logger.error(f"Error fetching books: {str(e)}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/books/<int:book_id>', methods=['GET'])
def get_book(book_id):
    try:
        book = Book.query.get_or_404(book_id)
        return jsonify({
            'id': book.id,
            'title': book.title,
            'author': book.author,
            'isbn': book.isbn,
            'description': book.description,
            'category': book.category,
            'stock': book.stock
        }), 200
    except Exception as e:
        logger.error(f"Error fetching book {book_id}: {str(e)}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/books', methods=['POST'])
@jwt_required()
def create_book():
    try:
        current_user = get_jwt_identity()
        data = request.get_json()
        
        # Input validation
        required_fields = ['title', 'author', 'isbn']
        for field in required_fields:
            if field not in data:
                return jsonify({'error': f'Missing required field: {field}'}), 400

        new_book = Book(
            title=data['title'],
            author=data['author'],
            isbn=data['isbn'],
            description=data.get('description', ''),
            category=data.get('category', ''),
            stock=data.get('stock', 0)
        )
        
        db.session.add(new_book)
        db.session.commit()
        
        logger.info(f"Book created: {new_book.title} by {current_user}")
        return jsonify({'message': 'Book created successfully', 'id': new_book.id}), 201
    
    except Exception as e:
        db.session.rollback()
        logger.error(f"Error creating book: {str(e)}")
        return jsonify({'error': 'Internal server error'}), 500

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True, port=5000)