// Theme toggle functionality
const themeToggle = document.getElementById('themeToggle');
const body = document.body;

function applyTheme(theme) {
    if (theme === 'dark') {
        body.setAttribute('data-theme', 'dark');
        // themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
        localStorage.setItem('theme', 'dark');
    } else {
        body.removeAttribute('data-theme');
        // themeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
        localStorage.setItem('theme', 'light');
    }
}

// Check for saved theme preference or respect OS preference
const savedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

// Initialize theme
if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    applyTheme('dark');
} else {
    applyTheme('light');
}

// Toggle theme on click
themeToggle.addEventListener('click', () => {
    if (body.getAttribute('data-theme') === 'dark') {
        applyTheme('light');
    } else {
        applyTheme('dark');
    }
});

// Conversation item click handling
document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', function() {
        const conversationId = this.getAttribute('data-conversation-id');
        // Make sure we're not clicking on the "Messages" header item
        if (conversationId && conversationId !== "null") {
            window.location.href = '/conversations/' + conversationId;
        }
    });
});

// Get the current conversation ID
const currentConversationId = '{{ $conversation->id }}';
const conversationsList = document.getElementById('conversations-list');

// Function to update conversation sidebar
function updateConversationSidebar(conversationId, messageBody, isCurrentUser) {
    const conversationItem = conversationsList.querySelector(`[data-conversation-id="${conversationId}"]`);

    if (conversationItem) {
        const previewElement = conversationItem.querySelector('.conversation-preview');
        const timeElement = conversationItem.querySelector('.conversation-time');

        if (previewElement) {
            previewElement.textContent = (isCurrentUser ? 'You: ' : '') + messageBody;
        }

        if (timeElement) {
            timeElement.textContent = 'Just now';
        }

        // Update the last updated timestamp
        conversationItem.setAttribute('data-last-updated', Math.floor(Date.now() / 1000));

        // Move the updated conversation to the top (after the "Messages" item)
        const firstItem = conversationsList.querySelector('.conversation-item');
        if (firstItem) {
            conversationsList.insertBefore(conversationItem, firstItem.nextSibling);
        }

        // Add notification badge if it's not the active conversation
        if (conversationId != currentConversationId) {
            let badge = conversationItem.querySelector('.notification-badge');
            if (!badge) {
                badge = document.createElement('div');
                badge.className = 'notification-badge';
                badge.textContent = '1';
                conversationItem.appendChild(badge);
            } else {
                badge.textContent = parseInt(badge.textContent) + 1;
            }
        }
    }
}

// Audio recording functionality
let mediaRecorder;
let audioChunks = [];
let audioBlob = null;

// Check if browser supports audio recording
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Event listener for audio recording
    document.getElementById('record-audio-button').addEventListener('click', toggleAudioRecording);
    document.getElementById('cancel-audio').addEventListener('click', cancelAudioRecording);
    // Add event listener for the send audio button
    document.getElementById('send-audio-button').addEventListener('click', sendAudioMessage);
} else {
    // Hide record button if not supported
    document.getElementById('record-audio-button').style.display = 'none';
}

async function toggleAudioRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        // Stop recording
        mediaRecorder.stop();
        document.getElementById('record-audio-button').classList.remove('recording');
        document.getElementById('recording-status').style.display = 'none';
    } else {
        // Start recording
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = (event) => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioUrl = URL.createObjectURL(audioBlob);

                // Show audio preview
                const audioPreview = document.getElementById('audio-preview');
                audioPreview.src = audioUrl;
                document.getElementById('audio-controls').style.display = 'flex';

                // Create a file from the blob
                const audioFile = new File([audioBlob], 'audio-message.webm', { type: 'audio/webm' });

                // Create a DataTransfer object to hold the file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(audioFile);

                // Assign the file to the file input
                document.getElementById('audio-input').files = dataTransfer.files;

                // Hide text input and send button, show audio controls with send button
                document.querySelector('.message-input').style.display = 'none';
                document.querySelector('.message-send-btn').style.display = 'none';
                document.querySelector('.mic-btn').style.display = 'none';
            };

            mediaRecorder.start();
            document.getElementById('record-audio-button').classList.add('recording');
            document.getElementById('recording-status').style.display = 'block';

        } catch (error) {
            console.error('Error accessing microphone:', error);
            alert('Could not access your microphone. Please check permissions.');
        }
    }
}

function cancelAudioRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }

    // Reset UI
    document.getElementById('audio-controls').style.display = 'none';
    document.querySelector('.message-input').style.display = '';
    document.querySelector('.message-send-btn').style.display = '';
    document.querySelector('.mic-btn').style.display = '';
    document.getElementById('record-audio-button').classList.remove('recording');
    document.getElementById('recording-status').style.display = 'none';

    // Clear audio input
    document.getElementById('audio-input').value = '';
    audioBlob = null;
}

// Function to handle sending audio messages
function sendAudioMessage() {
    const form = document.getElementById('message-form');
    const formData = new FormData(form);

    // Create a temporary "sending" message for immediate feedback
    const messagesContainer = document.getElementById('messages-container');
    const now = new Date();
    const messageTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    const tempMessageHtml = `
        <div class="message-wrapper message-wrapper-sent sending-message">
            <div class="message-bubble">
                <div class="message message-sent audio-message">
                    <div class="custom-audio-player">
                        <button class="play-btn"><i class="bi bi-play-fill"></i></button>
                        <div class="waveform">
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="audio-duration">0:00</div>
                    </div>
                </div>
                <div class="message-timestamp message-timestamp-sent">
                    ${messageTime}
                </div>
            </div>
        </div>
    `;

    messagesContainer.insertAdjacentHTML('beforeend', tempMessageHtml);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Submit the form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const sendingMessage = messagesContainer.querySelector('.sending-message');
        if (sendingMessage) {
            sendingMessage.remove(); // Remove the temporary message
        }

        // Add the final message
        const messageHtml = `
            <div class="message-wrapper message-wrapper-sent">
                <div class="message-bubble">
                    <div class="message message-sent audio-message">
                        <div class="custom-audio-player" data-src="/messages/${data.message.id}/audio">
                            <button class="play-btn"><i class="bi bi-play-fill"></i></button>
                            <div class="waveform">
                                <span></span><span></span><span></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <div class="audio-duration">0:00</div>
                        </div>
                    </div>
                    <div class="message-timestamp message-timestamp-sent">
                        ${messageTime}
                    </div>
                </div>
            </div>
        `;

        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Update the sidebar with the latest message
        updateConversationSidebar('{{ $conversation->id }}', 'Audio message', true);

        // Reset form and UI
        form.reset();
        cancelAudioRecording();
        attachAudioPlayerListeners();
    })
    .catch(error => {
        console.error('Error:', error);
        const sendingMessage = messagesContainer.querySelector('.sending-message');
        if (sendingMessage) {
            sendingMessage.remove(); // Remove the temporary message
        }
        alert('Error sending audio message. Please try again.');
    });
}

function isEmojiOnly(str) {
    // This regex matches a broad range of emoji characters and sequences
    // It's the client-side equivalent of the PHP regex you updated.
    const emojiRegex = /^(\p{Emoji_Modifier_Base}\p{Emoji_Modifier}?|\p{Emoji_Presentation}|\p{Emoji}\uFE0F?\u200D?)+$/u;
    return emojiRegex.test(str.trim());
}

// Update form submission to handle audio
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const inputField = form.querySelector('.message-input');
    const messageBody = inputField.value;

    const audioFile = document.getElementById('audio-input').files[0];
    const imageFile = document.getElementById('image-upload').files[0];
    const videoFile = document.getElementById('video-upload').files[0];

    if (!messageBody.trim() && !audioFile && !imageFile && !videoFile) {
        return;
    }

    // Determine the type of message to send and call the appropriate function
    if (audioFile) {
        sendAudioMessage();
        return;
    } else if (imageFile || videoFile) {
        uploadFile(imageFile || videoFile, imageFile ? 'image' : 'video');
        return;
    }

    // Handle text message submission
    const messagesContainer = document.getElementById('messages-container');
    const now = new Date();
    const messageTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    // Check if the message is emoji-only
    const emojiOnlyClass = isEmojiOnly(messageBody) ? 'emoji-only' : '';
    const messageClass = `message-sent ${emojiOnlyClass}`;

    let tempMessageHtml = `
        <div class="message-wrapper message-wrapper-sent sending-message">
            <div class="message-bubble">
                <div class="message ${messageClass}">
                    <div class="message-text">${messageBody}</div>
                </div>
                <div class="message-timestamp message-timestamp-sent">
                    ${messageTime}
                </div>
            </div>
        </div>
    `;

    messagesContainer.insertAdjacentHTML('beforeend', tempMessageHtml);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const sendingMessage = messagesContainer.querySelector('.sending-message');
            if (sendingMessage) {
                sendingMessage.remove(); // Remove the temporary message
            }

            // Update the chat area with the final message
           let finalMessageHtml = `
        <div class="message-wrapper message-wrapper-sent">
            <div class="message-bubble">
                <div class="message ${messageClass}">
                    <div class="message-text">${messageBody}</div>
                </div>
                <div class="message-timestamp message-timestamp-sent">
                    ${messageTime}
                </div>
            </div>
        </div>
    `;

            messagesContainer.insertAdjacentHTML('beforeend', finalMessageHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Update the sidebar with the latest message
            updateConversationSidebar('{{ $conversation->id }}', messageBody, true);

            form.reset();
            cancelAudioRecording(); // Reset audio UI
            attachAudioPlayerListeners(); // Re-attach listeners for new messages
        })
        .catch(error => {
            console.error('Error:', error);
            const sendingMessage = messagesContainer.querySelector('.sending-message');
            if (sendingMessage) {
                sendingMessage.remove(); // Remove the temporary message
            }
            // Fallback: submit the form normally if AJAX fails
            form.submit();
        });
});

// Auto-scroll to bottom of messages
window.addEventListener('load', function() {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    attachAudioPlayerListeners(); // Attach listeners on page load
});

// Function to format message timestamps
function formatMessageTime(timestamp) {
    const messageTime = new Date(timestamp);
    const now = new Date();
    const diffInMinutes = Math.floor((now - messageTime) / (1000 * 60));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
    if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`;

    return messageTime.toLocaleDateString();
}

// --- Custom Audio Player Functionality ---

// A single, reusable AudioContext for the whole page.
let audioContext;
// Track which player is currently active
let playingPlayer = null;

function attachAudioPlayerListeners() {
    document.querySelectorAll('.custom-audio-player').forEach(player => {
        const playBtn = player.querySelector('.play-btn');
        const durationDisplay = player.querySelector('.audio-duration');
        const waveform = player.querySelector('.waveform');
        const audio = new Audio(player.dataset.src);

        // This prevents re-attaching listeners on existing players
        if (player.hasAttribute('data-listeners-attached')) {
            return;
        }
        player.setAttribute('data-listeners-attached', 'true');

        // Store references to the nodes on the player element itself
        // This is the key fix for the InvalidStateError
        player.analyser = null;
        player.source = null;

        // Set initial duration only when the audio metadata is loaded
        audio.addEventListener('loadedmetadata', () => {
            durationDisplay.textContent = formatTime(audio.duration);
        });

        // Toggle play/pause
        playBtn.addEventListener('click', () => {
            // First time a user interacts, create the AudioContext
            if (!audioContext) {
                audioContext = new(window.AudioContext || window.webkitAudioContext)();
            }

            if (audio.paused) {
                // Pause any currently playing audio
                document.querySelectorAll('audio').forEach(otherAudio => {
                    if (otherAudio !== audio) {
                        otherAudio.pause();
                    }
                });
                audio.play();
                playingPlayer = player;
            } else {
                audio.pause();
            }
        });

        audio.addEventListener('play', () => {
            playBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
            startWaveformAnimation(player, audio, audioContext);
        });

        audio.addEventListener('pause', () => {
            playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            stopWaveformAnimation(player);
        });

        audio.addEventListener('ended', () => {
            audio.currentTime = 0;
            playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            stopWaveformAnimation(player);
            // On end, display the full duration again
            durationDisplay.textContent = formatTime(audio.duration);
            playingPlayer = null;
        });

        // Fix: Display elapsed time, not remaining time
        audio.addEventListener('timeupdate', () => {
            durationDisplay.textContent = formatTime(audio.currentTime);
        });
    });
}

function formatTime(seconds) {
    if (isNaN(seconds) || !isFinite(seconds)) {
        return '0:00';
    }
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
}

// Global animation frame variable to prevent conflicts
let currentAnimationFrame;

function startWaveformAnimation(player, audio, audioContext) {
    const waveform = player.querySelector('.waveform');

    // If an animation is already running, stop it
    if (currentAnimationFrame) {
        cancelAnimationFrame(currentAnimationFrame);
    }

    // Check if the source and analyser already exist for this player
    if (!player.source) {
        player.analyser = audioContext.createAnalyser();
        player.analyser.fftSize = 256;
        player.source = audioContext.createMediaElementSource(audio);
        player.source.connect(player.analyser);
        player.analyser.connect(audioContext.destination);
    }

    const dataArray = new Uint8Array(player.analyser.frequencyBinCount);
    const bars = waveform.querySelectorAll('span');

    function animate() {
        player.analyser.getByteFrequencyData(dataArray);
        for (let i = 0; i < bars.length; i++) {
            const bar = bars[i];
            const value = dataArray[i * 2 + 10];
            const normalizedValue = value / 256;
            const height = normalizedValue * 100;
            bar.style.height = `${Math.max(5, height)}%`;
        }
        currentAnimationFrame = requestAnimationFrame(animate);
    }
    animate();
}

function stopWaveformAnimation(player) {
    const waveform = player.querySelector('.waveform');
    if (currentAnimationFrame) {
        cancelAnimationFrame(currentAnimationFrame);
        currentAnimationFrame = null;
    }
    // Reset all bars to default height
    waveform.querySelectorAll('span').forEach(span => {
        span.style.height = '15%';
    });
}
document.getElementById('open-emoji-picker').addEventListener('click', function(e) {
    e.stopPropagation(); // Prevent immediate closing when clicking the button

    // 1. Create or show emoji picker UI
    const emojiPicker = document.getElementById('emoji-picker');
    const inputContainer = document.querySelector('.message-input-container');

    if (emojiPicker) {
        // Toggle visibility if it already exists
        const isVisible = emojiPicker.style.display !== 'none';
        emojiPicker.style.display = isVisible ? 'none' : 'block';

        // Position it near the emoji button if showing
        if (!isVisible) {
            positionEmojiPicker(emojiPicker, inputContainer);
        }
    } else {
        // Create emoji picker if it doesn't exist
        createEmojiPicker(inputContainer);
    }
});

function createEmojiPicker(container) {
    // Create emoji picker container
    const picker = document.createElement('div');
    picker.id = 'emoji-picker';
    picker.className = 'emoji-picker';

    // Add some common emojis (organized by category)
 const emojiCategories = {
    // Corrected to a full Bootstrap icon tag
    '<i class="bi bi-emoji-smile"></i>': ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳'],
    '<i class="bi bi-bug"></i>': ['🐵', '🐒', '🦍', '🐶', '🐕', '🐩', '🐺', '🦊', '🐱', '🐈', '🦁', '🐯', '🐅', '🐆', '🐴', '🐎', '🦄', '🦓', '🦌', '🐮', '🐂', '🐃', '🐄', '🐷', '🐖', '🐗', '🐽', '🐏', '🐑', '🐐', '🐪', '🐫', '🦙', '🦒', '🐘', '🦏', '🦛', '🐭', '🐁', '🐀', '🐹', '🐰', '🐇', '🐿', '🦔', '🦇', '🐻', '🐨', '🐼', '🦘', '🦡', '🐾'],
    '<i class="bi bi-apple"></i>': ['🍏', '🍎', '🍐', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🍈', '🍒', '🍑', '🍍', '🥭', '🥥', '🥝', '🍅', '🍆', '🥑', '🥦', '🥬', '🥒', '🌶', '🌽', '🥕', '🧄', '🧅', '🥔', '🍠', '🥐', '🥯', '🍞', '🥖', '🥨', '🧀', '🥚', '🍳', '🥞', '🧇', '🧈', '🍖', '🍗', '🥩', '🥓', '🍔', '🍟', '🍕', '🌭', '🥪', '🌮', '🌯', '🥙', '🧆', '🥚', '🍳', '🥘', '🍲', '🥣', '🥗', '🍿', '🧈', '🧂', '🥫'],
    '<i class="bi bi-buildings"></i>': ['🏠', '🏡', '🏢', '🏣', '🏤', '🏥', '🏦', '🏨', '🏩', '🏪', '🏫', '🏬', '🏭', '🏯', '🏰', '💒', '🗼', '🗽', '⛪', '🕌', '🛕', '🕍', '⛩', '🕋', '⛲', '⛺', '🌁', '🌃', '🏙', '🌄', '🌅', '🌆', '🌇', '🌉', '🎠', '🎡', '🎢', '💈', '🎪', '🚂', '🚃', '🚄', '🚅', '🚆', '🚇', '🚈', '🚉', '🚊', '🚝', '🚞', '🚋', '🚌', '🚍', '🚎', '🚐', '🚑', '🚒', '🚓', '🚔', '🚕', '🚖', '🚗', '🚘', '🚙', '🚚', '🚛', '🚜', '🏎', '🏍', '🛵', '🚲', '🛴', '🛹', '🚏', '🛣', '🛤', '⛽', '🚨', '🚥', '🚦', '🛑', '🚧', '⚓', '⛵', '🛶', '🚤', '🛳', '⛴', '🛥', '🚢', '✈️', '🛩', '🛫', '🛬', '🪂', '💺', '🚁', '🚟', '🚠', '🚡', '🛰', '🚀', '🛸', '🎆', '🎇', '🎑', '💎'],
    '<i class="bi bi-controller"></i>': ['⚽', '🏀', '🏈', '⚾', '🥎', '🎾', '🏐', '🏉', '🥏', '🎱', '🪀', '🏓', '🏸', '🏒', '🏑', '🥍', '🏏', '🪃', '🥅', '⛳', '🪁', '🏹', '🎣', '🤿', '🥊', '🥋', '🎽', '🛹', '🛼', '🛷', '⛸', '🥌', '🎿', '⛷', '🏂', '🪂', '🏋️', '🤼', '🤸', '🤺', '⛹️', '🤾', '🏌️', '🏇', '🧘', '🏄', '🏊', '🤽', '🚣', '🧗', '🚴', '🚵'],
    '<i class="bi bi-lightbulb"></i>': ['⌚', '📱', '📲', '💻', '⌨️', '🖥', '🖨', '🖱', '🖲', '🕹', '🗜', '💽', '💾', '💿', '📀', '📼', '📷', '📸', '📹', '🎥', '📽', '🎞', '📞', '☎️', '📟', '📠', '📺', '📻', '🎙', '🎚', '🎛', '🧭', '⏱', '⏲', '⏰', '🕰', '⌛', '⏳', '📡', '🔋', '🔌', '💡', '🔦', '🕯', '🧯', '🕰', '💵', '💴', '💶', '💷', '💰', '💳', '💎', '⚖️', '🧰', '🔧', '🔨', '⚒', '🛠', '⛏', '🔩', '⚙️', '🧱', '⛓', '🧲', '🔫', '💣', '🧨', '🪓', '🔪', '🗡', '⚔️', '🛡', '🚬', '⚰️', '⚱️', '🏺', '🔮', '📿', '🧿', '💈', '⚗️', '🔭', '🔬', '🕳', '🩹', '🩺', '💊', '💉', '🩸', '🧬', '🦠', '🧫', '🧪', '🌡', '🧹', '🧺', '🧻', '🚽', '🚰', '🚿', '🛁', '🧼', '🧽', '🧴', '🛎', '🔑', '🗝', '🚪', '🪑', '🛋', '🛏', '🧸', '🖼', '🛍', '🛒', '🎁', '🎈', '🎏', '🎀', '🎊', '🎉', '🎎', '🏮', '🎐', '🧧', '✉️', '📩', '📨', '📧', '💌', '📥', '📤', '📦', '🏷', '📪', '📫', '📬', '📭', '📮', '📯', '📜', '📃', '📄', '📑', '🧾', '📊', '📈', '📉', '🗒', '🗓', '📆', '📅', '🧮', '📇', '🗃', '🗳', '🗄', '📋', '📁', '📂', '🗂', '🗞', '📰', '📓', '📔', '📒', '📕', '📗', '📘', '📙', '🧾', '🔖', '🗝', '📎', '🖇', '📏', '📐', '✂️', '🗃', '🗳', '🗄', '📌', '📍', '📌', '🖍', '🖌', '🖋', '✒️', '🖊', '🖇', '✏️', '📝', '🔍', '🔎', '🔏', '🔐', '🔒', '🔓'],
    '<i class="bi bi-heart"></i>': ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❤️‍🔥', '❤️‍🩹', '❣️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '☮️', '✝️', '☪️', '🕉', '☸️', '✡️', '🔯', '🕎', '☯️', '☦️', '🛐', '⛎', '♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐', '♑', '♒', '♓', '🆔', '⚛️', '🉑', '☢️', '☣️', '📴', '📳', '🈶', '🈚', '🈸', '🈺', '🈷️', '✴️', '🆚', '💮', '🉐', '㊙️', '㊗️', '🈴', '🈵', '🈹', '🈲', '🅰️', '🅱️', '🆎', '🆑', '🅾️', '🆘', '❌', '⭕', '🛑', '⛔', '📛', '🚫', '💯', '💢', '♨️', '🚷', '🚯', '🚳', '🚱', '🔞', '📵', '🚭', '❗', '❕', '❓', '❔', '‼️', '⁉️', '🔅', '🔆', '〽️', '⚠️', '🚸', '🔱', '⚜️', '🔰', '♻️', '✅', '🈯', '💹', '❇️', '✳️', '❎', '🌐', '💠', 'Ⓜ️', '🌀', '💤', '🏧', '🚾', '♿', '🅿️', '🛗', '🈳', '🈂️', '🛂', '🛃', '🛄', '🛅', '🚹', '🚺', '🚼', '🚻', '🚮', '🎦', '📶', '🈁', '🔣', 'ℹ️', '🔤', '🔡', '🔠', '🆖', '🆗', '🆙', '🆒', '🆕', '🆓', '0️⃣', '1️⃣', '2️⃣', '3️⃣', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟', '🔢', '#️⃣', '*️⃣', '⏏️', '▶️', '⏸', '⏯', '⏹', '⏺', '⏭', '⏮', '⏩', '⏪', '⏫', '⏬', '◀️', '🔼', '🔽', '➡️', '⬅️', '⬆️', '⬇️', '↗️', '↘️', '↙️', '↖️', '↕️', '↔️', '↪️', '↩️', '⤴️', '⤵️', '🔀', '🔁', '🔂', '🔄', '🔃', '🎵', '🎶', '➕', '➖', '➗', '♾', '💲', '💱', '™', '©', '®', '〰️', '➰', '➿', '🔚', '🔙', '🔛', '🔝', '🔜', '✔️', '☑️', '🔘', '🔴', '🟠', '🟡', '🟢', '🔵', '🟣', '⚫', '⚪', '🟤', '🔺', '🔻', '🔸', '🔹', '🔶', '🔷', '🔳', '🔲', '▪️', '▫️', '◾', '◽', '◼️', '◻️', '🟥', '🟧', '🟨', '🟩', '🟦', '🟪', '⬛', '⬜', '🟫', '🔈', '🔇', '🔉', '🔊', '🔔', '🔕', '📣', '📢', '👁‍🗨', '💬', '💭', '🗯', '♠️', '♣️', '♥️', '♦️', '🃏', '🎴', '🀄', '🕐', '🕑', '🕒', '🕓', '🕔', '🕕', '🕖', '🕗', '🕘', '🕙', '🕚', '🕛', '🕜', '🕝', '🕞', '🕟', '🕠', '🕡', '🕢', '🕣', '🕤', '🕥', '🕦', '🕧'],
    '<i class="bi bi-flag"></i>': ['🏳️', '🏴', '🏴‍☠️', '🏁', '🚩', '🇦🇨', '🇦🇩', '🇦🇪', '🇦🇫', '🇦🇬', '🇦🇮', '🇦🇱', '🇦🇲', '🇦🇴', '🇦🇶', '🇦🇷', '🇦🇸', '🇦🇹', '🇦🇺', '🇦🇼', '🇦🇽', '🇦🇿', '🇧🇦', '🇧🇧', '🇧🇩', '🇧🇪', '🇧🇫', '🇧🇬', '🇧🇭', '🇧🇮', '🇧🇯', '🇧🇱', '🇧🇲', '🇧🇳', '🇧🇴', '🇧🇶', '🇧🇷', '🇧🇸', '🇧🇹', '🇧🇻', '🇧🇼', '🇧🇾', '🇧🇿', '🇨🇦', '🇨🇨', '🇨🇩', '🇨🇫', '🇨🇬', '🇨🇭', '🇨🇮', '🇨🇰', '🇨🇱', '🇨🇲', '🇨🇳', '🇨🇴', '🇨🇵', '🇨🇷', '🇨🇺', '🇨🇻', '🇨🇼', '🇨🇽', '🇨🇾', '🇨🇿', '🇩🇪', '🇩🇬', '🇩🇯', '🇩🇰', '🇩🇲', '🇩🇴', '🇩🇿', '🇪🇦', '🇪🇨', '🇪🇪', '🇪🇬', '🇪🇭', '🇪🇷', '🇪🇸', '🇪🇹', '🇪🇺', '🇫🇮', '🇫🇯', '🇫🇰', '🇫🇲', '🇫🇴', '🇫🇷', '🇬🇦', '🇬🇧', '🇬🇩', '🇬🇪', '🇬🇫', '🇬🇬', '🇬🇮', '🇬🇱', '🇬🇲', '🇬🇳', '🇬🇵', '🇬🇶', '🇬🇷', '🇬🇸', '🇬🇹', '🇬🇺', '🇬🇼', '🇬🇾', '🇭🇰', '🇭🇲', '🇭🇳', '🇭🇷', '🇭🇹', '🇭🇺', '🇮🇨', '🇮🇩', '🇮🇪', '🇮🇱', '🇮🇲', '🇮🇳', '🇮🇴', '🇮🇶', '🇮🇷', '🇮🇸', '🇮🇹', '🇯🇪', '🇯🇲', '🇯🇴', '🇯🇵', '🇰🇪', '🇰🇬', '🇰🇭', '🇰🇮', '🇰🇲', '🇰🇳', '🇰🇵', '🇰🇷', '🇰🇼', '🇰🇾', '🇰🇿', '🇱🇦', '🇱🇧', '🇱🇨', '🇱🇮', '🇱🇰', '🇱🇷', '🇱🇸', '🇱🇹', '🇱🇺', '🇱🇻', '🇱🇾', '🇲🇦', '🇲🇨', '🇲🇩', '🇲🇪', '🇲🇫', '🇲🇬', '🇲🇭', '🇲🇰', '🇲🇱', '🇲🇲', '🇲🇳', '🇲🇴', '🇲🇵', '🇲🇶', '🇲🇷', '🇲🇸', '🇲🇹', '🇲🇺', '🇲🇻', '🇲🇼', '🇲🇽', '🇲🇾', '🇲🇿', '🇳🇦', '🇳🇨', '🇳🇪', '🇳🇫', '🇳🇬', '🇳🇮', '🇳🇱', '🇳🇴', '🇳🇵', '🇳🇷', '🇳🇺', '🇳🇿', '🇴🇲', '🇵🇦', '🇵🇪', '🇵🇫', '🇵🇬', '🇵🇭', '🇵🇰', '🇵🇱', '🇵🇲', '🇵🇳', '🇵🇷', '🇵🇸', '🇵🇹', '🇵🇼', '🇵🇾', '🇶🇦', '🇷🇪', '🇷🇴', '🇷🇸', '🇷🇺', '🇷🇼', '🇸🇦', '🇸🇧', '🇸🇨', '🇸🇩', '🇸🇪', '🇸🇬', '🇸🇭', '🇸🇮', '🇸🇰', '🇸🇱', '🇸🇲', '🇸🇳', '🇸🇴', '🇸🇷', '🇸🇸', '🇸🇹', '🇸🇻', '🇸🇽', '🇸🇾', '🇸🇿', '🇹🇦', '🇹🇨', '🇹🇩', '🇹🇫', '🇹🇬', '🇹🇭', '🇹🇯', '🇹🇰', '🇹🇱', '🇹🇲', '🇹🇳', '🇹🇴', '🇹🇷', '🇹🇹', '🇹🇻', '🇹🇼', '🇹🇿', '🇺🇦', '🇺🇬', '🇺🇲', '🇺🇳', '🇺🇸', '🇺🇾', '🇺🇿', '🇻🇦', '🇻🇨', '🇻🇪', '🇻🇬', '🇻🇮', '🇻🇳', '🇻🇺', '🇼🇫', '🇼🇸', '🇽🇰', '🇾🇪', '🇾🇹', '🇿🇦', '🇿🇲', '🇿🇼']
};

    // Create category tabs and content
    const tabsContainer = document.createElement('div');
    tabsContainer.className = 'emoji-tabs';
    const contentContainer = document.createElement('div');
    contentContainer.className = 'emoji-content';
    Object.keys(emojiCategories).forEach((category, index) => {
        // Create tab
        const tab = document.createElement('button');
        tab.className = `emoji-tab ${index === 0 ? 'active' : ''}`;
        tab.innerHTML = category; // Render the icon HTML directly
        tab.title = category;
        tab.dataset.category = category;
        tab.addEventListener('click', () => {
            // Activate this tab
            document.querySelectorAll('.emoji-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            // Show this category's emojis
            document.querySelectorAll('.emoji-category').forEach(cat => {
                cat.style.display = 'none';
            });
            document.getElementById(`emoji-category-${category}`).style.display = 'grid';
        });
        tabsContainer.appendChild(tab);
        // Create content for this category
        const categoryDiv = document.createElement('div');
        categoryDiv.id = `emoji-category-${category}`;
        categoryDiv.className = 'emoji-category';
        categoryDiv.style.display = index === 0 ? 'grid' : 'none';
        emojiCategories[category].forEach(emoji => {
            const span = document.createElement('span');
            span.className = 'emoji';
            span.textContent = emoji;
            span.title = emoji;
            span.addEventListener('click', function() {
                // Insert emoji into input field
                const input = document.querySelector('.message-input');
                input.value += emoji;
                input.focus();
                // Close picker after selection
                picker.style.display = 'none';
            });
            categoryDiv.appendChild(span);
        });
        contentContainer.appendChild(categoryDiv);
    });
    picker.appendChild(tabsContainer);
    picker.appendChild(contentContainer);
    // Add search functionality
    const searchContainer = document.createElement('div');
    searchContainer.className = 'emoji-search';
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search emojis...';
    searchInput.className = 'emoji-search-input';

    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();

        document.querySelectorAll('.emoji-category').forEach(category => {
            category.querySelectorAll('.emoji').forEach(emoji => {
                // Simple search - you could enhance this with emoji names
                if (searchTerm === '' || emoji.textContent.includes(searchTerm)) {
                    emoji.style.display = 'inline-block';
                } else {
                    emoji.style.display = 'none';
                }
            });
        });
    });


    searchContainer.appendChild(searchInput);
    picker.insertBefore(searchContainer, tabsContainer);

    document.body.appendChild(picker);

    // Position the picker
    positionEmojiPicker(picker, container);

    // Close picker when clicking outside
    document.addEventListener('click', closeEmojiPicker);
}
function positionEmojiPicker(picker, container) {
    const rect = container.getBoundingClientRect();
    picker.style.bottom = `${window.innerHeight - rect.top + 10}px`;
    picker.style.left = `${rect.left}px`;
    picker.style.display = 'block';
}
function closeEmojiPicker(e) {
    const picker = document.getElementById('emoji-picker');
    const emojiBtn = document.getElementById('open-emoji-picker');
    // Check if the click is outside the picker and not on the button
    if (picker && !picker.contains(e.target) && !emojiBtn.contains(e.target)) {
        picker.style.display = 'none';
        document.removeEventListener('click', closeEmojiPicker);
    }
}

// Function to create and display a new media message
function createMediaMessage(file, fileType) {
    const messagesContainer = document.getElementById('messages-container');
    const now = new Date();
    const messageTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    let mediaHtml = '';
    const fileUrl = URL.createObjectURL(file);
    const mediaTag = fileType === 'image' ? 'img' : 'video';
    const mediaClass = fileType === 'image' ? 'message-image' : 'message-video';

    if (fileType === 'image') {
        mediaHtml = `<img src="${fileUrl}" alt="Image" class="${mediaClass}">`;
    } else if (fileType === 'video') {
        mediaHtml = `
            <video controls class="${mediaClass}">
                <source src="${fileUrl}" type="${file.type}">
                Your browser does not support the video tag.
            </video>
        `;
    }
    const tempMessageHtml = `
        <div class="message-wrapper message-wrapper-sent sending-message">
            <div class="message-bubble">
                <div class="message message-sent media-message">
                    ${mediaHtml}
                </div>
                <div class="message-timestamp message-timestamp-sent">
                    ${messageTime}
                </div>
            </div>
        </div>
    `;

    messagesContainer.insertAdjacentHTML('beforeend', tempMessageHtml);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
// Function to handle the file upload via AJAX
function uploadFile(file, type) {
    // Create a temporary "sending" message
    createMediaMessage(file, type);

    const formData = new FormData();
    formData.append(type, file);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    // Fetch the conversation ID from the URL or a data attribute
    const form = document.getElementById('message-form');
    const conversationId = form.action.split('/').pop();

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Check for success or failure from the server
        if (data.message) {
            console.log('File uploaded and message created:', data.message);

            // Remove the temporary message
            const sendingMessage = document.querySelector('.sending-message');
            if (sendingMessage) {
                sendingMessage.remove();
            }

            // Create and append the final message with the correct path
            const messagesContainer = document.getElementById('messages-container');
            const now = new Date();
            const messageTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            let finalMediaHtml = '';
            const filePath = `{{ asset('storage') }}/${type === 'image' ? data.message.image_path : data.message.video_path}`;

            if (type === 'image') {
                finalMediaHtml = `<img src="${filePath}" alt="Image" class="message-image">`;
            } else if (type === 'video') {
                finalMediaHtml = `
                    <video controls class="message-video">
                        <source src="${filePath}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
            }
            const finalMessageHtml = `
                <div class="message-wrapper message-wrapper-sent">
                    <div class="message-bubble">
                        <div class="message message-sent media-message">
                            ${finalMediaHtml}
                        </div>
                        <div class="message-timestamp message-timestamp-sent">
                            ${messageTime}
                        </div>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', finalMessageHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            // Update the sidebar
            updateConversationSidebar('{{ $conversation->id }}', type, true);

        } else {
            console.error('Upload failed:', data.error);
            alert('File upload failed. Please try again.');
            // Remove temporary message on failure
            const sendingMessage = document.querySelector('.sending-message');
            if (sendingMessage) {
                sendingMessage.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error during upload:', error);
        alert('An error occurred during upload. Please try again.');
        // Remove temporary message on error
        const sendingMessage = document.querySelector('.sending-message');
        if (sendingMessage) {
            sendingMessage.remove();
        }
    });
}
// Listener for image file selection
document.getElementById('image-upload').addEventListener('change', function(event) {
    if (event.target.files.length > 0) {
        const file = event.target.files[0];
        uploadFile(file, 'image');
        // Clear the input field to allow re-uploading the same file
        event.target.value = '';
    }
});
// Listener for video file selection
document.getElementById('video-upload').addEventListener('change', function(event) {
    if (event.target.files.length > 0) {
        const file = event.target.files[0];
        uploadFile(file, 'video');
        // Clear the input field to allow re-uploading the same file
        event.target.value = '';
    }
});

