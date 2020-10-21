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

export default notyf;