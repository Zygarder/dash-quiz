<template>
  <div class="auth-wrapper">
    <header class="auth-header">
      <div class="header-inner">
        <div class="brand">
          <div class="brand-icon">DQ</div>
          <span class="brand-name">Dash<span>Quiz</span></span>
        </div>
        <div class="header-links">
          <span>Security Portal</span>
        </div>
      </div>
    </header>

    <main class="auth-container center-mode">
      <div class="reset-card">
        <div class="glow-bg"></div>

        <div class="card-intro">
          <h2>Forgot password</h2>
          <p>We'll send a recovery link to your inbox.</p>
        </div>

        <form @submit.prevent="handleReset" class="auth-form">
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" v-model="email" placeholder="e.g. name@snsu.edu.ph" required />
          </div>

          <button class="btn-submit" :disabled="loading">
            <span>{{ loading ? 'Sending link...' : 'Send reset link' }}</span>
          </button>
        </form>

        <div class="form-footer">
          <p>
            Remembered?
            <router-link to="/" class="v-link">Back to login</router-link>
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Dark Theme Reset */
:global(body) {
  background-color: #020617;
  margin: 0;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  -webkit-font-smoothing: antialiased;
}

.auth-wrapper {
  min-height: 100vh;
  /* Cleaner mesh gradient */
  background: 
    radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
    radial-gradient(circle at 100% 100%, rgba(139, 92, 246, 0.08) 0%, transparent 40%);
  display: flex;
  flex-direction: column;
}

.center-mode {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 24px; /* Essential for mobile breathing room */
}

/* Glassmorphism Card Redefined */
.reset-card {
  position: relative;
  width: 100%;
  max-width: 420px;
  /* 48px padding (3rem) for a spacious, high-end feel */
  padding: 3rem; 
  background: #333;
  border-radius: 28px;
  /* Thinner, more subtle border */
  border: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(20px);
  box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5);
  animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}

.glow-bg {
  position: absolute;
  top: -30px;
  right: -30px;
  width: 150px;
  height: 150px;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}

/* Typography Spacing Check */
.card-intro {
  position: relative;
  z-index: 1;
  margin-bottom: 32px; /* Fixed spacing to form */
}

.card-intro h2 {
  font-size: 1.75rem;
  font-weight: 800;
  color: #f8fafc;
  margin: 0 0 8px 0; /* Reset margins */
  letter-spacing: -0.03em;
}

.card-intro p {
  color: #94a3b8;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0;
}

/* Form Vertical Spacing */
.auth-form {
  display: flex;
  flex-direction: column;
  gap: 24px; /* Consistent gap between all elements */
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 10px; /* Space between label and input */
}

.form-group label {
  font-size: 0.75rem;
  font-weight: 700;
  color: #8b5cf6;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding-left: 4px;
}

/* Input Minimalist Style */
input {
  width: 100%;
  padding: 14px;
  border-radius: 14px;
  /* Invisible border until focus or hover */
  border: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.03);
  font-size: 1rem;
  color: #ffffff;
  transition: all 0.25s ease;
}

input:hover {
  border-color: rgba(255, 255, 255, 0.2);
}

input:focus {
  outline: none;
  border-color: #8b5cf6;
  background: rgba(139, 92, 246, 0.05);
  box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
}

/* Button Refinement */
.btn-submit {
  width: 100%;
  padding: 16px;
  border-radius: 14px;
  border: none;
  font-weight: 700;
  font-size: 1rem;
  color: #fff;
  cursor: pointer;
  background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
  transition: all 0.3s ease;
  margin-top: 8px; /* Extra push from the input */
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  filter: brightness(1.1);
  box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
}

/* Footer Section */
.form-footer {
  margin-top: 32px;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  padding-top: 24px;
  text-align: center;
}

.form-footer p {
  color: #64748b;
  font-size: 0.9rem;
  margin: 0;
}

.v-link {
  color: #a78bfa;
  text-decoration: none;
  font-weight: 600;
  margin-left: 4px;
}

.v-link:hover {
  color: #f8fafc;
}

/* Header Adjustments */
.auth-header {
  padding: 24px;
}

.header-inner {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand-icon {
  background: #8b5cf6;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 800;
  font-size: 0.8rem;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>