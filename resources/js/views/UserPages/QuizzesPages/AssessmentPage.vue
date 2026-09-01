<template>
    <div class="assessment-page">

        <!-- Back -->
        <button class="back-btn" @click="$router.back()">
            <i class="fas fa-arrow-left"></i>
            Back
        </button>

        <!-- COC Header -->
        <div class="coc-header">
            <div class="coc-icon">
                <i class="fas fa-desktop"></i>
            </div>

            <div>
                <h1>COC {{ assessmentObject.coc_number }}</h1>
                <p>{{ assessmentObject.title }}</p>
                <span>
                    {{ assessmentObject.description }}
                </span>
            </div>
        </div>

        <!-- Assessment Types -->
        <div class="section-heading">
            <h2>Choose Assessment</h2>
            <p>Select an assessment type to begin.</p>
        </div>

        <div class="assessment-grid">

            <!-- Multiple Choice -->
            <div class="assessment-card active">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-list-check"></i>
                    </div>

                    <span class="available">
                        Available
                    </span>
                </div>

                <h3>Multiple Choice</h3>

                <p class="card-description">
                    Test your knowledge of computer systems and basic troubleshooting.
                </p>

                <!-- Tags -->
                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Hardware
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Easy
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <i class="fas fa-question-circle"></i>
                        10 Questions
                    </span>

                    <button @click="multipleChoiceQuiz">
                        Start
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>


            <!-- Image Identification -->
            <div class="assessment-card active">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-image"></i>
                    </div>

                    <span class="available">
                        Available
                    </span>
                </div>

                <h3>Image Identification</h3>

                <p class="card-description">
                    Identify computer components, tools, ports, and equipment.
                </p>

                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Components
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Medium
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <i class="fas fa-microscope"></i>
                        Practical lab
                    </span>
                    <button @click="imageIdentificationQuiz">
                        Start
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>


            <!-- Drag & Drop -->
            <div class="assessment-card active">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-arrows-up-down-left-right"></i>
                    </div>

                    <span class="available">
                        Available
                    </span>
                </div>

                <h3>Drag & Drop</h3>

                <p class="card-description">
                    Arrange computer components and installation procedures correctly.
                </p>

                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Installation
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Medium
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <span>
                            <i class="fas fa-question-circle"></i>
                            10 Questions
                        </span>

                    </span>
                    <button @click="dragDropQuiz">
                        Start
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>


            <!-- Scenario Based -->
            <div class="assessment-card locked">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-comments"></i>
                    </div>

                    <span class="available">
                        Available
                    </span>
                </div>

                <h3>Scenario-Based</h3>

                <p class="card-description">
                    Solve realistic computer problems using troubleshooting knowledge.
                </p>

                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Troubleshooting
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Hard
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <i class="fas fa-bolt"></i>
                        Hands-on scenario
                    </span>
                    <button @click="scenarioQuiz">
                        Start
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>


            <!-- Virtual Hands-On -->
            <div class="assessment-card locked">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-screwdriver-wrench"></i>
                    </div>

                    <span class="coming">
                        Coming Soon
                    </span>
                </div>

                <h3>Virtual Hands-On</h3>

                <p class="card-description">
                    Perform computer installation and configuration tasks virtually.
                </p>

                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Practical
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Hard
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <i class="fas fa-lock"></i>
                        Not Available
                    </span>
                </div>

            </div>


            <!-- RJ-45 Hands-On -->
            <div class="assessment-card active">

                <div class="card-top">
                    <div class="type-icon">
                        <i class="fas fa-ethernet"></i>
                    </div>

                    <span class="available">
                        Available
                    </span>
                </div>

                <h3>RJ-45 Cable Activity</h3>

                <p class="card-description">
                    Cut, arrange, connect, crimp, and test a network cable using the T568B standard.
                </p>

                <div class="tags">
                    <span class="tag category">
                        <i class="fas fa-layer-group"></i>
                        Networking
                    </span>

                    <span class="tag difficulty">
                        <i class="fas fa-signal"></i>
                        Hard
                    </span>
                </div>

                <div class="card-footer">
                    <span>
                        <i class="fas fa-flask"></i>
                        4 practical stages
                    </span>
                    <button @click="rj45HandsOn">
                        Start
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>

        </div>

    </div>
</template>


<script setup>
import { useRouter, useRoute } from 'vue-router'
import { onMounted, ref } from 'vue'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const assessmentObject = ref({});


const fetchAssessmentData = async () => {
    const quizId = route.params.id;

    const { data } = await axios.get('/api/quizzes')
    assessmentObject.value = data.data[quizId - 1] || {};
    console.log(assessmentObject)
}

const multipleChoiceQuiz = () => {
    // Change this route to your actual quiz 
    // const quizId = route.params.quiz_id
    const quizId = route.params.id
    console.log(quizId)

    if (!quizId) {
        router.push('/user/quizzes')
        return
    }
    router.push(`/quiz/${quizId}`)
}

const dragDropQuiz = () => {
    const quizId = route.params.id
    console.log(quizId)

    if (!quizId) {
        router.push('/user/quizzes')
        return
    }
    router.push(`/dragdrop/${quizId}`)
}

const scenarioQuiz = () => {
    const quizId = route.params.id

    if (!quizId) {
        router.push('/user/quizzes')
        return
    }

    router.push(`/dragdrop/${quizId}`)
}

const imageIdentificationQuiz = () => {
    const quizId = route.params.id

    if (!quizId) {
        router.push('/user/quizzes')
        return
    }

    router.push(`/image-identification/${quizId}`)
}

const rj45HandsOn = () => {
    const quizId = route.params.id

    if (!quizId) {
        router.push('/user/quizzes')
        return
    }

    router.push(`/rj45-hands-on/${quizId}`)
}

onMounted(() => {
    fetchAssessmentData()
})

</script>


<style scoped>
/* =========================================================
   FROSTED NOIR — ASSESSMENT PAGE
   #FFFFFF  White
   #000000  Black
   #A9A9A9  Gray
   #D3D3D3  Light Gray
   #696969  Dark Gray
========================================================= */

.assessment-page {
    --white: #ffffff;
    --black: #000000;

    --gray-light: #d3d3d3;
    --gray: #a9a9a9;
    --gray-dark: #696969;

    --background: #f6f6f6;
    --surface: #ffffff;
    --surface-soft: #f8f8f8;

    --border: #d3d3d3;

    width: 100%;
    min-width: 0;
    min-height: 100vh;

    padding: clamp(16px, 3vw, 32px);

    background: var(--background);
    color: var(--black);

    box-sizing: border-box;
    overflow-x: hidden;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    margin: 0 0 20px;

    padding: 8px 0;

    border: none;
    background: transparent;

    color: var(--gray-dark);

    font-family: inherit;
    font-size: 13px;
    font-weight: 600;

    cursor: pointer;

    transition:
        color 0.18s ease,
        transform 0.18s ease;
}

.back-btn:hover {
    color: var(--black);
}

.back-btn:hover i {
    transform: translateX(-3px);
}

.back-btn i {
    transition: transform 0.18s ease;
}


/* =========================================================
   COC HEADER
========================================================= */

.coc-header {
    width: 100%;

    display: flex;
    align-items: center;

    gap: 18px;

    padding: clamp(18px, 3vw, 24px);

    margin-bottom: 32px;

    background: var(--surface);

    border: 1px solid var(--border);

    border-radius: 14px;

    box-sizing: border-box;

    box-shadow:
        0 4px 16px rgba(0, 0, 0, 0.04);
}


/* =========================================================
   COC ICON
========================================================= */

.coc-icon {
    width: 54px;
    height: 54px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: var(--black);

    color: var(--white);

    border-radius: 10px;

    font-size: 20px;
}


/* =========================================================
   COC TEXT
========================================================= */

.coc-header h1 {
    margin: 0 0 3px;

    color: var(--gray-dark);

    font-size: 12px;

    font-weight: 800;

    letter-spacing: 0.08em;

    text-transform: uppercase;
}

.coc-header p {
    margin: 0;

    color: var(--black);

    font-size: clamp(17px, 2vw, 20px);

    font-weight: 700;

    line-height: 1.35;
}

.coc-header span {
    display: block;

    margin-top: 5px;

    color: var(--gray-dark);

    font-size: 12px;

    line-height: 1.5;

    overflow-wrap: anywhere;
}


/* =========================================================
   SECTION HEADING
========================================================= */

.section-heading {
    margin-bottom: 16px;
}

.section-heading h2 {
    margin: 0;

    color: var(--black);

    font-size: 18px;

    font-weight: 750;

    letter-spacing: -0.01em;
}

.section-heading p {
    margin: 4px 0 0;

    color: var(--gray-dark);

    font-size: 12px;
}


/* =========================================================
   ASSESSMENT GRID
========================================================= */

.assessment-grid {
    width: 100%;

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(min(100%, 270px), 1fr));

    gap: 14px;

    box-sizing: border-box;
}


/* =========================================================
   ASSESSMENT CARD
========================================================= */

.assessment-card {
    min-width: 0;

    display: flex;
    flex-direction: column;

    padding: 19px;

    background: var(--surface);

    border: 1px solid var(--border);

    border-radius: 12px;

    box-sizing: border-box;

    transition:
        transform 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}


/* =========================================================
   ACTIVE CARD
========================================================= */

.assessment-card.active {
    cursor: default;
}

.assessment-card.active:hover {
    transform: translateY(-3px);

    border-color: var(--gray-dark);

    box-shadow:
        0 10px 24px rgba(0, 0, 0, 0.08);
}


/* =========================================================
   LOCKED CARD
========================================================= */

.assessment-card.locked {
    background: #fafafa;

    opacity: 0.58;
}


/* =========================================================
   CARD TOP
========================================================= */

.card-top {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;
}


/* =========================================================
   TYPE ICON
========================================================= */

.type-icon {
    width: 40px;
    height: 40px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: var(--surface-soft);

    border: 1px solid var(--gray-light);

    color: var(--gray-dark);

    font-size: 15px;
}


/* Active assessment icon */

.assessment-card.active .type-icon {
    background: var(--black);

    border-color: var(--black);

    color: var(--white);
}


/* =========================================================
   STATUS
========================================================= */

.available,
.coming {
    display: inline-flex;

    align-items: center;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 9px;

    font-weight: 750;

    letter-spacing: 0.04em;

    text-transform: uppercase;

    white-space: nowrap;
}


/* Available */

.available {
    background: #f0f0f0;

    color: var(--black);

    border: 1px solid var(--gray-light);
}


/* Coming soon */

.coming {
    background: #eeeeee;

    color: var(--gray-dark);

    border: 1px solid var(--gray-light);
}


/* =========================================================
   CARD TITLE
========================================================= */

.assessment-card h3 {
    margin: 16px 0 6px;

    color: var(--black);

    font-size: 15px;

    font-weight: 750;

    line-height: 1.3;
}


/* =========================================================
   DESCRIPTION
========================================================= */

.card-description {
    margin: 0;

    min-height: 42px;

    color: var(--gray-dark);

    font-size: 11.5px;

    line-height: 1.55;
}


/* =========================================================
   TAGS
========================================================= */

.tags {
    display: flex;

    flex-wrap: wrap;

    gap: 6px;

    margin-top: 15px;
}

.tag {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    border-radius: 5px;

    font-size: 9.5px;

    font-weight: 700;

    line-height: 1;
}


/* Category */

.tag.category {
    background: #f2f2f2;

    color: var(--gray-dark);

    border: 1px solid #e3e3e3;
}


/* Difficulty */

.tag.difficulty {
    background: #e9e9e9;

    color: var(--black);

    border: 1px solid var(--gray-light);
}


/* =========================================================
   CARD FOOTER
========================================================= */

.card-footer {
    width: 100%;

    margin-top: 18px;

    padding-top: 13px;

    border-top: 1px solid #e8e8e8;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    box-sizing: border-box;
}


/* Footer information */

.card-footer>span {
    min-width: 0;

    display: flex;

    align-items: center;

    gap: 5px;

    color: var(--gray-dark);

    font-size: 10px;

    white-space: nowrap;
}


/* =========================================================
   START BUTTON
========================================================= */

.card-footer button {
    flex-shrink: 0;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    padding: 8px 12px;

    border: 1px solid var(--black);

    border-radius: 6px;

    background: var(--black);

    color: var(--white);

    font-family: inherit;

    font-size: 10px;

    font-weight: 700;

    cursor: pointer;

    transition:
        background 0.18s ease,
        border-color 0.18s ease,
        transform 0.12s ease;
}

.card-footer button:hover {
    background: var(--gray-dark);

    border-color: var(--gray-dark);
}

.card-footer button:active {
    transform: scale(0.96);
}


/* =========================================================
   LOCKED FOOTER
========================================================= */

.assessment-card.locked .card-footer {
    color: var(--gray);
}

.assessment-card.locked .card-footer i {
    color: var(--gray);
}


/* =========================================================
   LARGE TABLET
========================================================= */

@media (max-width: 1024px) {

    .assessment-page {
        padding: 24px;
    }

    .assessment-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 768px) {

    .assessment-page {
        padding: 20px 16px;
    }

    .coc-header {
        gap: 14px;

        margin-bottom: 26px;
    }

    .coc-icon {
        width: 48px;
        height: 48px;

        font-size: 18px;
    }

    .assessment-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 12px;
    }

    .assessment-card {
        padding: 16px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 600px) {

    .assessment-page {
        padding: 16px 12px;
    }

    .back-btn {
        margin-bottom: 15px;
    }

    .coc-header {
        align-items: flex-start;

        padding: 16px;

        margin-bottom: 24px;

        border-radius: 12px;
    }

    .coc-icon {
        width: 44px;
        height: 44px;

        font-size: 16px;
    }

    .coc-header h1 {
        font-size: 10px;
    }

    .coc-header p {
        font-size: 16px;
    }

    .coc-header span {
        font-size: 11px;
    }

    .section-heading {
        margin-bottom: 13px;
    }

    .section-heading h2 {
        font-size: 16px;
    }

    .assessment-grid {
        grid-template-columns: 1fr;

        gap: 10px;
    }

    .assessment-card {
        padding: 16px;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .assessment-page {
        padding: 12px 10px;
    }

    .coc-header {
        gap: 11px;

        padding: 14px;
    }

    .coc-icon {
        width: 40px;
        height: 40px;

        border-radius: 8px;

        font-size: 14px;
    }

    .coc-header p {
        font-size: 15px;
    }

    .coc-header span {
        font-size: 10px;
    }

    .assessment-card {
        padding: 14px;

        border-radius: 10px;
    }

    .type-icon {
        width: 36px;
        height: 36px;

        font-size: 13px;
    }

    .assessment-card h3 {
        margin-top: 14px;

        font-size: 14px;
    }

    .card-description {
        min-height: 0;

        font-size: 11px;
    }

    .card-footer {
        margin-top: 15px;

        padding-top: 11px;
    }

}


/* =========================================================
   EXTRA SMALL
========================================================= */

@media (max-width: 330px) {

    .assessment-page {
        padding: 10px 8px;
    }

    .coc-header {
        padding: 12px;
    }

    .available,
    .coming {
        padding: 4px 6px;

        font-size: 8px;
    }

    .assessment-card {
        padding: 13px;
    }

    .card-footer>span {
        font-size: 9px;
    }

    .card-footer button {
        padding: 7px 9px;

        font-size: 9px;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .assessment-card,
    .back-btn,
    .card-footer button {
        transition: none;
    }

}
</style>