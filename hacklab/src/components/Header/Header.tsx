import type { JSX } from "react/jsx-runtime";
import { NavLink } from "react-router";
import "./Header.scss";

export function Header(): JSX.Element {
  return (
    <>
      <header>
        <div className="main-logo">
          <span className="logo-icon">&gt;_</span>
          <span className="logo-text">Hacklab</span>
        </div>
        <nav>
          <NavLink to="/module">Modules</NavLink>
          <NavLink to="/classement">Classement</NavLink>
          <NavLink to="/certification">Certifications</NavLink>
          <NavLink to="/communaute">Communauté</NavLink>
        </nav>
        <div className="login-part">
          <NavLink to="/login">Connexion</NavLink>
          <NavLink className="btn-start" to="/register">
            Commencer gratuitement
          </NavLink>
        </div>
      </header>
    </>
  );
}
