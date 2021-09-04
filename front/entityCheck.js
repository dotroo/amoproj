function entityCheck(option) {
    if (option.value === "custom") {
        document.getElementById("customInput").style.display = "inline";
        document.getElementById("entitySelected").style.display = "none";
    } else {
        document.getElementById("customInput").style.display = "none";
        document.getElementById("entitySelected").style.display = "inline";
    }
}