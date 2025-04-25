<style>
    .div-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .card-container {
        perspective: 1000px;
        width: 100%;
        max-width: 800px;
    }

    .card {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        padding: 40px;
        transition: transform 0.6s, box-shadow 0.6s;
        position: relative;
        overflow: hidden;
        border-top: 5px solid #4263eb;
        width: 100%;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .card-title {
        color: #4263eb;
        font-weight: 700;
        font-size: 28px;
        margin: 0;
    }

    .badge {
        background-color: #37b24d;
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
    }

    .date-info {
        display: flex;
        justify-content: space-between;
        width: 50%;
        margin-bottom: 30px;
        font-size: 14px;
        color: #64748b;
    }

    .feature-list {
        list-style-type: none;
        padding: 0;
        margin: 0 0 30px 0;
    }

    .feature-item {
        padding: 16px 0;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .feature-item:last-child {
        border-bottom: none;
    }

    .feature-icon {
        background-color: #edf2ff;
        color: #4263eb;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .feature-text {
        font-size: 16px;
        color: #334155;
        font-weight: 500;
    }

    .features-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .plan-badge {
        background-color: #edf2ff;
        color: #4263eb;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
    }

    .action-button {
        background-color: #4263eb;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .action-button:hover {
        background-color: #3b5bdb;
    }

    .button-icon {
        margin-left: 8px;
    }

    .card-background {
        position: absolute;
        top: 0;
        right: 0;
        width: 160px;
        height: 190px;
        background-color: #edf2ff;
        border-radius: 0 0 0 100%;
        z-index: 0;
    }
</style>
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