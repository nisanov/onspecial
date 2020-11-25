(function (factory) {
    typeof define === 'function' && define.amd ? define('app', factory) :
    factory();
}((function () { 'use strict';

    var Notify = {
        success(message) {
            this.instance.success(message);
        },
        error(message) {
            this.instance.error(message);
        },
        initialize() {
            this.instance = new Notyf({
                position: {
                    x: "center",
                    y: "bottom",
                },
                types: [
                    {
                        background: "hsl(110, 25%, 50%)",
                        type: "success",
                        duration: 4000,
                        dismissible: true,
                        ripple: false,
                        icon: false,
                    },
                    {
                        background: "hsl(0, 25%, 50%)",
                        type: "error",
                        duration: 6000,
                        dismissible: true,
                        ripple: false,
                        icon: false,
                    },
                ],
            });

            return this;
        },
    };

    var FloatingLabel = {
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

    var Axios = {
        favorite(productId, state) {
            return this.instance.post("/product/favorite/" + productId, {
                state,
            });
        },
        ignore(productId, state) {
            return this.instance.post("/product/ignore/" + productId, {
                state,
            });
        },
        initialize() {
            this.instance = axios.create({
                baseURL: "http://192.168.1.109/",
            });
            this.instance.interceptors.response.use(
                (response) => {
                    if (response.data.message !== undefined) {
                      notify.success(response.data.message);
                    }
                    return response;
                },
                (error) => {
                    return Promise.reject(error);
                }
            );

            return this;
        },
    };

    window.notify = Notify.initialize();
    FloatingLabel.initialize();
    window.http = Axios.initialize();

    const danger = document.querySelector(".alert.alert-danger");
    if (danger !== null && danger.innerHTML !== "") {
        notify.error(danger.innerHTML);
    }

    const success = document.querySelector(".alert.alert-success");
    if (success !== null && success.innerHTML !== "") {
        notify.success(success.innerHTML);
    }

    const fab = document.querySelector(".fab button");
    if (fab !== null) {
        fab.addEventListener("click", (event) =>
            event.target.closest(".fab").classList.toggle("activated")
        );
    }

    const topper = document.querySelector(".topper button");
    if (topper !== null) {
        const accessibility = () =>
            topper.classList.toggle(
                "activated",
                document.documentElement.scrollTop > 2000
            );

        accessibility();

        document.addEventListener("scroll", accessibility);

        topper.addEventListener("click", () =>
            document.documentElement.scrollTo({ top: 0, behavior: "smooth" })
        );

        let seen = 0;
        const products = document.querySelectorAll(".product");
        const observer = new IntersectionObserver(
            (entries, observer) => {
                let inPosition = true;
                entries.some((entry) => {
                    const position = [].indexOf.call(products, entry.target) + 1;
                    if (!entry.isIntersecting) {
                        if (position === 1) {
                            inPosition = false;
                        } else if (inPosition === true) {
                            return true;
                        }
                    } else { 
                        inPosition = true;
                    }
                    const image = entry.target.querySelector('.thumbnail img');
                    image.src = image.dataset.src;
                    observer.unobserve(entry.target);
                    if (position > seen) {
                        seen = position;
                        document.getElementById("seen").innerText = seen.toString();
                    }
                });
            },
            {
                root: null,
                rootMargin: "0px",
                threshold: 0.75,
            }
        );

        products.forEach((product) => {
            observer.observe(product);
            product
                .querySelector(".product .controls .favorite")
                .addEventListener("click", (event) => {
                    const product = event.target.closest(".product");
                    const productId = product.dataset.id;
                    const button = product.querySelector(".controls .favorite");
                    const favorited = !button.classList.contains("favorited");
                    http.favorite(productId, favorited).then(() => {
                        const svg = button.querySelector("svg use");
                        svg.href.baseVal = svg.href.baseVal.replace(/#heart.*$/, favorited ? "#heart-solid" : "#heart");
                        button.classList.toggle("favorited");
                    });
                });
            product
                .querySelector(".product .controls .ignoring")
                .addEventListener("click", (event) => {
                    const product = event.target.closest(".product");
                    const productId = product.dataset.id;
                    const button = product.querySelector(".controls .ignoring");
                    const ignored = !button.classList.contains("ignored");
                    http.ignore(productId, ignored).then(() => {
                        button.classList.toggle("ignored");
                    });
                });
            product
                .querySelector(".product .controls .dropdown")
                .addEventListener("click", (event) => {
                    event.target.closest(".product").classList.toggle("more");
                });
        });

        window.lightbox = basicLightbox.create(
            document.getElementById("lightbox").innerHTML
        );

        document.querySelectorAll(".thumbnail").forEach((thumbnail) =>
            thumbnail.addEventListener("click", () => {
                const image = lightbox.element();
                image.querySelector("img").src = thumbnail.querySelector("img").dataset.image;
                lightbox.show();
            })
        );
    }

})));

//# sourceMappingURL=app-a2add1aac6.js.map
