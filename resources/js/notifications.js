class NotificationManager {
    constructor() {
        this.btn = document.getElementById('notif-btn');
        this.countEl = document.getElementById('notif-count');
        this.listEl = document.querySelector('.dropdown-menu .list-group');
        this.dropdownInstance = null;
        
        if (this.btn) {
            this.dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(this.btn);
            this.init();
        }
    }

    init() {
        // Load initial notifications
        this.loadNotifications();
        
        // Poll for new notifications every 30 seconds
        setInterval(() => this.updateUnreadCount(), 30000);
        
        // Handle notification click
        this.listEl.addEventListener('click', e => {
            const item = e.target.closest('.list-group-item');
            if (!item) return;
            
            e.preventDefault();
            const id = item.dataset.id;
            this.markAsRead(id);
        });
    }

    async loadNotifications() {
        try {
            const response = await fetch('/notifications');
            const notifications = await response.json();
            
            this.renderNotifications(notifications);
            this.updateUnreadCount();
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    renderNotifications(notifications) {
        this.listEl.innerHTML = notifications.map(notif => `
            <a href="${notif.link || '#'}" 
               class="list-group-item list-group-item-action ${!notif.is_read ? 'active' : ''}"
               data-id="${notif.id}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">${notif.title}</h5>
                    <small>${this.timeAgo(notif.created_at)}</small>
                </div>
                <p class="mb-1">${notif.message}</p>
            </a>
        `).join('');
    }

    async markAsRead(id) {
        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            this.loadNotifications();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/notifications/unread-count');
            const data = await response.json();
            
            if (data.count <= 0) {
                this.countEl.style.display = 'none';
            } else {
                this.countEl.style.display = '';
                this.countEl.textContent = data.count;
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }

    timeAgo(datetime) {
        const date = new Date(datetime);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " years ago";
        
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months ago";
        
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";
        
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours ago";
        
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes ago";
        
        return Math.floor(seconds) + " seconds ago";
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    new NotificationManager();
});
