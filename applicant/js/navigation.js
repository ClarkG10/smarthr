const dropdown = document.getElementById("dropdown");
const contents = document.getElementById("contents");

dropdown.addEventListener("click", (event) => {
  event.stopPropagation();
  contents.style.display = contents.style.display === "flex" ? "none" : "flex";
});

document.addEventListener("click", (event) => {
  if (!dropdown.contains(event.target) && event.target !== contents) {
    contents.style.display = "none";
  }
});

let isSidebarOpen = false; // Track the sidebar's open/close state

function toggleSidebar(event) {
  event.stopPropagation();
  const sidebar = document.getElementById("sidebar");

  // Toggle the sidebar
  if (isSidebarOpen) {
    sidebar.style.transform = "translate(-100%)"; // Move sidebar offscreen
  } else {
    sidebar.style.transform = "translate(0%)"; // Move sidebar onscreen
  }

  isSidebarOpen = !isSidebarOpen;
}

document.addEventListener("click", (event) => {
  const sidebar = document.getElementById("sidebar");

  if (isSidebarOpen && !sidebar.contains(event.target)) {
    sidebar.style.transform = "translate(-100%)";
    isSidebarOpen = false;
  }
});

window.addEventListener("resize", () => {
  const sidebar = document.getElementById("sidebar");

  if (window.innerWidth < 1000) {
    sidebar.style.transform = "translate(-100%)";
    isSidebarOpen = false;
  } else if (!isSidebarOpen) {
    sidebar.style.transform = "translate(0%)";
  }
});
