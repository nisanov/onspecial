export default class Fab {
  static button: HTMLElement = document.querySelector(".fab button");
  static initialize = () => {
    if (Fab.button !== null) {
      Fab.button.addEventListener("click", () => {
        Fab.button.closest(".fab").classList.toggle("activated");
      });
    }
  }
}
