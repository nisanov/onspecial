import http from './http';
import topper from './topper';
import lightbox from './lightbox';

topper.initialize();
lightbox.initialize();

export default class Products {
  static seen: number = 0;
  static instance: HTMLElement = document.querySelector('.products');
  static observer: IntersectionObserver;
  // static lightbox = GLightbox();
  static initialize = () => {
    if (Products.instance === null) {
      return;
    }
    Products.observer = new IntersectionObserver((entries: Array<IntersectionObserverEntry>) => {
      let inPosition: boolean = true;
      entries.some((entry: IntersectionObserverEntry) => {
        const product: HTMLElement = entry.target as HTMLElement;
        const position: number = parseInt(product.dataset.position, 10);
        console.log();
        if (!entry.isIntersecting) {
          if (position === 1) {
            inPosition = false;
          } else if (inPosition === true) {
            return true;
          }
        } else {
          inPosition = true;
        }
        const image: HTMLImageElement = entry.target.querySelector('.thumbnail img');
        image.src = image.dataset.src;
        Products.observer.unobserve(product);
        if (position > Products.seen) {
          Products.seen = position;
          document.getElementById('seen').innerText = Products.seen.toString();
        }
      });
    }, {
      root: null,
      rootMargin: '0px',
      threshold: 0.75,
    });
    Products.instance.querySelectorAll('.product').forEach((product: HTMLElement) => Products.observer.observe(product));
    Products.instance.addEventListener('click', (event: MouseEvent): void => {
      const target: HTMLElement = event.target as HTMLElement;
      const product: HTMLElement = target.closest('.product');
      let source: HTMLElement = null;
      switch (true) {
        case (source = target.closest('.product .controls .favorite')) !== null:
          const favorited = !source.classList.contains('favorited');
          http.favorite(product.dataset.id, favorited).then(() => {
            const svg: SVGAElement = source.querySelector('svg use');
            svg.href.baseVal = svg.href.baseVal.replace(/#heart.*$/, favorited ? '#heart-solid' : '#heart');
            source.classList.toggle('favorited');
          });
          break;
        case (source = target.closest('.product .controls .ignoring')) !== null:
          const ignored = !source.classList.contains('ignored');
          http.ignore(product.dataset.id, ignored).then(() => {
            const ignoring = parseInt(document.getElementById('ignoring').innerText, 10);
            document.getElementById('ignoring').innerText = (ignored ? ignoring + 1 : ignoring - 1).toString();
            source.classList.toggle('ignored');
          });
          break;
        case (source = target.closest('.product .controls .dropdown')) !== null:
          if (!product.classList.contains('more')) {
            const json = JSON.parse(decodeURIComponent(product.dataset.product.replace(/\+/g, ' ')));
            const template = `
              <span>Brand:</span>
              <span>${json.brand}</span>
              <span>Store:</span>
              <span>${json.store}&nbsp;
                  <a class="svg link" href="${json.uri}" target="_blank" rel="noopener">
                      <svg>
                          <use xlink:href="${document.querySelector('head').dataset.sprites}#external-link-alt"></use>
                      </svg>
                  </a>
              </span>
              <span>Ratio:</span>
              <span>${json.ratio_price}</span>
              <span>Description:</span>
              <span>${json.description ? json.description : '-'}</span>
              <span>Ingredients:</span>
              <span>${json.ingredients ? json.ingredients : '-'}</span>
            `;
            const drawer = document.createElement('div');
            drawer.classList.add('drawer');
            drawer.innerHTML = template;
            product.insertAdjacentElement('beforeend', drawer);
          } else {
            product.querySelector('.drawer').remove();
          }
          product.classList.toggle('more');
          break;
        case (source = target.closest('.product .content .thumbnail')) !== null:
          const image: HTMLImageElement = source.querySelector('img') as HTMLImageElement;
          lightbox.open(image.dataset.image);
          break;
      }
    });
  }
}
