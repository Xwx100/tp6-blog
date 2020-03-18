import axios from "axios";
import { Message, Loading } from "element-ui";
import router from "./router";

let loading; //定义loading变量

function startLoading() {
  //使用Element loading-start 方法
  loading = Loading.service({
    lock: true,
    text: "加载中...",
    background: "rgba(0, 0, 0, 0.7)"
  });
}
function endLoading() {
  //使用Element loading-close 方法
  loading.close();
}

// 请求拦截  设置统一header
axios.interceptors.request.use(
  config => {
    // 加载
    startLoading();
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

// 响应拦截  401 token过期处理
axios.interceptors.response.use(
  response => {
    endLoading();
    let msg = response.data.msg;
    if (response.data.code === 0) {
      Message.success(msg)
    } else {
      Message.error(msg)
      return Promise.reject(msg)
    }
    return response;
  },
  error => {
    // http_code 错误
    endLoading();
    Message.error(error.response.data);

    const { status } = error.response;
    if (status == 401) {
      Message.error("token值无效，请重新登录");
      // 页面跳转
      router.push("/login");
    }

    return Promise.reject(error);
  }
);

export default axios;
