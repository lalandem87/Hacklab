import { NavLink } from "react-router";
import type { JSX } from "react/jsx-runtime";
import { useFetch } from "../../utilities/useFetch";
import "./CatolgueModule.scss";
import { Tornado } from "lucide-react";

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
      <div className="catalogue-module-container">
        {modules.map((mod) => {
          return (
            <div key={mod.id} className="module-card">
              <div className="module-card-top">
                <div className="module-card-top-left">
                  <span className="difficulty">{mod.difficulty}</span>
                  <h3 className="title">{mod.name}</h3>
                  <em className="cat">{mod.categorie.name}</em>
                </div>
                <div className="module-card-top-right">
                  <span className="point">
                    <Tornado size={15} color="black" />
                    {mod.course.point + mod.challenge.point} pts
                  </span>
                </div>
              </div>
              <div className="module-card-bottom">
                <p className="desc">{mod.description}</p>
                <NavLink to={`/module/${mod.id}`}>Commencer</NavLink>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
}
