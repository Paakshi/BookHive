<!-- Floating Book Bot Button -->
<a href="book_bot.php" class="book-bot-btn">📚</a>

<style>
/* Floating Book Bot button */
.book-bot-btn {
    position: fixed; /* Fixes the position */
    bottom: 20px; /* Distance from the bottom of the page */
    right: 20px; /* Distance from the right of the page */
    background-color: #4CAF50; /* Button background color */
    color: white; /* Text color */
    padding: 15px 20px; /* Padding inside the button */
    border-radius: 50%; /* Circular shape */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Shadow effect */
    font-weight: bold; /* Make the text bold */
    cursor: pointer; /* Cursor pointer on hover */
    text-decoration: none; /* Remove underline */
    font-size: 28px; /* Font size for the icon */
    z-index: 999; /* Ensure the button appears above other elements */
    animation: pulse 2s infinite; /* Add pulse animation */
}

.book-bot-btn:hover {
    background-color: #45a049; /* Darker green on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
    transition: transform 0.3s ease; /* Smooth transition */
}

/* Pulse animation */
@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
    }
}
</style>
