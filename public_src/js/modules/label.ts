export default class Label {
  static initialize = () => {
    document
      .querySelectorAll(".input input")
      .forEach((input: HTMLInputElement) => {
        const container: HTMLElement = input.closest(".input");
        if (input.value !== "") {
          container.classList.add("activated");
        }
        input.addEventListener("focusin", () => {
          container.classList.add("activated");
          container.classList.add("focused");
        });
        input.addEventListener("focusout", () => {
          container.classList.remove("focused");
          if (input.value === "") {
            container.classList.remove("activated");
          }
        });
        input.addEventListener("change", () => {
          if (!container.classList.contains("activated")) {
            if (input.value !== "") {
              container.classList.add("activated");
            }
          }
        });
      });
  };
}
