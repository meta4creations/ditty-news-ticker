const { __ } = wp.i18n;
import { ToastContainer, toast } from "react-toastify";
import { ReactComponent as Logo } from "../../images/d.svg";
import "react-toastify/dist/ReactToastify.css";

export const DittyNotificationContainer = () => {
  return <ToastContainer />;
};

export const dittyNotification = (
  notification,
  type = "success",
  args = {}
) => {
  const settings = {
    autoClose: 2000,
    icon: <Logo style={{ height: "30px" }} />,
    className: `ditty-${type}`,
    ...args,
  };

  if (typeof notification === "object" && "error" == type) {
    let message = __("Whoops! Something went wrong...", "ditty-news-ticker");
    if (notification.message) {
      message = notification.message;
    }
    if (
      notification.response &&
      notification.response.data &&
      notification.response.data.message
    ) {
      message = notification.response.data.message;
    }
    notification = <div dangerouslySetInnerHTML={{ __html: message }} />;
  }
  toast(notification, settings);
};
