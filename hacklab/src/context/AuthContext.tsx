import React, { createContext, useContext, useState } from "react";

interface AuthContextType {
  token: string | undefined;
  setToken: (token: string | undefined) => void;
}

const AuthContext = createContext<AuthContextType>({
  token: undefined,
  setToken: () => {},
});

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [token, setToken] = useState<string | undefined>(
    localStorage.getItem("token") ||
      sessionStorage.getItem("token") ||
      undefined,
  );

  return (
    <AuthContext.Provider value={{ token, setToken }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
