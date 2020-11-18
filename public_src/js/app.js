import FloatingLabel from "./plugins/FloatingLabel";
FloatingLabel.initialize();

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

const danger = document.querySelector(".alert.alert-danger");
if (danger !== null && danger.innerHTML !== "") {
  notyf.error(danger.innerHTML);
}

const success = document.querySelector(".alert.alert-success");
if (success !== null && success.innerHTML !== "") {
  notyf.success(success.innerHTML);
}

const fab = document.querySelector(".fab button");
if (fab !== null) {
  fab.addEventListener("click", (event) =>
    event.target.closest(".fab").classList.toggle("activated")
  );
}

const topper = document.querySelector(".topper button");
if (topper !== null) {
  const accessibility = () => topper.classList.toggle("activated", document.documentElement.scrollTop > 2000);
  accessibility();
  document.addEventListener("scroll", accessibility);
  topper.addEventListener("click", () => document.documentElement.scrollTo({ top: 0, behavior: "smooth" }));
}
