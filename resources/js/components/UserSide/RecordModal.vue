<template>
  <Transition name="fade">
    <div v-if="modelValue" class="modal-overlay" @click.self="close">

      <div class="modal-content">
        <div class="modal-header">
          <div class="title-group">
            <h3>{{ record?.details?.quiz_title || 'Quiz Details' }}</h3>
            <p class="subtitle">Completed on {{ formatDate(record?.created_at) }}</p>
          </div>
          <button class="close-x" @click="close">✕</button>
        </div>

        <div class="modal-summary" v-if="record">
          <div class="m-stat">
            <span>Score</span>
            <strong :class="record.score >= 7 ? 'pass-text' : 'fail-text'">
              {{ record.score }} / 10
            </strong>
          </div>
          <div class="m-stat">
            <span>Accuracy</span>
            <strong>{{ accuracy }}%</strong>
          </div>
          <div class="m-stat">
            <span>Status</span>
            <span :class="['badge', record.score >= 7 ? 'pass' : 'fail']">
              {{ record.score >= 7 ? 'Passed' : 'Failed' }}
            </span>
          </div>
        </div>

        <div class="modal-body" v-if="record?.details?.questions">
          <h4 class="section-label">Review Questions</h4>

          <div v-for="(q, i) in record.details.questions" :key="i"
            :class="['q-card', q.correct ? 'card-correct' : 'card-wrong']">
            <div class="q-header">
              <span class="q-number">Question {{ i + 1 }}</span>
              <span :class="['q-status', q.correct ? 'text-correct' : 'text-wrong']">
                {{ q.correct ? 'Correct' : 'Incorrect' }}
              </span>
            </div>

            <p class="q-text">{{ q.question }}</p>

            <div class="answer-box">
              <div class="ans-line">
                <span class="ans-label">Your Answer:</span>
                <span :class="['ans-val', q.correct ? 'text-correct' : 'text-wrong']">
                  {{ q.user_answer }}
                </span>
              </div>

              <div v-if="!q.correct" class="ans-line correct-line">
                <span class="ans-label">Correct Answer:</span>
                <span class="ans-val text-correct">{{ q.correct_answer }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-secondary" @click="close">Close Review</button>
          <button class="btn-primary" @click="handleRetake">Retake Quiz</button>
        </div>
      </div>

    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  record: Object
})

const emit = defineEmits(['update:modelValue', 'retake'])

const close = () => emit('update:modelValue', false)

const accuracy = computed(() => {
  if (!props.record?.details?.questions?.length) return 0
  const correct = props.record.details.questions.filter(q => q.correct).length
  return ((correct / props.record.details.questions.length) * 100).toFixed(0)
})

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', {
  year: 'numeric', month: 'long', day: 'numeric'
}) : ''

const handleRetake = () => {
  emit('retake', props.record.quiz_id)
  close()
}
</script>

<style scoped>
/* OVERLAY */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  padding: 1rem;
}

/* CONTENT BOX */
.modal-content {
  background: white;
  width: 100%;
  max-width: 1200px;
  max-height: 100vh;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  overflow: hidden;
}

/* HEADER */
.modal-header {
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #f1f5f9;
}

.title-group h3 {
  margin: 0;
  color: #1e293b;
  font-size: 1.2rem;
  font-weight: 800;
}

.subtitle {
  margin: 4px 0 0;
  font-size: 0.8rem;
  color: #64748b;
}

.close-x {
  background: #f1f5f9;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  color: #64748b;
}

/* SUMMARY */
.modal-summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  padding: 1.25rem;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
}

.m-stat {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.m-stat span {
  font-size: 0.65rem;
  color: #64748b;
  text-transform: uppercase;
  font-weight: 700;
  margin-bottom: 4px;
}

.m-stat strong {
  font-size: 1.2rem;
  color: #1e293b;
}

/* BODY & CARDS */
.modal-body {
  padding: 1.5rem;
  overflow-y: auto;
  flex-grow: 1;
  background: #fff;
}

.section-label {
  font-size: 0.85rem;
  color: #94a3b8;
  text-transform: uppercase;
  margin-bottom: 1rem;
  letter-spacing: 0.05em;
}

.q-card {
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
}

.card-correct {
  background: #f0fdf4;
  border-color: #dcfce7;
}

.card-wrong {
  background: #fff1f2;
  border-color: #ffe4e6;
}

.q-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.q-number {
  font-size: 0.75rem;
  font-weight: 700;
  color: #64748b;
}

.q-status {
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
}

.q-text {
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 1rem 0;
  font-size: 0.95rem;
  line-height: 1.5;
}

/* ANSWERS */
.answer-box {
  font-size: 0.9rem;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.ans-line {
  display: flex;
  gap: 8px;
}

.ans-label {
  color: #64748b;
  font-weight: 500;
}

.ans-val {
  font-weight: 700;
}

.text-correct {
  color: #10b981;
}

.text-wrong {
  color: #f43f5e;
}

/* FOOTER */
.modal-footer {
  padding: 1.25rem 1.5rem;
  background: #fff;
  border-top: 1px solid #f1f5f9;
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

/* BUTTONS */
.btn-primary {
  background: #6366f1;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: #4f46e5;
  transform: translateY(-1px);
}

.btn-secondary {
  background: white;
  border: 1px solid #e2e8f0;
  padding: 10px 20px;
  border-radius: 10px;
  cursor: pointer;
  color: #64748b;
  font-weight: 600;
  font-size: 0.9rem;
}

.pass-text {
  color: #6366f1;
}

.fail-text {
  color: #f43f5e;
}

.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 800;
}

.badge.pass {
  background: #e0e7ff;
  color: #6366f1;
}

.badge.fail {
  background: #ffe4e6;
  color: #f43f5e;
}

/* SCROLLBAR */
.modal-body::-webkit-scrollbar {
  width: 6px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
</style>