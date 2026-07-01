import type { JSX } from "react/jsx-runtime";
import { NavLink, useNavigate } from "react-router";
import "./Header.scss";
import { useFetchWithToken } from "../../utilities/useFetchWithToken";
import { useState } from "react";

export function Header(): JSX.Element {
  const [token, setToken] = useState(
    localStorage.getItem("token") ||
      sessionStorage.getItem("token") ||
      undefined,
  );

  const { data: me } = useFetchWithToken("user/me", "GET", token);
  const navigate = useNavigate();
  const deconnect = () => {
    localStorage.removeItem("token");
    sessionStorage.removeItem("token");
    navigate("/");
    setToken(undefined);
  };

  return (
    <>
      <header>
        <div className="main-logo">
          <span className="logo-icon">&gt;_</span>
          <span className="logo-text">Hacklab</span>
        </div>
        <nav>
          <NavLink to="/">Home</NavLink>
          <NavLink to="/module">Modules</NavLink>
          <NavLink to="/classement">Classement</NavLink>
          <NavLink to="/certification">Certifications</NavLink>
          <NavLink to="/communaute">Communauté</NavLink>
        </nav>
        {token ? (
          <div className="login-part">
            <NavLink to={`/dashboard/user/${me?.id}`}>Mon profil</NavLink>
            <button onClick={deconnect}>Déconnection</button>
          </div>
        ) : (
          <div className="login-part">
            <NavLink to="/login">Connexion</NavLink>
            <NavLink className="btn-start" to="/register">
              Commencer gratuitement
            </NavLink>
          </div>
        )}
      </header>
    </>
  );
}
