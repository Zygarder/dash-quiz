<template>
  <Transition name="fade">
    <div v-if="modelValue" class="modal-overlay" @click.self="close">
      <div class="modal-content">

        <div class="modal-header">
          <div class="title-group">
            <h3>Quiz Review</h3>
            <p class="subtitle">Completed on {{ formatDate(record?.created_at) }}</p>
          </div>
          <button class="close-x" @click="close">&times;</button>
        </div>

        <div class="modal-summary" v-if="record">
          <div class="m-stat">
            <span>Score</span>
            <strong :class="record.score >= passingScore ? 'pass-text' : 'fail-text'">
              {{ correctCount }}/{{ totalQuestions }}
            </strong>
          </div>
          <div class="m-stat">
            <span>Accuracy</span>
            <h3>{{ accuracy }}%</h3>
          </div>
          <div class="m-stat">
            <span>Time</span>
            <h3>{{ formatElapsed(record.elapsed_time) }}</h3>
          </div>
          <div class="m-stat">
            <span :class="['badge', record.score >= passingScore ? 'pass' : 'fail']">
              {{ record.score >= passingScore ? 'Passed' : 'Failed' }}
            </span>
          </div>
        </div>

        <div class="modal-body" v-if="record?.questions?.length">
          <div class="question-list">
            <div v-for="(q, i) in record.questions" :key="q.question_id || i" class="question-item">

              <div :class="['q-indicator', q.is_correct ? 'ind-pass' : 'ind-fail']">
                {{ i + 1 }}
              </div>

              <div class="q-content">
                <p class="q-text">{{ q.question }}</p>

                <div class="answers-stack">
                  <div class="ans-line">
                    <span class="ans-label">Answer:</span>
                    <span :class="['ans-val', q.is_correct ? 'text-pass' : 'text-fail']">
                      {{ q.user_answer || 'No answer selected' }}
                    </span>
                  </div>

                  <div class="ans-line" v-if="!q.is_correct">
                    <span class="ans-label">Correct:</span>
                    <span class="ans-val text-pass">{{ q.correct_answer }}</span>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div v-else class="empty-state">
          No question data found.
        </div>

        <div class="modal-footer">
          <button class="btn-secondary" @click="close">Close</button>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  record: Object
})

const emit = defineEmits(['update:modelValue', 'retake'])
const passingScore = 7

const close = () => emit('update:modelValue', false)

watch(
  () => props.modelValue,
  (val) => { document.body.style.overflow = val ? 'hidden' : '' },
  { immediate: true }
)

// if 'esc' is pressed, close the modal
const handleEscape = (e) => {
  if (e.key === 'Escape') {
    close()
  }
}

onMounted(() => window.addEventListener('keydown', handleEscape))
onUnmounted(() => {
  window.removeEventListener('keydown', handleEscape)
  document.body.style.overflow = ''
})

const totalQuestions = computed(() => props.record?.total_questions || props.record?.questions?.length || 0)
const correctCount = computed(() => props.record?.questions?.filter(q => q.is_correct).length || 0)
const wrongCount = computed(() => props.record?.questions?.filter(q => !q.is_correct).length || 0)
const accuracy = computed(() => {
  const qs = props.record?.questions || []
  return qs.length ? ((props.record.questions.filter(q => q.is_correct).length / qs.length) * 100).toFixed(0) : 0
})

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : ''
const formatElapsed = (sec) => {
  if (sec == null) return '0:00'
  return `${Math.floor(sec / 60)}:${(sec % 60).toString().padStart(2, '0')}`
}
</script>

<style scoped>
/* =========================================================
   FROSTED NOIR
========================================================= */

.modal-content {
  --black: #000000;
  --white: #ffffff;

  --gray-100: #f7f7f7;
  --gray-200: #eeeeee;
  --gray-300: #d3d3d3;
  --gray-400: #a9a9a9;
  --gray-500: #696969;

  /* RIGHT */
  --right-bg: #ecfdf3;
  --right-border: #86efac;
  --right-accent: #22c55e;
  --right-text: #166534;

  /* WRONG */
  --wrong-bg: #fef2f2;
  --wrong-border: #fecaca;
  --wrong-accent: #ef4444;
  --wrong-text: #991b1b;
}


/* =========================================================
   OVERLAY
========================================================= */

.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 9999;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background: rgba(0, 0, 0, 0.42);

  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
}


/* =========================================================
   MODAL
========================================================= */

.modal-content {
  width: 100%;
  max-width: 620px;
  max-height: 86vh;

  display: flex;
  flex-direction: column;

  overflow: hidden;

  background: var(--white);

  border: 1px solid var(--gray-300);
  border-radius: 14px;

  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.16);
}


/* =========================================================
   HEADER
========================================================= */

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  gap: 20px;

  padding: 17px 20px;

  border-bottom: 1px solid var(--gray-200);
}


.title-group {
  min-width: 0;
}


.title-group h3 {
  margin: 0;

  color: var(--black);

  font-size: 15px;
  font-weight: 750;

  letter-spacing: -0.02em;
}


.subtitle {
  margin: 3px 0 0;

  color: var(--gray-400);

  font-size: 10px;
}


.close-x {
  width: 30px;
  height: 30px;

  flex: 0 0 30px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: 1px solid transparent;
  border-radius: 7px;

  background: transparent;

  color: var(--gray-400);

  font-size: 20px;
  line-height: 1;

  cursor: pointer;

  transition:
    background 0.15s ease,
    color 0.15s ease,
    border-color 0.15s ease;
}


.close-x:hover {
  background: var(--gray-200);

  border-color: var(--gray-300);

  color: var(--black);
}


/* =========================================================
   SUMMARY
========================================================= */

.modal-summary {
  display: grid;

  grid-template-columns:
    repeat(4, 1fr);

  align-items: stretch;

  background: var(--gray-100);

  border-bottom: 1px solid var(--gray-200);
}


.m-stat {
  min-width: 0;

  display: flex;
  flex-direction: column;
  justify-content: center;

  gap: 3px;

  padding: 13px 16px;

  border-right: 1px solid var(--gray-200);
}


.m-stat:last-child {
  border-right: none;
}


.m-stat>span:first-child {
  color: var(--white);

  font-size: 8px;
  font-weight: 700;

  letter-spacing: 0.09em;
  text-transform: uppercase;
}


.m-stat strong,
.m-stat h3 {
  margin: 0;

  color: var(--black);

  font-size: 15px;
  line-height: 1.2;

  font-weight: 750;
}


/* =========================================================
   PASS / FAIL STATUS
========================================================= */

.pass-text {
  color: var(--right-text);
}


.fail-text {
  color: var(--wrong-text);
}


.badge {
  width: fit-content;

  display: inline-flex;
  align-items: center;

  padding: 5px 8px;

  border-radius: 6px;

  font-size: 8px;
  font-weight: 750;

  text-transform: uppercase;
  letter-spacing: 0.05em;
}


.badge.pass {
  background: var(--right-accent);

  color: var(--white);
}


.badge.fail {
  background: var(--wrong-accent);

  color: var(--white);
}


/* =========================================================
   BODY
========================================================= */

.modal-body {
  flex: 1;

  overflow-y: auto;

  padding: 14px 16px;
}


.modal-body::-webkit-scrollbar {
  width: 4px;
}


.modal-body::-webkit-scrollbar-track {
  background: transparent;
}


.modal-body::-webkit-scrollbar-thumb {
  background: var(--gray-300);

  border-radius: 999px;
}


.modal-body::-webkit-scrollbar-thumb:hover {
  background: var(--gray-400);
}


/* =========================================================
   QUESTION LIST
========================================================= */

.question-list {
  display: flex;
  flex-direction: column;

  gap: 8px;
}


/* =========================================================
   QUESTION ITEM
========================================================= */

.question-item {
  display: flex;

  gap: 11px;

  padding: 12px;

  background: var(--white);

  border: 1px solid var(--gray-200);
  border-radius: 10px;

  transition:
    background 0.15s ease,
    border-color 0.15s ease;
}


.question-item:hover {
  background: var(--gray-100);

  border-color: var(--gray-300);
}


/* =========================================================
   QUESTION NUMBER
========================================================= */

.q-indicator {
  width: 26px;
  height: 26px;

  flex: 0 0 26px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 7px;

  font-size: 9px;
  font-weight: 750;
}


/* RIGHT QUESTION */

.ind-pass {
  background: var(--right-accent);

  color: var(--white);
}


/* WRONG QUESTION */

.ind-fail {
  background: var(--wrong-accent);

  color: var(--white);
}


/* =========================================================
   QUESTION CONTENT
========================================================= */

.q-content {
  min-width: 0;
  flex: 1;

  display: flex;
  flex-direction: column;

  gap: 8px;
}


.q-text {
  margin: 0;

  color: var(--black);

  font-size: 11px;
  line-height: 1.5;

  font-weight: 650;
}


/* =========================================================
   ANSWERS
========================================================= */

.answers-stack {
  display: flex;
  flex-direction: column;

  gap: 6px;

  font-size: 9px;
}


/* =========================================================
   ANSWER BOX
========================================================= */

.ans-line {
  min-width: 0;

  display: flex;
  align-items: center;

  gap: 8px;

  padding: 9px 10px;

  border-radius: 8px;

  line-height: 1.5;

  transition:
    transform 0.15s ease,
    box-shadow 0.15s ease;
}


/* =========================================================
   RIGHT ANSWER BOX
========================================================= */

.ans-line:has(.text-pass) {
  background: var(--right-bg);

  border: 1px solid var(--right-border);

  box-shadow:
    inset 3px 0 0 var(--right-accent);
}



/* =========================================================
   WRONG ANSWER BOX
========================================================= */

.ans-line:has(.text-fail) {
  background: var(--wrong-bg);

  border: 1px solid var(--wrong-border);

  box-shadow:
    inset 3px 0 0 var(--wrong-accent);
}

/* =========================================================
   STATUS ICON
========================================================= */

.ans-line:has(.text-pass)::before {
  content: "✓";

  width: 19px;
  height: 19px;

  flex: 0 0 19px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 5px;

  background: var(--right-accent);

  color: var(--white);

  font-size: 9px;
  font-weight: 800;
}


.ans-line:has(.text-fail)::before {
  content: "×";

  width: 19px;
  height: 19px;

  flex: 0 0 19px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 5px;

  background: var(--wrong-accent);

  color: var(--white);

  font-size: 11px;
  font-weight: 800;
}


/* =========================================================
   ANSWER LABEL
========================================================= */

.ans-label {
  width: 48px;

  flex: 0 0 48px;

  color: var(--gray-500);

  font-size: 8px;
  font-weight: 700;

  text-transform: uppercase;
  letter-spacing: 0.03em;
}


/* =========================================================
   ANSWER VALUE
========================================================= */

.ans-val {
  min-width: 0;

  font-size: 9px;
  font-weight: 650;

  overflow-wrap: anywhere;
}


/* RIGHT TEXT */

.text-pass {
  color: var(--right-text);
}


/* WRONG TEXT */

.text-fail {
  color: var(--wrong-text);
}


/* =========================================================
   FOOTER
========================================================= */

.modal-footer {
  display: flex;
  justify-content: flex-end;

  padding: 12px 16px;

  border-top: 1px solid var(--gray-200);
}


.btn-secondary {
  min-height: 34px;

  padding: 7px 13px;

  border: 1px solid var(--gray-300);
  border-radius: 7px;

  background: var(--white);

  color: var(--gray-500);

  font-size: 9px;
  font-weight: 700;

  cursor: pointer;

  transition:
    background 0.15s ease,
    border-color 0.15s ease,
    color 0.15s ease,
    transform 0.15s ease;
}


.btn-secondary:hover {
  background: var(--black);

  border-color: var(--black);

  color: var(--white);

  transform: translateY(-1px);
}


.btn-secondary:active {
  transform: scale(0.98);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
  padding: 40px 20px;

  text-align: center;

  color: var(--gray-400);

  font-size: 10px;
}


/* =========================================================
   TRANSITION
========================================================= */

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}


.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 600px) {

  .modal-overlay {
    padding: 10px;
  }


  .modal-content {
    max-height: 92vh;

    border-radius: 12px;
  }


  .modal-header {
    padding: 14px 15px;
  }


  /* SUMMARY */

  .modal-summary {
    grid-template-columns:
      repeat(2, 1fr);
  }


  .m-stat {
    padding: 11px 13px;

    border-bottom: 1px solid var(--gray-200);
  }


  .m-stat:nth-child(2) {
    border-right: none;
  }


  .m-stat:nth-child(3),
  .m-stat:nth-child(4) {
    border-bottom: none;
  }


  /* BODY */

  .modal-body {
    padding: 11px;
  }


  /* QUESTION */

  .question-item {
    padding: 10px;

    gap: 9px;
  }


  .q-text {
    font-size: 10px;
  }


  .answers-stack {
    gap: 5px;
  }


  .ans-line {
    padding: 8px 9px;
  }


  /* FOOTER */

  .modal-footer {
    padding: 10px 11px;
  }
}


/* =========================================================
   SMALL PHONES
========================================================= */

@media (max-width: 380px) {

  .modal-header {
    padding: 12px;
  }


  .title-group h3 {
    font-size: 14px;
  }


  .subtitle {
    font-size: 9px;
  }


  .modal-summary {
    grid-template-columns:
      1fr 1fr;
  }


  .m-stat {
    padding: 10px;
  }


  .m-stat strong,
  .m-stat h3 {
    font-size: 13px;
  }


  .question-item {
    padding: 9px;
  }


  .q-indicator {
    width: 24px;
    height: 24px;

    flex-basis: 24px;
  }


  .q-text {
    font-size: 9.5px;
  }


  .ans-line {
    padding: 7px 8px;

    gap: 6px;
  }


  .ans-line:has(.text-pass)::before,
  .ans-line:has(.text-fail)::before {
    width: 17px;
    height: 17px;

    flex-basis: 17px;
  }


  .ans-label {
    width: 42px;

    flex-basis: 42px;
  }


  .ans-val {
    font-size: 8.5px;
  }
}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

  .fade-enter-active,
  .fade-leave-active,
  .question-item,
  .ans-line,
  .close-x,
  .btn-secondary {
    transition: none;
  }
}
</style>