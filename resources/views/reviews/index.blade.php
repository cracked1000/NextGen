<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Computing - Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom right, #1f2937, #111827);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
        }
        
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        h1 {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(to right, #3b82f6, #ec4899);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            color: #d1d5db;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .alert-error {
            background-color: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .review-form-container {
            background: linear-gradient(to bottom right, rgba(31, 41, 55, 0.5), rgba(17, 24, 39, 0.8));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #f3f4f6;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #d1d5db;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: rgba(55, 65, 81, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.8);
            border-radius: 0.5rem;
            color: #f9fafb;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .form-error {
            margin-top: 0.5rem;
            color: #ef4444;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            display: inline-block;
            background: linear-gradient(to right, #3b82f6, #6366f1);
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            background: linear-gradient(to right, #2563eb, #4f46e5);
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(to bottom right, rgba(31, 41, 55, 0.5), rgba(17, 24, 39, 0.8));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            margin-bottom: 3rem;
        }
        
        .login-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .login-link:hover {
            color: #60a5fa;
            text-decoration: underline;
        }
        
        .reviews-container {
            padding-top: 1rem;
        }
        
        .reviews-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .review-item {
            background: linear-gradient(to bottom right, rgba(31, 41, 55, 0.7), rgba(17, 24, 39, 0.9));
            border-left: 4px solid;
            border-image: linear-gradient(to bottom, #3b82f6, #ec4899) 1;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .review-item:hover {
            transform: translateY(-3px);
        }
        
        .quotation-number {
            font-size: 1.125rem;
            font-weight: 700;
            color: #ec4899;
            margin-bottom: 0.5rem;
        }
        
        .reviewer-name {
            font-weight: 600;
            color: #f3f4f6;
            display: inline-block;
        }
        
        .star-rating {
            color: #fbbf24;
            font-size: 1.25rem;
            margin-left: 0.5rem;
        }
        
        .review-comment {
            margin-top: 0.75rem;
            color: #d1d5db;
            line-height: 1.6;
        }
        
        .review-date {
            display: block;
            margin-top: 0.75rem;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        
        .no-reviews {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 2rem;
            background: rgba(31, 41, 55, 0.3);
            border-radius: 0.5rem;
        }
        
        .rating-select {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .rating-select select {
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: rgba(55, 65, 81, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.8);
            color: #f9fafb;
        }
    </style>
</head>
<body>
    @include('include.header')
    <div class="page-container">
        <div class="page-title">
            <h1>APPLICATION & SERVICE REVIEWS</h1>
            <p class="subtitle">Share your experience with NextGen Computing and help others make informed decisions.</p>
        </div>
        
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        @if (auth()->check())
            <div class="review-form-container">
                <h2 class="form-title">Submit Your Review</h2>
                <form method="POST" action="{{ route('reviews.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="quotation_number" class="form-label">Quotation Number</label>
                        <input type="text" name="quotation_number" id="quotation_number" class="form-control" required placeholder="Enter your quotation number">
                        @error('quotation_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="comment" class="form-label">Your Review</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Share your experience with us..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="rating" class="form-label">Rating</label>
                        <div class="rating-select">
                            <select name="rating" id="rating" class="form-control" required>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Very Good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Review
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="login-prompt">
                <p>Please <a href="{{ route('login') }}" class="login-link">log in</a> to submit a review.</p>
            </div>
        @endif
        
        <div class="reviews-container">
            <h2 class="reviews-title">Customer Reviews</h2>
            
            @forelse ($reviews as $review)
                <div class="review-item">
                    <div class="quotation-number">Quotation: {{ $review->quotationAction->quotation_number }}</div>
                    <span class="reviewer-name">{{ $review->user->first_name }} {{ $review->user->last_name }}</span>
                    <span class="star-rating">{!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}</span>
                    <p class="review-comment">{{ $review->comment ?? 'No comment provided.' }}</p>
                    <small class="review-date">{{ $review->created_at->format('F d, Y') }}</small>
                </div>
            @empty
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>