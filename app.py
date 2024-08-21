from flask import Flask, render_template, redirect, url_for, request, session
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = 'your_secret_key'
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///site.db'
db = SQLAlchemy(app)

# Database models
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(100), unique=True, nullable=False)
    email = db.Column(db.String(100), unique=True, nullable=False)
    phone = db.Column(db.String(20), unique=True, nullable=False)
    password = db.Column(db.String(60), nullable=False)
    profile_picture = db.Column(db.String(120), default='default.jpg')
    points = db.Column(db.Integer, default=0)
    referred_by = db.Column(db.Integer, db.ForeignKey('user.id'))
    referrals = db.relationship('User', backref='referrer', remote_side=[id])

# Home route
@app.route('/')
def home():
    return render_template('index.html')

# Profile route
@app.route('/profile')
def profile():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    user = User.query.get_or_404(session['user_id'])
    return render_template('profile.html', user=user)

# Work route
@app.route('/work')
def work():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    return render_template('work.html')

# Task routes
@app.route('/task1')
def task1():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    user = User.query.get_or_404(session['user_id'])
    user.points += 50
    db.session.commit()
    return redirect(url_for('work'))

@app.route('/task2')
def task2():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    user = User.query.get_or_404(session['user_id'])
    user.points += 50
    db.session.commit()
    return redirect(url_for('work'))

@app.route('/task3')
def task3():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    user = User.query.get_or_404(session['user_id'])
    user.points += 50
    db.session.commit()
    return redirect(url_for('work'))

# Login route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        phone = request.form.get('phone')
        password = request.form.get('password')
        user = User.query.filter_by(phone=phone).first()
        if user and check_password_hash(user.password, password):
            session['user_id'] = user.id
            return redirect(url_for('home'))
        else:
            return "Login failed. Check your phone and password."
    return render_template('login.html')

# Register route
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form.get('username')
        phone = request.form.get('phone')
        email = request.form.get('email')
        password = generate_password_hash(request.form.get('password'), method='sha256')
        profile_picture = request.form.get('profile_picture', 'default.jpg')
        referred_by = request.args.get('referrer_id')
        
        # Create new user
        new_user = User(username=username, phone=phone, email=email, password=password, profile_picture=profile_picture)
        if referred_by:
            referrer = User.query.get(referred_by)
            if referrer:
                new_user.referrer = referrer
                referrer.points += 100
                db.session.commit()
        
        db.session.add(new_user)
        db.session.commit()
        
        # Automatically log in the new user
        session['user_id'] = new_user.id
        
        return redirect(url_for('home'))
    
    return render_template('register.html')

# Logout route
@app.route('/logout')
def logout():
    session.pop('user_id', None)
    return redirect(url_for('home'))

if __name__ == '__main__':
    db.create_all()
    app.run(debug=True)
