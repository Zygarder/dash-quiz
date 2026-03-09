<script setup>
const props = defineProps({
    show: Boolean
})

const emit = defineEmits(["close", "delete"])
</script>

<template>
    <div v-if="show" class="modal">
        <div class="modal-content">
            <h3>Delete Account</h3>
            <p>This action cannot be undone.</p>

            <div class="modal-buttons">
                <button class="cancel-btn" @click="$emit('close')">
                    Cancel
                </button>
                <button class="delete-confirm-btn" @click="$emit('delete')">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* 1. The Backdrop: Centers the modal on the screen */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* 2. The Card: Adds padding and structure */
.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
    text-align: center; /* Centers the text and heading */
}

/* 3. The Buttons: Aligns them side-by-side */
.modal-buttons {
    display: flex;
    justify-content: flex-end; /* Aligns buttons to the right */
    gap: 12px;                /* Adds space between buttons */
    margin-top: 24px;
}

button {
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 4px;
    border: none;
}

.cancel-btn {
    background-color: #e0e0e0;
}

.delete-confirm-btn {
    background-color: #d9534f;
    color: white;
}
</style>