export default {
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
