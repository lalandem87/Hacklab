import { NavLink } from "react-router";
import type { JSX } from "react/jsx-runtime";
import { useFetch } from "../../utilities/useFetch";

export function CatalogueModule(): JSX.Element {
  const { data: modules, loading } = useFetch("module");
  return (
    <section className="catalogue-module">
      <div className="catalogue-module-top">
        <div className="catalogue-module-top-left">
          <span className="badge-section">CATALOGUE</span>
          <h2>Modules populaires</h2>
        </div>
        <div className="catalogue-module-top-right">
          <NavLink to="/module">Voir tous les modules</NavLink>
        </div>
      </div>
      <div className="catalogue-module-container">
        {modules.map((mod) => {
          return <div className=""></div>;
        })}
      </div>
    </section>
  );
}
