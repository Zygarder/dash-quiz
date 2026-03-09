<template>
    <form class="login-box" method="POST" :action="submitUrl" @submit="handleSubmit">
        
        <input type="hidden" name="_token" :value="csrfToken">

        <input 
            type="email" 
            name="email" 
            placeholder="Email Address" 
            v-model="email"
            @input="validateEmail"
            :style="emailError ? 'border: 1px solid red;' : ''"
        />
        <div class="error" v-if="emailError">{{ emailError }}</div>
        <div class="error" v-else-if="laravelEmailError">{{ laravelEmailError }}</div>

        <input 
            type="password" 
            name="password" 
            placeholder="Enter Password" 
            v-model="password"
        />
        <div class="error" v-if="laravelPasswordError">{{ laravelPasswordError }}</div>

        <button type="submit" class="login-btn" :disabled="isSubmitting">
            {{ isSubmitting ? 'Logging in...' : 'Log In' }}
        </button>

        <p class="small-text">
            Forgot password? <a :href="forgotUrl" class="forgot">click here</a>
        </p>
        <button type="button" class="register-btn" @click="goToRegister">
            Register Now!
        </button>
    </form>
</template>

<script>
export default {
    // "Props" are the pieces of data Laravel passes INTO this component
    props: [
        'csrfToken', 'submitUrl', 'forgotUrl', 'registerUrl', 
        'oldEmail', 'laravelEmailError', 'laravelPasswordError'
    ],
    data() {
        return {
            email: this.oldEmail || '', // Fill with old email if Laravel sent it back
            password: '',
            emailError: '',
            isSubmitting: false
        }
    },
    methods: {
        validateEmail() {
            // Check if what they are typing looks like a real email
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.email.length > 0 && !regex.test(this.email)) {
                this.emailError = 'Please enter a valid email format.';
            } else {
                this.emailError = ''; // Clear error if it looks good
            }
        },
        handleSubmit(event) {
            this.validateEmail();
            
            // If there's an error, STOP the form from submitting
            if (this.emailError || !this.email || !this.password) {
                event.preventDefault(); 
                return;
            }
            
            // Otherwise, show the loading state and let Laravel process it normally!
            this.isSubmitting = true;
        },
        goToRegister() {
            window.location.href = this.registerUrl;
        }
    }
}
</script>

<style scoped>
/* A little extra CSS for the disabled button */
.login-btn:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
    opacity: 0.7;
}
</style>