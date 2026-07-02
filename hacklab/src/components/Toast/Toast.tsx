import { useEffect } from "react";
import "./Toast.scss";

interface ToastProps {
  message: string;
  type: "success" | "error";
  onClose: () => void;
}

export function Toast({ message, type, onClose }: ToastProps) {
  useEffect(() => {
    const timer = setTimeout(onClose, 3000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <div className={`toast toast-${type}`}>
      {type === "success" ? "✓" : "✗"} {message}
    </div>
  );
}
