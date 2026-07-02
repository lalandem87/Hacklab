import type React from "react";
import { useAuth } from "../../context/AuthContext";
import { Navigate } from "react-router";

export function ProtectedRoute({ children }: { children: React.ReactNode }) {
  const { token } = useAuth();

  if (!token) return <Navigate to={"/login"} />;

  return <>{children}</>;
}
