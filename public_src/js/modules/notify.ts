import { Notyf } from "notyf";

export const notify = new Notyf({
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
      background: "hsl(40, 25%, 50%)",
      type: "warning",
      duration: 6000,
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
