export default class Topper {
  static button: HTMLElement = document.querySelector(".topper button");
  static initialize = () => {
    if (Topper.button !== null) {
      Topper.button.addEventListener("click", () => document.documentElement.scrollTo({ top: 0, behavior: 'smooth' }));
      const activation = () => Topper.button.classList.toggle('activated', document.documentElement.scrollTop > 2000);
      document.addEventListener('scroll', activation);
      activation();
    }
  }
}
