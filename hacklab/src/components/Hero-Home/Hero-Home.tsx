import type { JSX } from "react/jsx-runtime";
import "./Hero-Home.scss";
import { NavLink } from "react-router";

export function HeroHome(): JSX.Element {
  return (
    <section className="hero-home">
      <div className="hero-home-top">
        <span className="section-badge">
          Plateforme de cybersécurité française
        </span>
        <h1>
          Apprends la cybersécurité. <em>Reléve les défis.</em>
        </h1>
        <p className="desc-section">
          Des modules complets alliant cours théoriques et challenges pratiques
          pour progresser de débutant à expert en sécurité informatique.
        </p>
        <div className="buttons-hero">
          <NavLink className="btn-start" to="/register">
            Démarrer l'aventure
          </NavLink>
          <NavLink className="voir-modules" to="/module">
            Voir les modules
          </NavLink>
        </div>
      </div>
      <div className="hero-home-bottom">
        <img src="../../../public/images/hero-home.png" alt="image hero home" />
      </div>
    </section>
  );
}
