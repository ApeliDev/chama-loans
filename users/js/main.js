function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('.header');
    const mainContent = document.querySelector('.juliet-akoth-container');
    if (sidebar && header && mainContent) {
        sidebar.classList.toggle('collapsed');
        header.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
    } else {
        console.error("One or more elements for sidebar, header, or main content were not found.");
    }
}

// Timeout function
let timeout;

function resetTimeout() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        window.location.href = "../login.php?timeout=true";
    }, timeoutDuration); 
}

window.onload = resetTimeout;
document.onmousemove = resetTimeout;
document.onkeypress = resetTimeout;
document.onclick = resetTimeout;
document.onscroll = resetTimeout;
