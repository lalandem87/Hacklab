import type { JSX } from "react/jsx-runtime";
import "./EnterMatrice.scss";
import { NavLink } from "react-router";

export function EnterMatrice(): JSX.Element {
  return (
    <section className="enter-matrice">
      <div className="enter-matrice-container">
        <h2>Prêt à entrer dans la matrice ?</h2>
        <p className="desc-section">
          Rejoins des milliers d'apprenants et commence ton parcours en
          cybersé©curité dés aujourd'hui. Gratuit, sans engagement.
        </p>
        <NavLink to="/register">Créer un compte gratuitement</NavLink>
      </div>
    </section>
  );
}
