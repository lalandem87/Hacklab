import { NavLink } from "react-router";
import type { JSX } from "react/jsx-runtime";
import "./Footer.scss";

export function Footer(): JSX.Element {
  return (
    <footer>
      <div className="main-logo">
        <span className="logo-icon">&gt;_</span>
        <span className="logo-text">Hacklab</span>
      </div>
      <div className="links-wrapper">
        <NavLink to="/">Confidentialité</NavLink>
        <NavLink to="/">Mention Légales</NavLink>
        <NavLink to="/">Contact</NavLink>
        <NavLink to="/">Discord</NavLink>
      </div>
      <p>@ {new Date().getFullYear()} Hacklab. Tous droits réservés.</p>
    </footer>
  );
}
