import axios from "axios";
import store from "./store";


const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`
})


//on every request Axios make it's going to pass the authorization token
axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`
    return config;
})

export default axiosClient;