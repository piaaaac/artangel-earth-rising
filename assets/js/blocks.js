// ----------------------------------------------------------------------------
// Block: accordion
// ----------------------------------------------------------------------------

class Accordion {
  constructor(element) {
    this.accordion = element;
    this.items = this.accordion.querySelectorAll(".accordion-item");
    this.init();
    this.accordion.dataset.initialized = "true";
  }

  init() {
    this.items.forEach((item) => {
      const header = item.querySelector(".accordion-header");
      const content = item.querySelector(".accordion-content");
      const arrow = item.querySelector(".accordion-arrow");

      header.addEventListener("click", () => {
        this.toggleItem(item, content, arrow);
      });
    });
  }

  toggleItem(item, content, arrow) {
    const isActive = content.style.height && content.style.height !== "0px";

    // Close all items
    this.closeAllItems();

    // If the clicked item wasn't active, open it
    if (!isActive) {
      this.openItem(item, content, arrow);
    }
  }

  openItem(item, content, arrow) {
    const body = content.querySelector(".accordion-body");
    const header = item.querySelector(".accordion-header");
    const height = body.scrollHeight;

    content.style.height = height + "px";
    arrow.classList.add("active");

    setTimeout(() => {
      header.scrollIntoView({ behavior: "smooth", block: "start" });
    }, 300); // wait for opening animation if any
  }

  closeAllItems() {
    this.items.forEach((item) => {
      const content = item.querySelector(".accordion-content");
      const arrow = item.querySelector(".accordion-arrow");

      content.style.height = "0px";
      arrow.classList.remove("active");
    });
  }
}

// ----------------------------------------------------------------------------
// Utility functions
// ----------------------------------------------------------------------------

function initNewAccordions() {
  const accordions = document.querySelectorAll(
    ".accordion[data-initialized=false]"
  );
  accordions.forEach((accordionElement) => {
    const acc = new Accordion(accordionElement);
    console.log(acc);
  });
}

// // Initialize accordion when DOM is loaded
// document.addEventListener("DOMContentLoaded", () => {
//   const accordionElement = document.querySelector(".accordion");
//   new Accordion(accordionElement);
// });
