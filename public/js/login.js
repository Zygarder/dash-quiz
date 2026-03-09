// Get CSRF token from meta
let token = document.getElementsByTagName('meta').content

// Login function
async function loginRequest(email, password) {
    const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    if (!response.ok) throw new Error(data.message || 'Login failed');

    return data;
}

// Leaderboard function
async function leaderBoard(token) {
    const url = '/api/dashboard';
    const currentUserId = Number(document.body.dataset.userId);

    const response = await fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });

    if (!response.ok) throw new Error(`Status: ${response.status}`);

    const data = await response.json();
    const tableBody = document.querySelector('.leaderboard-body');
    let rows = '';
    let foundYou = false;

    data.leaders.forEach((leader, index) => {
        const isYou = leader.user_id === currentUserId;
        if (isYou) foundYou = true;

        let medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : (index + 1) + 'th';

        rows += `
        <tr class="${isYou ? 'you' : ''}">
            <td><strong>${medal}</strong></td>
            <td>
                <div class="dash-user">
                    <img src="${leader.profile_photo ?? '/images/default-avatar.png'}" class="dash-avatar" alt="DP">
                    <div class="dash-name">
                        ${leader.name}${isYou ? "<span class='you-tag'>(You)</span>" : ""}
                    </div>
                </div>
            </td>
            <td>${leader.quiz_title}</td>
            <td><strong>${leader.score}</strong></td>
        </tr>`;
    });

    if (!foundYou) {
        rows += `<tr><td colspan="4" class="no-score">You have no quiz scores yet.</td></tr>`;
    }

    tableBody.innerHTML = rows;
}

// Handle form submission
const loginForm = document.querySelector('.login-box');

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.querySelector('#email').value;
    const password = document.querySelector('#pass').value;

    try {
        const data = await loginRequest(email, password);

        console.log('Login successful!', data);

        // Redirect frontend
        window.location.href = data.redirect;
    } catch (error) {
        console.error('Login failed:', error.message);
    }
});
