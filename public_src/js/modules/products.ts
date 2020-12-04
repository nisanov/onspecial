import http from './http';
import topper from './topper';
import lightbox from './lightbox';
import { DateTime, Interval } from 'luxon';

topper.initialize();
lightbox.initialize();

export default class Products {
  static seen: number = 0;
  static instance: HTMLElement = document.querySelector('.products');
  static observer: IntersectionObserver;
  static initialize = () => {
    if (Products.instance === null) {
      return;
    }
    const onScroll = () => {
      if ((window.innerHeight + Math.ceil(window.pageYOffset)) >= document.body.offsetHeight) {
        const loader: string = document.querySelector('head').dataset.loader;
        [].slice.call(document.querySelector('.products').querySelectorAll('.product')).reverse().some((product: HTMLElement) => {
          const image: HTMLImageElement = product.querySelector('.thumbnail img');
          const position: number = parseInt(product.dataset.position, 10);
          if (image.src === loader) {
            image.src = image.dataset.src;
            Products.observer.unobserve(product);
            if (position > Products.seen) {
              Products.seen = position;
              document.getElementById('seen').innerText = Products.seen.toString();
            }
            return false;
          }
          return true;
        });
        window.removeEventListener('scroll', onScroll);
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    Products.observer = new IntersectionObserver((entries: Array<IntersectionObserverEntry>) => {
      let inPosition: boolean = true;
      entries.some((entry: IntersectionObserverEntry) => {
        const product: HTMLElement = entry.target as HTMLElement;
        const position: number = parseInt(product.dataset.position, 10);
        if (!entry.isIntersecting) {
          if (position === 1) {
            inPosition = false;
          } else if (inPosition === true) {
            return true;
          }
        } else {
          inPosition = true;
        }
        const image: HTMLImageElement = product.querySelector('.thumbnail img');
        image.src = image.dataset.src;
        if (entry.intersectionRatio >= 0.75) {
          Products.observer.unobserve(product);
          if (position > Products.seen) {
            Products.seen = position;
            document.getElementById('seen').innerText = Products.seen.toString();
          }
        }
        return false;
      });
    }, {
      root: null,
      rootMargin: '0px',
      threshold: [0.10, 0.75],
    });
    Products.instance.querySelectorAll('.product').forEach((product: HTMLElement) => Products.observer.observe(product));
    Products.instance.addEventListener('click', (event: MouseEvent): void => {
      const target: HTMLElement = event.target as HTMLElement;
      const product: HTMLElement = target.closest('.product');
      let source: HTMLElement = null;
      switch (true) {
        case (source = target.closest('.product .content .thumbnail')) !== null:
          const image: HTMLImageElement = source.querySelector('img') as HTMLImageElement;
          lightbox.open(image.dataset.image);
          break;
        case (source = target.closest('.product .controls .favorite')) !== null:
          const favoritedProduct = !source.classList.contains('favorited');
          http.favoriteProduct(product.dataset.id, favoritedProduct).then(() => {
            const svg: SVGAElement = source.querySelector('svg use');
            svg.href.baseVal = svg.href.baseVal.replace(/#heart.*$/, favoritedProduct ? '#heart-solid' : '#heart');
            source.classList.toggle('favorited');
          });
          break;
        case (source = target.closest('.product .controls .ignoring')) !== null:
          const ignoredProduct = !source.classList.contains('ignored');
          http.ignoreProduct(product.dataset.id, ignoredProduct).then(() => {
            const ignoring = parseInt(document.getElementById('ignoring').innerText, 10);
            document.getElementById('ignoring').innerText = (ignoredProduct ? ignoring + 1 : ignoring - 1).toString();
            source.classList.toggle('ignored');
          });
          break;
        case (source = target.closest('.product .controls .dropdown')) !== null:
          if (!product.classList.contains('more')) {
            const json = JSON.parse(decodeURIComponent(product.dataset.product.replace(/\+/g, ' ')));
            const template = (
              json.brand_id !== null 
                ? `
                  <button class="svg ignoring ${json.ignoring_brand === "1" ? 'ignored' : ''}">
                    <svg>
                      <use xlink:href="${document.querySelector('head').dataset.sprites}#ban"></use>
                    </svg>
                  </button>
                  ` 
                : `` 
              ) + `
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
              <span>Since:</span>
              <span>${DateTime.fromSQL(json.created_at).toLocaleString(DateTime.DATE_MED_WITH_WEEKDAY)} (${parseInt(Interval.fromDateTimes(DateTime.fromSQL(json.created_at), DateTime.local()).length('days').toString(), 10)} days)</span>
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
        case (source = target.closest('.product .drawer .ignoring')) !== null:
          const ignoredBrand = !source.classList.contains('ignored');
          http.ignoreBrand(product.dataset.brandId, ignoredBrand).then((response) => {
            document.getElementById('ignoring').innerText = response.data.ignoring;
            source.classList.toggle('ignored');
          });
          break;
      }
    });
  }
}
