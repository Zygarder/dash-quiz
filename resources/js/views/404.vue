<template>
    <div class="not-found-wrapper">
        <div class="content">
            <div class="lightning-container">
                <svg class="lightning-bolt" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                </svg>
                <h1 class="error-code">404</h1>
            </div>

            <div class="text-section">
                <h2>Whoops! You've been zapped.</h2>
                <p>The page you're looking for has moved or vanished into thin air.</p>
                
            </div>

            <div class="actions">
                <button @click="goBack" class="btn-back">Go Back</button>
                <router-link to="/" class="btn-home">Return Home</router-link>
            </div>
        </div>

        <div class="glow-sphere"></div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goBack = () => {
    router.back();
};

</script>

<style scoped>
.not-found-wrapper {
    min-height: 100vh;
    background-color: #f8fafc;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
    position: relative;
}

.content {
    text-align: center;
    z-index: 2;
    padding: 2rem;
}

/* === Big Lightning Style === */
.lightning-container {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
}

.lightning-bolt {
    width: 150px; /* Big lightning */
    height: 150px;
    color: #8b5cf6; /* Your Accent Violet */
    filter: drop-shadow(0 0 15px rgba(139, 92, 246, 0.5));
    animation: flicker 2s infinite;
}

.error-code {
    font-size: 8rem;
    font-weight: 900;
    color: #1e1b4b; /* Your Primary Deep */
    margin-top: -30px;
    letter-spacing: -5px;
    line-height: 1;
}

/* === Text Section === */
.text-section h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #1e1b4b;
    margin-bottom: 0.5rem;
}

.text-section p {
    color: #64748b;
    font-size: 1.1rem;
    margin-bottom: 2.5rem;
}

/* === Action Buttons === */
.actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-back {
    background: transparent;
    border: 2px solid #e2e8f0;
    color: #64748b;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-home {
    background: #1e1b4b;
    color: white;
    text-decoration: none;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    transition: all 0.2s;
    box-shadow: 0 10px 15px -3px rgba(30, 27, 75, 0.2);
}

.btn-back:hover {
    background: #fff;
    border-color: #8b5cf6;
    color: #8b5cf6;
}

.btn-home:hover {
    transform: translateY(-2px);
    background: #4c1d95;
}

/* === Decorations & Animations === */
.glow-sphere {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.05) 0%, transparent 70%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
}

@keyframes flicker {
    0%, 18%, 22%, 25%, 53%, 57%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    20%, 24%, 55% {
        opacity: 0.7;
        transform: scale(0.95) translateY(5px);
    }
}

@media (max-width: 600px) {
    .error-code { font-size: 5rem; }
    .lightning-bolt { width: 100px; height: 100px; }
}
</style>