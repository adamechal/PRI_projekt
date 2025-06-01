
const searchInput = document.getElementById("search");
const typeFilter = document.getElementById("typeFilter");

function filterTitles() {
    const query = searchInput.value.toLowerCase();
    const type = typeFilter.value.toLowerCase();

    document.querySelectorAll(".title-card").forEach(card => {
        const name = card.querySelector("a").textContent.toLowerCase();
        const cardType = card.dataset.type.toLowerCase();
        const matchesSearch = name.includes(query);
        const matchesType = !type || cardType === type;

        card.style.display = (matchesSearch && matchesType) ? "" : "none";
    });
}

searchInput.addEventListener("input", filterTitles);
typeFilter.addEventListener("change", filterTitles);
