import type { JSX } from "react/jsx-runtime";
import { useFetch } from "../../utilities/useFetch";
import "./SideBar.scss";
import { useState } from "react";

interface SideBarProps {
  onCatChange: (cat: string) => void;
  onDiffChange: (cat: string) => void;
}

export function SideBar({
  onCatChange,
  onDiffChange,
}: SideBarProps): JSX.Element {
  const { data: categories, loading } = useFetch("categorie");
  const [activeCat, setActiveCat] = useState("Tous");
  const [activeDiff, setActiveDiff] = useState("Tous");

  if (loading) return <p>Chargement...</p>;

  return (
    <section className="side-bar">
      <div className="side-bar-wrapper">
        <div className="side-bar-wrapper-title">Catégorie</div>
        <div className="side-bar-wrapper-cats">
          <button
            className={activeCat === "Tous" ? "active" : ""}
            onClick={() => {
              onCatChange("Tous");
              setActiveCat("Tous");
            }}
          >
            Tous
          </button>
          {categories.map((cat) => {
            return (
              <button
                className={activeCat === cat.name ? "active" : ""}
                key={cat.id}
                onClick={() => {
                  onCatChange(cat.name);
                  setActiveCat(cat.name);
                }}
              >
                {cat.name}
              </button>
            );
          })}
        </div>
      </div>
      <div className="side-bar-wrapper">
        <div className="side-bar-wrapper-title">Difficulté</div>
        <div className="side-bar-wrapper-dif">
          <button
            className={activeDiff === "Tous" ? "active" : ""}
            onClick={() => {
              onDiffChange("Tous");
              setActiveDiff("Tous");
            }}
          >
            Tous niveaux
          </button>
          <button
            className={activeDiff === "Facile" ? "active" : ""}
            onClick={() => {
              onDiffChange("Facile");
              setActiveDiff("Facile");
            }}
          >
            Débutant
          </button>
          <button
            className={activeDiff === "Intermediaire" ? "active" : ""}
            onClick={() => {
              onDiffChange("Intermediaire");
              setActiveDiff("Intermediaire");
            }}
          >
            Intermédiaire
          </button>
          <button
            className={activeDiff === "Avance" ? "active" : ""}
            onClick={() => {
              onDiffChange("Avance");
              setActiveDiff("Avance");
            }}
          >
            Avancé
          </button>
        </div>
      </div>
    </section>
  );
}
