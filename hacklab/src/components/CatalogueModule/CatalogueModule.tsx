import { NavLink } from "react-router";
import type { JSX } from "react/jsx-runtime";
import "./CatolgueModule.scss";
import { ModuleCard } from "../ModuleCard/ModuleCard";
import { useFetch } from "../../utilities/useFetch";

export function CatalogueModule(): JSX.Element {
  const { data: modules, loading } = useFetch("module");

  if (loading) return <p>Chargement...</p>;
  return (
    <section className="catalogue-module">
      <div className="catalogue-module-top">
        <div className="catalogue-module-top-left">
          <span className="badge-section">CATALOGUE</span>
          <h2>Modules populaires</h2>
        </div>
        <div className="catalogue-module-top-right">
          <NavLink className="btn-voir-modules" to="/module">
            Voir tous les modules
          </NavLink>
        </div>
      </div>
      <ModuleCard modules={modules} />
    </section>
  );
}
