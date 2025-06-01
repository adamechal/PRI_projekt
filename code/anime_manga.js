const searchInput = document.getElementById("search");

searchInput.addEventListener("input", function () {

    const query = this.value.toLowerCase();
    document.querySelectorAll(".title-card").forEach(card => {
        
        const name = card.querySelector("a").textContent.toLowerCase();
        card.style.display = name.includes(query) ? "" : "none";
    });
});
