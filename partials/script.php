<script>
document.getElementById("hotelSearchForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const checkInDate = new Date(document.getElementById("checkInDate").value);
    const checkOutDate = new Date(document.getElementById("checkOutDate").value);
    const today = new Date();

    if (checkInDate < today || checkOutDate <= checkInDate) {
        document.getElementById("invalidDateMessage").classList.remove("hidden");
    } else {
        document.getElementById("invalidDateMessage").classList.add("hidden");
        alert("Searching for hotels...");
    }
});


// List of cities for autocomplete
const cities = [
    "Karachi", "Lahore", "Islamabad", "Peshawar", "Rawalpindi", 
    "Multan", "Quetta", "Faisalabad", "Sialkot", "Gujranwala"
];

// Function to handle autocomplete for destination input
function autocomplete(input) {
    let currentFocus;
    input.addEventListener("input", function(e) {
        let list, item, val = this.value;
        closeAllLists();
        if (!val) return false;
        currentFocus = -1;
        list = document.createElement("DIV");
        list.setAttribute("id", this.id + "-autocomplete-list");
        list.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(list);
        
        for (let i = 0; i < cities.length; i++) {
            if (cities[i].toLowerCase().includes(val.toLowerCase())) {
                item = document.createElement("DIV");
                item.innerHTML = cities[i];
                item.addEventListener("click", function() {
                    input.value = this.innerText;
                    closeAllLists();
                });
                list.appendChild(item);
            }
        }
    });

    function closeAllLists(elmnt) {
        const items = document.querySelectorAll(".autocomplete-items");
        items.forEach(item => {
            if (elmnt != item && elmnt != input) {
                item.parentNode.removeChild(item);
            }
        });
    }

    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}

// Initialize autocomplete for the destination input
autocomplete(document.getElementById("destination"));

// Form validation and submission
document.getElementById("hotelSearchForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const destination = document.getElementById('destination').value;
    const checkInDate = document.getElementById('checkInDate').value;
    const checkOutDate = document.getElementById('checkOutDate').value;

    if (!destination || !checkInDate || !checkOutDate) {
        alert("Please fill in all the fields.");
    } else if (new Date(checkInDate) < new Date() || new Date(checkOutDate) <= new Date()) {
        document.getElementById("invalidDateMessage").classList.remove("hidden");
    } else {
        document.getElementById("invalidDateMessage").classList.add("hidden");
        // Proceed with your search logic (e.g., redirect to a results page)
        console.log("Searching for hotels in", destination, "from", checkInDate, "to", checkOutDate);
        // Example redirect:
        // window.location.href = `hotel_results.html?destination=${destination}&checkInDate=${checkInDate}&checkOutDate=${checkOutDate}`;
    }
});
</script>