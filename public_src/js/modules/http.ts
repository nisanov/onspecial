import { notify } from "./notify";
import axios, { AxiosInstance } from "axios";

export default class Http {
  static axios: AxiosInstance = axios.create({
    baseURL: process.env.APP_BASE_URL,
  });
  static favorite = (productId: string, state: boolean): any => {
    return Http.axios.post("/product/favorite/" + productId, {
      state,
    });
  }
  static ignore = (productId: string, state: boolean): any => {
    return Http.axios.post("/product/ignore/" + productId, {
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
        return Promise.reject(error);
      }
    );
  }
}