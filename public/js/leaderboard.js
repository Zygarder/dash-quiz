
async function leaderBoard() {
    const url = 'user/leaderboard-data';
    const currentUserId = Number(document.body.dataset.userId);

    try {
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin' // send Laravel session cookie
        });


        if (!response.ok) throw new Error(`Response status: ${response.status}`);

        const data = await response.json();
        const tableBody = document.querySelector(".leaderboard-body");
        tableBody.innerHTML = ''; // clear old rows

        let foundYou = false;

        console.log(data.leaders)

        data.leaders.forEach(function (leader, index) {
            const isYou = leader.user_id === currentUserId;
            if (isYou) foundYou = true;

            let medal = '';
            if (index === 0) medal = '🥇';
            else if (index === 1) medal = '🥈';
            else if (index === 2) medal = '🥉';

            tableBody.innerHTML += `
            <tr class="${isYou ? 'you' : ''}">
              <td><strong>${medal ? medal : '#' + (index + 1)}</strong></td>
              <td>
                <div class="dash-user">
                  <img src="${leader.profile_photo}" class="dash-avatar" alt="DP">
                  <div class="dash-name">
                    ${leader.name}
                    ${isYou ? "<span class='you-tag'>(You)</span>" : ""}
                  </div>
                </div>
              </td>
              <td>${leader.quiz_title}</td>
              <td><strong>${leader.score}</strong></td>
            </tr>
          `;
        });

        if (!foundYou) {
            tableBody.innerHTML += `
        <tr>
          <td colspan="4" class="no-score">You have no quiz scores yet.</td>
        </tr>
      `;
        }

    } catch (error) {
        console.error(error.message);
    }
}
leaderBoard()

setInterval(leaderBoard, 6000);