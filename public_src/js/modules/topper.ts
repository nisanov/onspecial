export default class Topper {
  static instance: HTMLElement = document.createElement('div');
  static button: HTMLButtonElement;
  static initialize = () => {
    Topper.instance.classList.add('topper');
    Topper.instance.innerHTML = `
      <button>
          <svg>
              <use xlink:href="${document.querySelector('head').dataset.sprites}#arrow-up"></use>
          </svg>
      </button>
    `;
    document.body.insertAdjacentElement('beforeend', Topper.instance);
    Topper.button = Topper.instance.querySelector("button");
    Topper.button.addEventListener("click", () => document.documentElement.scrollTo({ top: 0, behavior: 'smooth' }));
    const activation = () => Topper.button.classList.toggle('activated', document.documentElement.scrollTop > 2000);
    document.addEventListener('scroll', activation);
    activation();
  }
}
