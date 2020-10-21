export default {
  onFocusIn(event) {
    const container = event.target.closest(".input");
    container.classList.add("activated");
    container.classList.add("focused");
  },
  onFocusOut(event) {
    const container = event.target.closest(".input");
    container.classList.remove("focused");
    if (event.target.value === "") {
      container.classList.remove("activated");
    }
  },
  onChange(event) {
    const container = event.target.closest(".input");
    if (!container.classList.contains("activated")) {
      if (event.target.value !== "") {
        container.classList.add("activated");
      }
    }
  },
  initialize() {
    document.querySelectorAll(".input input").forEach((input) => {
      if (input.value !== "") {
        input.closest(".input").classList.add("activated");
      }
      input.addEventListener("focusin", this.onFocusIn, false);
      input.addEventListener("focusout", this.onFocusOut, false);
      input.addEventListener("change", this.onChange, false);
    });
  },
};
