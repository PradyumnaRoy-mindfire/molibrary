<link rel="stylesheet" href="{{ url('css/member/membership_features.css') }}">

<div class="div-container">
    <div class="card-container">
        <div class="card">
            <div class="card-background"></div>
            <div class="card-header">
                <h1 class="card-title">Library Membership</h1>
                <span class="badge">Active</span>
            </div>

            <h2 style="color: #334155; font-size: 20px; margin-bottom: 16px;">Base Plan Features</h2>

            <div class="features-container">
                <div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon">📚</div>
                            <div class="feature-text">Borrow up to 5 books at a time</div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">⏱️</div>
                            <div class="feature-text">21-day loan period</div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">🔖</div>
                            <div class="feature-text">Reserve up to 3 books in advance</div>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon">💻</div>
                            <div class="feature-text">Access to basic online resources</div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">📱</div>
                            <div class="feature-text">Mobile app notifications for due dates</div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">🎓</div>
                            <div class="feature-text">Monthly newsletter subscription</div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-footer">
                <span class="plan-badge">Base Plan</span>
                <a href="{{ route('memberships') }}" class="action-button">
                    Upgrade Membership
                    <span class="button-icon">→</span>
                </a>
            </div>
        </div>
    </div>
</div>