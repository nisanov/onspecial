export default class Lightbox {
  static instance: HTMLElement = document.createElement('div');
  static image: HTMLImageElement;
  static initialize = () => {
    Lightbox.instance.classList.add('lightbox');
    Lightbox.instance.innerHTML = `
      <button class="close">x</button>
      <img class="image" src="${document.querySelector('head').dataset.loader}" onerror="this.src='${document.querySelector('head').dataset.placeholder}';" alt="Product Image" />
    `;
    document.body.insertAdjacentElement('beforeend', Lightbox.instance);
    Lightbox.image = Lightbox.instance.querySelector('.image');
    Lightbox.instance.addEventListener('click', () => {
      Lightbox.instance.classList.remove('open');
      Lightbox.instance.addEventListener('transitionend', () => {
        Lightbox.image.src = document.querySelector('head').dataset.loader;
      }, { once: true });
    });
  }
  static open = (image: string): void => {
    Lightbox.image.src = image;
    Lightbox.instance.classList.add('open');
  }
}
