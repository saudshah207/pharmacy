function setUpChangeSidebarToggleIcon(toggle) {
    const toggleIcon = toggle.querySelector("img"), 
    sidebar = document.querySelector("#sidebar");

    document.addEventListener("click", changeToggleIcon);

    function changeToggleIcon(event) {
        console.log(event.target);
        console.log("hi");
        console.log(!toggleIcon.contains(event.target) && sidebar.contains(event.target));

        if (!toggleIcon.contains(event.target) && sidebar.contains(event.target)) return;

        const toggleIconSource = toggleIcon.src, 
            menuIconName = "menu.svg", 
            closeIconName = "close.svg";

        if (toggleIconSource.endsWith(menuIconName)) {
            toggleIcon.src = toggleIconSource.substring(0, toggleIconSource.indexOf(menuIconName)) + closeIconName;
        } else {
            toggleIcon.src = toggleIconSource.substring(0, toggleIconSource.indexOf(closeIconName)) + menuIconName;
        }
    }
}

setUpChangeSidebarToggleIcon(document.querySelector(".sidebar-toggle"));