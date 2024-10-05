// dashboard.js

function fetchDashboardData() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_dashboard_data.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);

            // Update the dashboard with live data
            document.getElementById('total-clicks').innerText = data.total_clicks;
            document.getElementById('total-balance').innerText = data.total_balance;
            document.getElementById('total-users').innerText = data.total_users;
        }
    };
    xhr.send();
}

// Call the function every 5 seconds for live updates
setInterval(fetchDashboardData, 5000);

// Initial call to load data immediately on page load
fetchDashboardData();
