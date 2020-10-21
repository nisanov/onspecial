(function (factory) {
  typeof define === 'function' && define.amd ? define('app', factory) :
  factory();
}((function () { 'use strict';

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

  // https://carlosroso.com/notyf/
  const notyf = new Notyf({
    position: {
      x: "center",
      y: "bottom",
    },
    types: [
      {
        background: "hsl(110, 25%, 50%)",
        type: "success",
        duration: 3000,
        dismissible: true,
        ripple: false,
        icon: false,
      },
      {
        background: "hsl(0, 25%, 50%)",
        type: "error",
        duration: 5000,
        dismissible: true,
        ripple: false,
        icon: false,
      },
    ],
  });

  const danger = document.querySelector('.alert.alert-danger');
  if (danger !== null && danger.innerHTML !== "") {
      notyf.error(danger.innerHTML);
  }

  const success = document.querySelector('.alert.alert-success');
  if (success !== null && success.innerHTML !== "") {
      notyf.success(success.innerHTML);
  }

  FloatingLabel.initialize();

  const fab = document.querySelector(".fab button");
  if (fab !== null) {
      fab.addEventListener("click", event => event.target.closest('.fab').classList.toggle('activated'));
  }

})));

//# sourceMappingURL=app-9ccec1faca.js.map
