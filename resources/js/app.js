import './bootstrap';
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'local', // or any value if you're not using Pusher.com
    wsHost: window.location.hostname,
    wsPort: 8080, // 👈 your current Reverb port
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});


