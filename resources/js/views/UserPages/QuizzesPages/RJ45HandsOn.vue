<template>
  <div class="rj45-page">
    <header class="assessment-header">
      <div class="header-left">
        <button class="back-btn" type="button" title="Go back" @click="router.back()">
          <i class="fas fa-arrow-left"></i>
        </button>
        <div>
          <span class="assessment-label">COC 1 / Practical Bench</span>
          <h1>RJ-45 Cable Termination</h1>
        </div>
      </div>
      <div class="progress-info">
        <span>Stage {{ stage }} of 4</span>
        <div class="progress-bar">
          <div class="progress-fill" :style="{ width: `${progress}%` }"></div>
        </div>
      </div>
    </header>

    <main class="assessment-container">
      <section class="scenario-card">
        <div class="scenario-meta">
          <span><i class="fas fa-network-wired"></i> Ethernet lab</span
          ><span><i class="fas fa-shield-halved"></i> T568{{ selectedStandard }} standard</span>
        </div>
        <span class="scenario-kicker"
          ><i class="fas fa-triangle-exclamation"></i> Field repair ticket / NET-045</span
        >
        <h2>
          Terminate and verify a replacement patch cable for the offline workstation.
        </h2>
        <p>
          You are the technician on shift. Prepare the cable, arrange the conductors,
          insert them into the RJ-45 plug, crimp the contact, and verify continuity.
        </p>
      </section>

      <section class="bench">
        <aside class="procedure-panel">
          <div class="panel-heading">
            <div>
              <span class="panel-eyebrow">Work order</span>
              <h3>Termination steps</h3>
            </div>
            <span class="bench-code">RJ45</span>
          </div>
          <button
            v-for="stepItem in procedureSteps"
            :key="stepItem.id"
            type="button"
            class="procedure-step"
            :class="{ current: stage === stepItem.id, complete: stage > stepItem.id }"
            :disabled="stage < stepItem.id"
            @click="stage = stepItem.id"
          >
            <span class="step-number">{{ stepItem.id }}</span
            ><span
              ><strong>{{ stepItem.title }}</strong
              ><small>{{ stepItem.detail }}</small></span
            ><i
              :class="stage > stepItem.id ? 'fas fa-check' : 'fas fa-chevron-right'"
            ></i>
          </button>
          <div class="standard-note">
            <i class="fas fa-circle-info"></i
              ><span
              >Pin 1 starts at {{ targetOrder[0].name.toLowerCase() }}. Keep the clip facing away from you.</span
            >
          </div>
        </aside>

        <section class="work-surface">
          <div class="surface-header">
            <div>
              <span class="panel-eyebrow">Live workbench</span>
              <h3>{{ currentStep.title }}</h3>
            </div>
            <span class="stage-counter">0{{ stage }}</span>
          </div>

          <div v-if="stage === 1" class="stage-content cut-stage">
            <div class="standard-picker">
              <span class="panel-eyebrow">Wiring standard</span>
              <div class="standard-options">
                <button v-for="standard in ['A', 'B']" :key="standard" type="button" class="standard-option"
                  :class="{ selected: selectedStandard === standard }" @click="chooseStandard(standard)">
                  <strong>Type {{ standard }}</strong><small>{{ standard === 'A' ? 'T568A pinout' : 'T568B pinout' }}</small>
                </button>
              </div>
            </div>
            <div class="cable-illustration" @dragover.prevent @drop="dropScissors">
              <div class="cable-jacket" :class="{ cut: cableCut }"></div>
              <div class="cut-mark"></div>
              <div class="cable-wires"><i v-for="wire in targetOrder.slice(0, 4)" :key="wire.id" :class="wire.className"></i></div>
              <div class="cable-label">CAT5e cable</div>
            </div>
            <div class="instruction">
              <strong>Cut and strip the jacket</strong
              ><span
                >Make a clean cut at the marked line, then expose enough conductor to
                reach the plug.</span
              >
            </div>
            <button class="scissor-tool" type="button" draggable="true" @dragstart="dragScissors" @dragend="scissorDragged = false" @click="cutCable">
              <i class="fas fa-scissors"></i><span>Drag or tap to cut</span>
            </button>
            <button class="action-btn" type="button" :disabled="cableCut" @dragover.prevent @drop="dropScissors" @click="cutCable">
              <i class="fas fa-scissors"></i> {{ cableCut ? 'Cable stripped' : 'Cut and strip cable' }}
            </button>
          </div>

          <div v-else-if="stage === 2" class="stage-content wiring-stage">
            <div class="instruction">
              <strong>Arrange the conductors in T568{{ selectedStandard }} order</strong
              ><span
                >Drag each wire to a pin, or use the numbered buttons. Pin 1 is on the
                left.</span
              >
            </div>
            <div class="wire-tray">
              <button
                v-for="wire in looseWires"
                :key="wire.id"
                type="button"
                class="wire-chip"
                :class="wire.className"
                draggable="true"
                @dragstart="dragWire(wire)"
                @click="selectWire(wire)"
              >
                <i class="fas fa-grip-lines"></i>{{ wire.name }}
              </button>
            </div>
            <div class="pin-row" @dragover.prevent @drop="dropWire(null)">
              <button
                v-for="(wire, index) in arrangedWires"
                :key="index"
                type="button"
                class="pin-slot"
                :class="wire?.className"
                @click="selectPin(index)"
                @dragover.prevent
                @drop.stop="dropWire(index)"
              >
                <span>Pin {{ index + 1 }}</span
                ><strong>{{ wire?.name || "Drop wire" }}</strong>
              </button>
            </div>
            <div class="wire-order">
              <span>Required:</span
              ><b v-for="wire in targetOrder" :key="wire.id" :class="wire.className">{{
                wire.short
              }}</b>
            </div>
            <button
              class="action-btn"
              type="button"
              :disabled="!isWiringCorrect"
              @click="finishWiring"
            >
              <i class="fas fa-list-check"></i> Lock T568{{ selectedStandard }} order
            </button>
          </div>

          <div v-else-if="stage === 3" class="stage-content insert-stage">
            <div class="connector-illustration" @dragover.prevent @drop="dropConnector">
              <div class="plug">
                <span
                  v-for="wire in arrangedWires"
                  :key="wire.id"
                  :class="wire.className"
                ></span>
              </div>
              <i class="fas fa-arrow-right"></i>
              <div class="crimper" draggable="true" @dragstart="dragConnector" @dragend="connectorDragged = false" @click="crimpConnector"><i class="fas fa-screwdriver-wrench"></i><small>RJ-45 plug</small></div>
            </div>
            <div class="instruction">
              <strong>Insert and crimp the connector</strong
              ><span
                >Slide all eight conductors fully into the clear plug, then press the
                crimper once to seat the contacts.</span
              >
            </div>
            <button class="action-btn" type="button" :disabled="connectorInserted" @click="crimpConnector">
              <i class="fas fa-compress"></i> Insert and crimp
            </button>
          </div>

          <div v-else class="stage-content test-stage">
            <div class="tester">
              <div class="tester-top">
                <i class="fas fa-flask"></i><span>LAN CABLE TESTER</span>
              </div>
              <div class="tester-screen">{{ testResult || "READY / CONNECT CABLE" }}</div>
              <div class="tester-port" :class="{ testing }">
                <span
                  v-for="(wire, index) in arrangedWires"
                  :key="index"
                  :class="[wire.className, { lit: testLights.includes(index) }]"
                ></span>
              </div>
            </div>
            <div class="instruction">
              <strong>Test continuity</strong
              ><span
                >Connect the terminated cable to the tester. A pass requires all eight
                pins to light in sequence.</span
              >
            </div>
            <button class="action-btn" type="button" :disabled="tested || testing" @click="runTest">
              <i class="fas fa-bolt"></i>
              {{ testing ? "Scanning pins..." : tested ? "Test complete" : "Run continuity test" }}
            </button>
            <div v-if="tested" class="test-result" :class="{ pass: testPassed }">
              <i :class="testPassed ? 'fas fa-circle-check' : 'fas fa-circle-xmark'"></i
              ><strong>{{
                testPassed ? "PASS / Cable ready" : "FAIL / Re-terminate cable"
              }}</strong
              ><span>{{
                testPassed
                  ? `Pins 1-8 are continuous in T568${selectedStandard} order.`
                  : "The pinout does not match the required standard."
              }}</span>
            </div>
          </div>
        </section>
      </section>

      <section class="assessment-actions">
        <button class="reset-btn" type="button" @click="resetBench">
          <i class="fas fa-rotate-left"></i> Reset bench</button
        ><button
          class="submit-btn"
          type="button"
          :disabled="!testPassed"
          @click="finishActivity"
        >
          Complete activity <i class="fas fa-arrow-right"></i>
        </button>
      </section>
    </main>
    <div v-if="completed" class="completion-notice">
      <i class="fas fa-certificate"></i
      ><span
        ><strong>Activity complete</strong
        ><small>RJ-45 cable passed continuity testing.</small></span
      ><button type="button" @click="router.push(`/user/quizzes/assessment/${quizId}`)">
        Return to assessments
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, onUnmounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

const router = useRouter();
const route = useRoute();
const quizId = route.params.id;
const stage = ref(1);
const completed = ref(false);
const tested = ref(false);
const testPassed = ref(false);
const testResult = ref("");
const selectedWire = ref(null);
const draggedWire = ref(null);
const cableCut = ref(false);
const scissorDragged = ref(false);
const connectorInserted = ref(false);
const connectorDragged = ref(false);
const testing = ref(false);
const testLights = ref([]);
let testTimer = null;

const wireSets = {
  A: [
    { id: "wg", name: "White-green", short: "WG", className: "white-green" },
    { id: "g", name: "Green", short: "G", className: "green" },
    { id: "wo", name: "White-orange", short: "WO", className: "white-orange" },
    { id: "b", name: "Blue", short: "B", className: "blue" },
    { id: "wb", name: "White-blue", short: "WB", className: "white-blue" },
    { id: "o", name: "Orange", short: "O", className: "orange" },
    { id: "wbr", name: "White-brown", short: "WBr", className: "white-brown" },
    { id: "br", name: "Brown", short: "Br", className: "brown" },
  ],
  B: [
    { id: "wo", name: "White-orange", short: "WO", className: "white-orange" },
    { id: "o", name: "Orange", short: "O", className: "orange" },
    { id: "wg", name: "White-green", short: "WG", className: "white-green" },
    { id: "b", name: "Blue", short: "B", className: "blue" },
    { id: "wb", name: "White-blue", short: "WB", className: "white-blue" },
    { id: "g", name: "Green", short: "G", className: "green" },
    { id: "wbr", name: "White-brown", short: "WBr", className: "white-brown" },
    { id: "br", name: "Brown", short: "Br", className: "brown" },
  ],
};
const selectedStandard = ref("B");
const targetOrder = computed(() => wireSets[selectedStandard.value]);

const procedureSteps = [
  { id: 1, title: "Cut and strip", detail: "Expose conductors" },
  { id: 2, title: "Arrange wires", detail: "Follow T568B" },
  { id: 3, title: "Insert and crimp", detail: "Seat the plug" },
  { id: 4, title: "Test continuity", detail: "Verify pins 1-8" },
];
const looseWires = ref([...wireSets.B].sort(() => Math.random() - 0.5));
const arrangedWires = ref(Array(8).fill(null));
const currentStep = computed(() => procedureSteps[stage.value - 1]);
const progress = computed(() => ((stage.value - 1) / 3) * 100);
const isWiringCorrect = computed(() =>
  arrangedWires.value.every((wire, index) => wire?.id === targetOrder.value[index]?.id)
);

const chooseStandard = (standard) => {
  selectedStandard.value = standard;
  arrangedWires.value = Array(8).fill(null);
  looseWires.value = [...wireSets[standard]].sort(() => Math.random() - 0.5);
};
const cutCable = () => {
  cableCut.value = true;
  window.setTimeout(() => { stage.value = 2; }, 550);
};
const dragScissors = () => { scissorDragged.value = true; };
const dropScissors = () => { if (scissorDragged.value) cutCable(); scissorDragged.value = false; };
const selectWire = (wire) => {
  selectedWire.value = wire;
};
const dragWire = (wire) => {
  draggedWire.value = wire;
};
const dropWire = (index) => {
  const wire = draggedWire.value || selectedWire.value;
  if (!wire) return;
  const oldIndex = arrangedWires.value.findIndex((item) => item?.id === wire.id);
  if (oldIndex >= 0) arrangedWires.value[oldIndex] = null;
  const targetIndex =
    index === null ? arrangedWires.value.findIndex((item) => !item) : index;
  if (targetIndex >= 0) {
    const displaced = arrangedWires.value[targetIndex];
    arrangedWires.value[targetIndex] = wire;
    if (displaced && displaced.id !== wire.id) looseWires.value.push(displaced);
    looseWires.value = looseWires.value.filter((item) => item.id !== wire.id);
  }
  selectedWire.value = null;
  draggedWire.value = null;
};
const selectPin = (index) => {
  if (selectedWire.value) dropWire(index);
};
const finishWiring = () => {
  if (isWiringCorrect.value) stage.value = 3;
};
const crimpConnector = () => {
  connectorInserted.value = true;
  window.setTimeout(() => { stage.value = 4; }, 450);
};
const dragConnector = () => { connectorDragged.value = true; };
const dropConnector = () => { if (connectorDragged.value) crimpConnector(); connectorDragged.value = false; };
const runTest = () => {
  if (testing.value || tested.value) return;
  testing.value = true;
  testLights.value = [];
  let light = 0;
  testTimer = window.setInterval(() => {
    testLights.value.push(light);
    light += 1;
    if (light === 8) {
      window.clearInterval(testTimer);
      testTimer = null;
      testing.value = false;
      tested.value = true;
      testPassed.value = isWiringCorrect.value;
      testResult.value = testPassed.value ? "PASS / 1 2 3 4 5 6 7 8" : "FAIL / PINOUT ERROR";
    }
  }, 180);
};
const resetBench = () => {
  stage.value = 1;
  completed.value = false;
  tested.value = false;
  testPassed.value = false;
  testResult.value = "";
  cableCut.value = false;
  scissorDragged.value = false;
  connectorInserted.value = false;
  connectorDragged.value = false;
  testing.value = false;
  testLights.value = [];
  selectedWire.value = null;
  arrangedWires.value = Array(8).fill(null);
  looseWires.value = [...wireSets[selectedStandard.value]].sort(() => Math.random() - 0.5);
};
const finishActivity = () => {
  if (testPassed.value) completed.value = true;
};
onUnmounted(() => { if (testTimer) window.clearInterval(testTimer); });
</script>

<style scoped>
.rj45-page {
  --black: #000;
  --white: #fff;
  --gray-100: #f7f7f7;
  --gray-200: #eee;
  --gray-300: #d3d3d3;
  --gray-400: #a9a9a9;
  --gray-500: #696969;
  min-height: 100vh;
  padding-bottom: 50px;
  background: linear-gradient(rgba(211, 211, 211, 0.8) 1px, transparent 1px),
    linear-gradient(90deg, rgba(211, 211, 211, 0.8) 1px, transparent 1px), #f4f4f2;
  background-size: 28px 28px;
  color: var(--black);
  font-family: Inter, ui-sans-serif, system-ui, sans-serif;
}
.assessment-header {
  display: flex;
  position:sticky;
  top:0;
  left:0;
  align-items: center;
  justify-content: space-between;
  gap: 30px;
  padding: 14px 24px;
  background: white;
  z-index:99;
  border-bottom: 1px solid var(--gray-300);
  backdrop-filter: blur(18px);
}
.header-left {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
}
.back-btn {
  width: 36px;
  height: 36px;
  display: grid;
  place-items: center;
  border: 1px solid var(--gray-300);
  border-radius: 9px;
  background: var(--white);
  color: var(--gray-500);
  cursor: pointer;
}
.back-btn:hover,
.action-btn:hover:not(:disabled),
.submit-btn:hover:not(:disabled) {
  background: var(--black);
  color: var(--white);
}
.assessment-label,
.panel-eyebrow,
.scenario-kicker {
  display: block;
  color: var(--gray-500);
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}
.header-left h1 {
  margin: 2px 0 0;
  font-size: 18px;
}
.progress-info {
  width: 210px;
  color: var(--gray-500);
  font-size: 10px;
  font-weight: 700;
}
.progress-bar {
  height: 4px;
  margin-top: 7px;
  overflow: hidden;
  border-radius: 999px;
  background: var(--gray-200);
}
.progress-fill {
  height: 100%;
  background: var(--black);
  transition: width 0.3s ease;
}
.assessment-container {
  width: min(980px, calc(100% - 40px));
  margin: 26px auto 0;
}
.scenario-card,
.procedure-panel,
.work-surface {
  background: var(--white);
  border: 1px solid var(--gray-300);
  border-radius: 14px;
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.05);
}
.scenario-card {
  padding: 24px;
  background: var(--black);
  color: var(--white);
}
.scenario-meta {
  display: flex;
  gap: 18px;
  margin-bottom: 18px;
  color: #d3d3d3;
  font-size: 10px;
  font-weight: 700;
}
.scenario-meta i {
  margin-right: 5px;
  color: #a9a9a9;
}
.scenario-card .scenario-kicker {
  color: #d3d3d3;
}
.scenario-card h2 {
  max-width: 800px;
  margin: 8px 0 0;
  font-size: clamp(17px, 2vw, 23px);
  line-height: 1.4;
}
.scenario-card p {
  max-width: 760px;
  margin: 9px 0 0;
  color: #b9b9b9;
  font-size: 11px;
  line-height: 1.6;
}
.bench {
  display: grid;
  grid-template-columns: minmax(220px, 0.7fr) minmax(0, 1.3fr);
  gap: 12px;
  margin-top: 12px;
}
.procedure-panel,
.work-surface {
  padding: 17px;
}
.panel-heading,
.surface-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
}
.panel-heading h3,
.surface-header h3 {
  margin: 4px 0 14px;
  font-size: 13px;
}
.bench-code,
.stage-counter {
  padding: 6px 8px;
  border: 1px solid var(--gray-300);
  border-radius: 6px;
  color: var(--gray-500);
  font-size: 9px;
  font-weight: 800;
}
.procedure-step {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 7px;
  padding: 10px;
  border: 1px solid var(--gray-200);
  border-radius: 9px;
  background: var(--gray-100);
  color: var(--gray-500);
  text-align: left;
  cursor: pointer;
  font: inherit;
}
.procedure-step.current,
.procedure-step.complete {
  border-color: var(--black);
  background: var(--white);
  color: var(--black);
}
.procedure-step:disabled {
  cursor: not-allowed;
  opacity: 0.55;
}
.procedure-step i {
  margin-left: auto;
  font-size: 9px;
}
.step-number {
  width: 24px;
  height: 24px;
  display: grid;
  place-items: center;
  border-radius: 6px;
  background: var(--black);
  color: var(--white);
  font-size: 9px;
  font-weight: 800;
}
.procedure-step.complete .step-number {
  background: var(--gray-200);
  color: var(--black);
}
.procedure-step strong,
.procedure-step small {
  display: block;
}
.procedure-step strong {
  font-size: 10px;
}
.procedure-step small {
  margin-top: 2px;
  color: var(--gray-400);
  font-size: 9px;
}
.standard-note {
  display: flex;
  gap: 8px;
  margin-top: 15px;
  padding-top: 13px;
  border-top: 1px solid var(--gray-200);
  color: var(--gray-500);
  font-size: 9px;
  line-height: 1.5;
}
.standard-note i {
  color: var(--black);
}
.stage-content {
  min-height: 365px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 18px;
}
.instruction {
  display: grid;
  gap: 5px;
}
.instruction strong {
  font-size: 12px;
}
.instruction span {
  max-width: 600px;
  color: var(--gray-500);
  font-size: 10px;
  line-height: 1.5;
}
.cable-illustration {
  position: relative;
  height: 130px;
  display: grid;
  place-items: center;
  border: 1px solid var(--gray-200);
  border-radius: 10px;
  background: var(--gray-100);
  overflow: hidden;
}
.cable-jacket {
  width: 80%;
  height: 22px;
  border: 3px solid var(--gray-500);
  border-radius: 20px;
  background: var(--gray-300);
  transition: width 0.45s ease, opacity 0.45s ease;
}
.cable-jacket.cut {
  width: 42%;
  opacity: 0.45;
  animation: cable-cut 0.55s ease both;
}
.cut-mark {
  position: absolute;
  height: 58px;
  border-left: 2px dashed var(--black);
}
.cable-label {
  position: absolute;
  bottom: 15px;
  color: var(--gray-500);
  font-size: 9px;
  font-weight: 800;
  text-transform: uppercase;
}
.cable-wires {
  position: absolute;
  left: 58%;
  display: flex;
  gap: 3px;
  opacity: 0;
  transform: translateX(-8px);
  transition: opacity 0.35s ease, transform 0.35s ease;
}
.cable-wires i {
  width: 4px;
  height: 36px;
  border-radius: 4px;
}
.cable-jacket.cut + .cut-mark + .cable-wires {
  opacity: 1;
  transform: translateX(0);
}
.standard-picker { display: grid; gap: 7px; }
.standard-options { display: flex; gap: 7px; }
.standard-option { flex: 1; display: grid; gap: 3px; padding: 8px 10px; border: 1px solid var(--gray-300); border-radius: 7px; background: var(--white); color: var(--gray-500); text-align: left; cursor: pointer; font: inherit; }
.standard-option.selected { border-color: var(--black); background: var(--black); color: var(--white); }
.standard-option strong { font-size: 10px; }.standard-option small { font-size: 8px; color: var(--gray-400); }.standard-option.selected small { color: #d3d3d3; }
.scissor-tool { align-self: flex-start; display: inline-flex; align-items: center; gap: 8px; padding: 9px 11px; border: 1px solid var(--gray-300); border-radius: 8px; background: var(--gray-100); color: var(--gray-500); cursor: grab; font: inherit; font-size: 9px; font-weight: 800; }
.scissor-tool:hover { border-color: var(--black); color: var(--black); animation: tool-nudge 0.45s ease; }
.action-btn {
  align-self: flex-start;
  padding: 10px 14px;
  border: 1px solid var(--black);
  border-radius: 8px;
  background: var(--white);
  color: var(--black);
  font: inherit;
  font-size: 10px;
  font-weight: 800;
  cursor: pointer;
}
.action-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}
.wire-tray {
  display: flex;
  flex-wrap: wrap;
  gap: 7px;
  padding: 12px;
  border: 1px dashed var(--gray-300);
  border-radius: 9px;
  background: var(--gray-100);
}
.wire-chip {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px;
  border: 1px solid var(--gray-300);
  border-radius: 6px;
  background: var(--white);
  color: var(--black);
  font: inherit;
  font-size: 9px;
  font-weight: 800;
  cursor: grab;
}
.wire-chip i {
  color: var(--gray-400);
}
.pin-row {
  display: grid;
  grid-template-columns: repeat(8, minmax(0, 1fr));
  gap: 5px;
  padding: 10px;
  border: 1px solid var(--gray-300);
  border-radius: 9px;
  background: #fafafa;
}
.pin-slot {
  min-height: 66px;
  padding: 5px 2px;
  border: 1px dashed var(--gray-300);
  border-radius: 5px;
  background: var(--white);
  cursor: pointer;
}
.pin-slot span {
  display: block;
  color: var(--gray-400);
  font-size: 8px;
}
.pin-slot strong {
  display: block;
  margin-top: 8px;
  font-size: 8px;
  overflow-wrap: anywhere;
}
.wire-order {
  display: flex;
  align-items: center;
  gap: 5px;
  color: var(--gray-500);
  font-size: 9px;
}
.wire-order b {
  padding: 4px;
  border: 1px solid var(--gray-300);
  border-radius: 4px;
  background: var(--gray-100);
  font-size: 8px;
}
.white-orange,
.white-green,
.white-blue,
.white-brown {
  background: repeating-linear-gradient(135deg, #fff 0 4px, #d9a56b 4px 7px) !important;
}
.orange {
  background: #e38b39 !important;
}
.white-green {
  background: repeating-linear-gradient(135deg, #fff 0 4px, #7bb174 4px 7px) !important;
}
.blue {
  background: #4386c5 !important;
}
.white-blue {
  background: repeating-linear-gradient(135deg, #fff 0 4px, #79a8d2 4px 7px) !important;
}
.green {
  background: #4e9a55 !important;
}
.white-brown {
  background: repeating-linear-gradient(135deg, #fff 0 4px, #a87c55 4px 7px) !important;
}
.brown {
  background: #8b5b3e !important;
}
.pin-slot[class*="white-"] strong,
.pin-slot.orange strong,
.pin-slot.blue strong,
.pin-slot.green strong,
.pin-slot.brown strong {
  color: var(--black);
}
.connector-illustration {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 22px;
  height: 170px;
  border: 1px solid var(--gray-200);
  border-radius: 10px;
  background: var(--gray-100);
  font-size: 22px;
}
.plug {
  width: 130px;
  height: 75px;
  display: flex;
  align-items: flex-end;
  gap: 3px;
  padding: 14px;
  border: 2px solid var(--gray-500);
  border-radius: 7px 7px 18px 18px;
  background: #ffffffaa;
}
.plug span {
  height: 48px;
  flex: 1;
  border-radius: 2px;
}
.crimper {
  width: 70px;
  height: 95px;
  display: grid;
  place-items: center;
  border-radius: 8px;
  background: var(--black);
  color: var(--white);
  font-size: 24px;
  cursor: grab;
  transition: transform 0.25s ease;
}
.crimper:hover { transform: translateY(-4px) rotate(-3deg); }.crimper small { display: block; font-size: 8px; font-weight: 800; }
.plug.inserted { animation: connector-seat 0.45s ease both; }
.tester {
  padding: 18px;
  border: 1px solid var(--gray-300);
  border-radius: 10px;
  background: var(--black);
  color: var(--white);
}
.tester-top {
  display: flex;
  gap: 8px;
  align-items: center;
  color: #d3d3d3;
  font-size: 9px;
  font-weight: 800;
}
.tester-screen {
  margin: 15px 0;
  padding: 11px;
  background: #242424;
  color: #fff;
  font-family: monospace;
  font-size: 11px;
}
.tester-port {
  display: flex;
  gap: 3px;
  width: 140px;
  height: 18px;
  padding: 5px;
  margin: auto;
  border-radius: 4px;
  background: #d3d3d3;
}
.tester-port span {
  flex: 1;
  border-radius: 2px;
}
.tester-port span.lit { box-shadow: 0 0 9px 3px #fff; filter: brightness(1.5); animation: pin-light 0.3s ease; }
.tester-port.testing span:not(.lit) { opacity: .35; }
.test-result {
  display: grid;
  gap: 4px;
  padding: 12px;
  border: 1px solid var(--gray-300);
  border-radius: 8px;
  background: var(--gray-100);
  font-size: 10px;
}
.test-result i {
  font-size: 18px;
}
.test-result span {
  color: var(--gray-500);
}
.test-result.pass i {
  color: #222;
}
.assessment-actions {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  margin-top: 14px;
}
.reset-btn,
.submit-btn {
  min-height: 39px;
  padding: 8px 14px;
  border-radius: 8px;
  font: inherit;
  font-size: 10px;
  font-weight: 800;
  cursor: pointer;
}
.reset-btn {
  border: 1px solid var(--gray-300);
  background: var(--white);
  color: var(--gray-500);
}
.submit-btn {
  border: 1px solid var(--black);
  background: var(--black);
  color: var(--white);
}
.submit-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}
.completion-notice {
  position: fixed;
  right: 20px;
  bottom: 20px;
  z-index: 5;
  display: flex;
  align-items: center;
  gap: 10px;
  max-width: 310px;
  padding: 15px;
  border: 1px solid var(--black);
  border-radius: 10px;
  background: var(--white);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
  font-size: 11px;
}
.completion-notice > i {
  font-size: 20px;
}
.completion-notice span {
  display: grid;
  gap: 3px;
}
.completion-notice small {
  color: var(--gray-500);
}
.completion-notice button {
  margin-left: auto;
  padding: 8px;
  border: 1px solid var(--black);
  border-radius: 6px;
  background: var(--black);
  color: var(--white);
  font: inherit;
  font-size: 9px;
  font-weight: 800;
  cursor: pointer;
}
@keyframes cable-cut { 0% { clip-path: inset(0 0 0 0); } 100% { clip-path: inset(0 46% 0 0); } }
@keyframes tool-nudge { 50% { transform: rotate(-10deg); } }
@keyframes connector-seat { 50% { transform: translateX(10px); } }
@keyframes pin-light { 50% { transform: scaleY(1.35); } }
@media (max-width: 760px) {
  .assessment-header {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
    padding: 13px 15px;
  }
  .progress-info {
    width: 100%;
  }
  .assessment-container {
    width: calc(100% - 20px);
    margin-top: 13px;
  }
  .bench {
    grid-template-columns: 1fr;
  }
  .procedure-panel {
    order: 2;
  }
  .work-surface {
    order: 1;
  }
  .scenario-card {
    padding: 16px;
  }
  .scenario-meta {
    flex-wrap: wrap;
  }
  .pin-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 6px;
  }
  .stage-content {
    min-height: 330px;
  }
  .assessment-actions {
    flex-direction: column-reverse;
  }
  .reset-btn,
  .submit-btn {
    width: 100%;
  }
  .completion-notice {
    right: 10px;
    bottom: 10px;
    left: 10px;
    max-width: none;
  }
  .completion-notice button {
    white-space: nowrap;
  }
}
@media (prefers-reduced-motion: reduce) {
  .progress-fill {
    transition: none;
  }
}
</style>
