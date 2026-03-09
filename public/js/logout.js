async function logout() {
    const token = localStorage.getItem('authToken');

    const res = await fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });

    if (res.ok) {
        localStorage.removeItem('authToken');
        window.location.href = '/login';
    } else {
        console.error('Logout failed');
    }
}
