export default {
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
