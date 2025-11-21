let qIndex = 0;

function addQuestion() {
    const container = document.getElementById('questions-container');

    const html = `
        <div class="question-block">
            <h4>Question ${qIndex + 1}</h4>
            
            <label>Question Text</label>
            <input type="text" name="questions[${qIndex}][text]" required>

            <label>Choices</label>
            <input type="text" name="questions[${qIndex}][options][]" placeholder="Option 1" required>
            <input type="text" name="questions[${qIndex}][options][]" placeholder="Option 2" required>
            <input type="text" name="questions[${qIndex}][options][]" placeholder="Option 3" required>
            <input type="text" name="questions[${qIndex}][options][]" placeholder="Option 4" required>

            <label>Correct Answer</label>
            <select name="questions[${qIndex}][correct_option]" required>
                <option value="0">Option 1</option>
                <option value="1">Option 2</option>
                <option value="2">Option 3</option>
                <option value="3">Option 4</option>
            </select>
        </div>
        <hr>
    `;

    container.insertAdjacentHTML('beforeend', html);
    qIndex++;
}

// 10 default questions 
for (let i = 0; i < 10; i++) {
    addQuestion()
};
