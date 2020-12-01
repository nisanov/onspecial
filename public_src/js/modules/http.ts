import { notify } from "./notify";
import axios, { AxiosInstance } from "axios";

export default class Http {
  static axios: AxiosInstance = axios.create({
    baseURL: process.env.APP_BASE_URL,
  });
  static favoriteProduct = (productId: string, state: boolean): any => {
    return Http.axios.post("/product/favorite/" + productId, {
      state,
    });
  }
  static ignoreProduct = (productId: string, state: boolean): any => {
    return Http.axios.post("/product/ignore/" + productId, {
      state,
    });
  }
  static ignoreBrand = (brandId: string, state: boolean): any => {
    return Http.axios.post("/brand/ignore/" + brandId, {
      state,
    });
  }
  static initialize = (): void => {
    Http.axios.interceptors.response.use(
      (response: any) => {
        if (response.data.message !== undefined) {
          notify.success(response.data.message);
        }
        return response;
      },
      (error: any) => {
        if (error.response.data.message !== undefined) {
          notify.error(error.response.data.message);
        }
        return Promise.reject(error);
      }
    );
  }
}