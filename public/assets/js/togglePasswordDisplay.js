function togglePasswordDisplay(passwordInput) {
    displayToggleCSSSelector = ".password-display-toggle";
    document.addEventListener("click", toggle);

    function toggle(event) {
        const displayToggleButton = event.target.closest(displayToggleCSSSelector);

        if (!displayToggleButton) return;

        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        
        const displayToggleIcon = displayToggleButton.querySelector("img"),
            displayToggleIconSource = displayToggleIcon.src;

        displayToggleIcon.src = displayToggleIconSource.endsWith("visibility.svg") ? 
            displayToggleIconSource.substring(0, displayToggleIconSource.indexOf(".svg")) + "-off.svg" : 
            displayToggleIconSource.substring(0, displayToggleIconSource.indexOf("-off.svg")) + ".svg";  
    }
}

togglePasswordDisplay(document.querySelector("[type='password']"));