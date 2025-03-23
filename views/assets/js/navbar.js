
let notificationCount = 1;
const badge = document.getElementById('notificationBadge');

function updateNotificationCount(count) {
    notificationCount = count;
    badge.textContent = notificationCount;
    badge.style.display = notificationCount > 0 ? 'block' : 'none';
}

// Example usage: update count after 2 seconds
setTimeout(() => {
    updateNotificationCount(2);
}, 2000);